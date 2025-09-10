<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Http\Request;
use App\Http\Response;
use App\Interfaces\ControllerInterface;

class HomeController implements ControllerInterface
{
    public function index(Request $request, array $params = []): Response
    {
        $content = $this->render('home.php', ['message' => 'Hello']);
        $response = new Response($content);
        return $response->withHeader('Content-Type', 'text/html');
    }

    private function render(string $view, array $data = []): string
    {
        extract($data);
        ob_start();
        include __DIR__ . '/../Views/' . $view;
        return (string) ob_get_clean();
    }
}
