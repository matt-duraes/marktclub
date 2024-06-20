<?php

namespace PainelConfig;

final class Historico
{
    public array $appExtra = [];

    public function __construct(
        public ?string $app = null,
        public bool $leitura = true,
        public bool $escrita = true,
        public bool $download = false,
        public bool $arquivo = false
    ) {
    }

    public function app(string $app, string $titulo)
    {
        $this->appExtra[$app] = $titulo;
    }
}
