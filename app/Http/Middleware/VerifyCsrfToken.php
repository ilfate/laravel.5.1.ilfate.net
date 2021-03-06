<?php

namespace Ilfate\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as BaseVerifier;

class VerifyCsrfToken extends BaseVerifier
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'GuessSeries/*',
        'hex/action',
        'MathEffect/save',
        'MathEffect/saveName',
        'Spellcraft/action',
        'Spellcraft/createMage',
        'Spellcraft/mapBuilder/save',
    ];
}
