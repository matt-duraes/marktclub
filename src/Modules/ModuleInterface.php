<?php

namespace Modules;

interface ModuleInterface
{
    public function real(): mixed;

    public function valor();

    public function banco(): mixed;

    public function vazio(): bool;

    public function valido(): bool;

    public function validar(string $campo, bool $vazio = true): void;
}
