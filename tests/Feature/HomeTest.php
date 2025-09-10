<?php

declare(strict_types=1);

use App\Core\Bootstrap;
use App\Http\Request;
use PHPUnit\Framework\TestCase;

final class HomeTest extends TestCase
{
    public function test_home_page_returns_hello(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['REQUEST_URI'] = '/';

        $app = new Bootstrap();
        $response = $app->handle(Request::capture());

        $this->assertSame(200, $response->getStatusCode());
        $this->assertStringContainsString('Hello', $response->getBody());
    }
}
