<?php

namespace App\Models\Site\Endereco;

use App\Helpers\ClubeApiHelper;

final class EstruturaModel extends ClubeApiHelper
{
    use LocalTrait;

    public array $estrutura = [
        'pais'   => [
            'quantidade' => 0,
            'lista'      => []
        ],
        'estado' => [
            'quantidade' => 0,
            'lista'      => []
        ],
        'cidade' => [
            'quantidade' => 0,
            'lista'      => []
        ]
    ];

    public function __construct(
        private string $id,
        private string $local,
        private string $pais,
        private string $estado
    ) {
        parent::__construct();
        $this->buscarDado();
    }

    private function buscarDado()
    {
        $lista = $this
            ->validar(login: true)
            ->json([
                'local_principal'  => $this->pegarLocal($this->local),
                'local_secundario' => 'clube',
                'vinculo'          => $this->id,
                'pais'             => $this->pais,
                'estado'           => $this->estado
            ])
            ->get('/endereco/estrutura')
            ->array();

        if (!chaveExiste('dado', $lista)) {
            return;
        }
        $lista = $lista['dado'];
        if (array_key_exists('pais', $lista)) {
            $this->estrutura['pais']['quantidade'] = count($lista['pais']);
            $this->estrutura['pais']['lista'] = count($lista['pais']) > 1 ? array_merge(['' => 'Escolha uma opção'], $lista['pais']) : $lista['pais'];
        }
        if (array_key_exists('estado', $lista)) {
            $this->estrutura['estado']['quantidade'] = count($lista['estado']);
            $this->estrutura['estado']['lista'] = count($lista['estado']) > 1 ? array_merge(['' => 'Escolha uma opção'], $lista['estado']) : $lista['estado'];
        }
        if (array_key_exists('cidade', $lista)) {
            $this->estrutura['cidade']['quantidade'] = count($lista['cidade']);
            $this->estrutura['cidade']['lista'] = count($lista['cidade']) > 1 ? array_merge(['' => 'Escolha uma opção'], $lista['cidade']) : $lista['cidade'];
        }
    }
}
