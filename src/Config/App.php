<?php

namespace System\Config;

use System\System\System;

final class App
{
    public function run()
    {
        return (new System)->init();
    }
}
