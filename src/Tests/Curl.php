<?php

namespace Tests;

use Helpers\CurlHelper;

final class Curl extends CurlHelper
{

    public function __construct($url)
    {
        parent::__construct($url);
    }
}
