<?php

namespace App\Models\Api\Mensageria;

interface MensageriaInterface
{
    public function __construct(array $payload);
    public function pegarLinkEnvio(): string;
    public function pegarScopeEnvio(): string;
    public function tratarPayload(): array;
    public function pegarUsuario(): string;
}
