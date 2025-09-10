<?php

declare(strict_types=1);

namespace App\Core;

use App\Http\Request;
use App\Http\Response;
use App\Interfaces\LoggerInterface;
use App\Interfaces\RouterInterface;
use App\Routing\Router;
use App\Services\LoggerService;
use Dotenv\Dotenv;
use Throwable;

class Bootstrap
{
    private Container $container;
    private RouterInterface $router;

    public function __construct()
    {
        $root = dirname(__DIR__, 2);
        if (class_exists(Dotenv::class)) {
            Dotenv::createImmutable($root)->safeLoad();
        }

        $this->container = new Container();
        $logPath = $_ENV['LOG_PATH'] ?? $root . '/storage/logs/app.log';
        $this->container->set(LoggerInterface::class, fn () => new LoggerService($logPath));

        $this->router = new Router($this->container);
        $this->container->set(RouterInterface::class, fn () => $this->router);
        $routes = require $root . '/config/routes.php';
        $routes($this->router);
    }

    public function handle(Request $request): Response
    {
        try {
            return $this->router->dispatch($request);
        } catch (Throwable $e) {
            $this->container->get(LoggerInterface::class)->log($e->getMessage());
            $debug = ($_ENV['APP_DEBUG'] ?? 'false') === 'true';
            $isJson = str_contains($request->header('Accept'), 'application/json');
            if ($isJson) {
                $body = ['error' => 'Server Error'];
                if ($debug) {
                    $body['message'] = $e->getMessage();
                    $body['trace'] = $e->getTrace();
                }
                return new Response(json_encode($body, JSON_THROW_ON_ERROR), 500, ['Content-Type' => 'application/json']);
            }
            $body = '<h1>Server Error</h1>';
            if ($debug) {
                $body .= '<pre>' . htmlspecialchars((string) $e) . '</pre>';
            }
            return new Response($body, 500, ['Content-Type' => 'text/html']);
        }
    }

    public function run(): void
    {
        $request = Request::capture();
        $response = $this->handle($request);
        $response = $response->withHeader('X-Frame-Options', 'DENY');
        $response->send();
    }
}
