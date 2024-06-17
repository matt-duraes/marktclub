<?php

namespace PainelConfig;

final class Visualizar
{
    public array $appFinal;

    public function __construct(
        private string $app,
        public bool $leitura = true,
        public bool $escrita = true,
    ) {
        $this->app($app);
    }

    public function app(string $app)
    {
        $this->appFinal[] = $app;
    }
}
