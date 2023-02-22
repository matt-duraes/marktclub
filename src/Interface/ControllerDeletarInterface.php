<?php

namespace System\Interface;

use Http\Response;

interface ControllerDeletarInterface
{
    public function deleteDeletar(string $id): Response;
}
