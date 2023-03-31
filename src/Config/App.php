<?php

namespace System\Config;

use Http\Response;
use System\System\System;

final class App
{
    /**
     * @return Response
     */
    public function run(): Response
    {
        return (new System)->init();
    }
}
