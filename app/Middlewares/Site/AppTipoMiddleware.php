<?php

namespace App\Middlewares\Site;

use Http\Response;
use Helpers\ApiHelper;

final class AppTipoMiddleware extends ApiHelper
{
    public function __construct()
    {
    }

    public function localhost()
    {
        if (!eLocalhost()) {
            return new Response(status: 404);
        }
        return true;
    }
}
