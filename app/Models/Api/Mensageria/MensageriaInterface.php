<?php

namespace App\Models\Api\Mensageria;

use Modules\Botao;

interface MensageriaInterface
{
    public function __construct(array $payload);
    public function pegarLinkEnvio(): string;
    public function pegarScopeEnvio(): string;
    public function pegarPayload(): array;
    public function vaiUsarApi(): Botao;
}
