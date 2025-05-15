<?php

namespace App\Classes\Saude\Cidade;

use App\Classes\Comercial\Empresa\UUID;

class Lista
{
    private array $estado = [];
    private array $cidade = [];
    private array $lista = [
        [
            'empresa' => [UUID::YOUHUUL, UUID::CFM],
            'estado'  => [
                ''   => 'Escolha um estado',
                'SP' => 'São Paulo',
                'RN' => 'Rio Grande do Norte',
            ],
            'cidade'  => [
                'SP' => [
                    ''                     => 'Escolha uma cidade',
                    'Barueri'              => 'Barueri',
                    'Cabreúva'             => 'Cabreúva',
                    'Caieiras'             => 'Caieiras',
                    'Cajamar'              => 'Cajamar',
                    'Campo Limpo Paulista' => 'Campo Limpo Paulista',
                    'Francisco Morato'     => 'Francisco Morato',
                    'Franco Da Rocha'      => 'Franco Da Rocha',
                    'Itupeva'              => 'Itupeva',
                    'Jarinú'               => 'Jarinú',
                    'Jundiaí'              => 'Jundiaí',
                    'Louveira'             => 'Louveira',
                    'Santana de Parnaíba'  => 'Santana de Parnaíba',
                    'Várzea Paulista'      => 'Várzea Paulista',
                ],
                'RN' => [],
            ],
        ],
    ];

    public function __construct()
    {
        $this->setarValorEmpresa();
    }

    public function pegarEstado(): array
    {
        return $this->estado;
    }

    public function pegarCidade(?string $estado): array
    {
        return $this->cidade[$estado] ?? [];
    }

    private function setarValorEmpresa(): void
    {
        foreach ($this->lista as $empresa) {
            if (!in_array(CLUBE_EMPRESA, $empresa['empresa'])) {
                continue;
            }
            $this->estado = $empresa['estado'];
            $this->cidade = $empresa['cidade'];
            return;
        }
    }
}
