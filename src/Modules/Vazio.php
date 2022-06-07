<?php

namespace Modules;

final class Vazio implements ModuleInterface
{
    public function __toString()
    {
        return '';
    }
}
