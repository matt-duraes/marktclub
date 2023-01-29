<?php

namespace App\Models\Api\Mensageria;

final class DownloadPrivadoHelper implements MensageriaInterface
{
    private string $app;
    private array $dado = [
        'usuario_cliente' => [
            'uri' => '/usuario-cliente/download',
            'scope' => 'usuario_cliente:download'
        ]
    ];

    public function __construct(
        private array $payload
    ) {
        $this->app = $payload['app'] ?? '';
    }
    public function pegarLinkEnvio(): string
    {
        return LINK_API . $this->dado[$this->app]['uri'] ?? '';
    }
    public function pegarScopeEnvio(): string
    {
        return LINK_API . $this->dado[$this->app]['scope'] ?? '';
    }
    public function tratarPayload(): array
    {
        $payload = $this->payload;
        unset($payload['app']);
        unset($payload['usuario']);
        return $payload;
    }
    public function pegarUsuario(): string
    {
        return $this->payload['usuario'] ?? '';
    }
}
