<?php

namespace PainelApp\login\Models;

use stdClass;

interface LoginInterface
{
    public function pegarToken(): stdClass;
}
