<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        'payment-callback',      // Tanpa slash
        '/payment-callback',     // Dengan slash (untuk safety)
        'payment-callback',
        '/payment-callback',
        'payment/*',
    ];
}