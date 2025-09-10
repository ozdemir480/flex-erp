<?php

declare(strict_types=1);

use App\Http\Request;

function csrf_input(): string
{
    // TODO: proper CSRF protection
    $token = Request::sessionToken();
    return '<input type="hidden" name="_token" value="' . htmlspecialchars($token, ENT_QUOTES) . '">';
}
