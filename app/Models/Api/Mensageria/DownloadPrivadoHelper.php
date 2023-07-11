<?php

namespace App\Models\Api\Mensageria;

use Modules\Botao;

final class DownloadPrivadoHelper implements MensageriaInterface
{
    private string $app;
    private array $dado = [
        'usuario_cliente' => [
            'uri'    => '/usuario-cliente/download',
            'scope'  => 'usuario_cliente:download',
            'metodo' => 'POST'
        ],
        'solicitacao_premium' => [
            'uri'    => '/solicitacao-premium/download',
            'scope'  => 'solicitacao_premium:download',
            'metodo' => 'POST'
        ],
        'solicitacao_salavip' => [
            'uri'    => '/solicitacao-salavip/download',
            'scope'  => 'solicitacao_salavip:download',
            'metodo' => 'POST'
        ],
        'solicitacao_voucher' => [
            'uri'    => '/solicitacao-voucher/download',
            'scope'  => 'solicitacao_voucher:download',
            'metodo' => 'POST'
        ]
    ];

    public function __construct(
        private array $payload
    ) {
        $this->app = $payload['app'] ?? '';
    }

    public function pegarLinkEnvio(): string
    {
        return $this->dado[$this->app]['uri'] ?? '';
    }

    public function pegarScopeEnvio(): string
    {
        return $this->dado[$this->app]['scope'] ?? '';
    }

    public function pegarMetodoEnvio(): string
    {
        return $this->dado[$this->app]['metodo'] ?? '';
    }

    public function pegarPayload(): array
    {
        $payload = $this->payload;
        unset($payload['app'], $payload['usuario']);

        return $payload;
    }

    public function vaiUsarApi(): Botao
    {
        return new Botao('sim');
    }
}
