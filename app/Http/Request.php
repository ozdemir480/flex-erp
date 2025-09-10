<?php

declare(strict_types=1);

namespace App\Http;

class Request
{
    public function __construct(
        private string $method,
        private string $uri,
        private array $query = [],
        private array $body = [],
        private array $headers = []
    ) {
    }

    public static function capture(): self
    {
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
        $query = $_GET;
        $body = $_POST;
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        return new self($method, $uri, $query, $body, $headers);
    }

    public function method(): string
    {
        return $this->method;
    }

    public function uri(): string
    {
        return $this->uri;
    }

    public function header(string $name): string
    {
        return $this->headers[$name] ?? '';
    }

    public function input(string $key, ?string $default = null): ?string
    {
        return $this->body[$key] ?? $default;
    }

    public function query(string $key, ?string $default = null): ?string
    {
        return $this->query[$key] ?? $default;
    }

    public static function sessionToken(): string
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (!isset($_SESSION['_token'])) {
            $_SESSION['_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['_token'];
    }
}
