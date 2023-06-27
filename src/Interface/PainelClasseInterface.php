<?php

namespace System\Interface;

use Http\Request;
use Http\Response;

interface PainelClasseInterface
{
    public function __construct(Request $request);
    public function retorno(): Response;
}
