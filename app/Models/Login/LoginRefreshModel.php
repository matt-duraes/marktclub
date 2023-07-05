<?php

namespace App\Models\Login;

final class LoginRefreshModel
{
    public function __construct(
        private string $refreshToken
    ) {
    }
}
