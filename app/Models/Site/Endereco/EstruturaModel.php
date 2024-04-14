<?php

namespace App\Models\Site\Endereco;

use App\Helpers\ClubeApiHelper;

final class EstruturaModel extends ClubeApiHelper
{
    use LocalTrait;

    public array $lista = [];

    public function __construct(
        private string $id,
        private string $local,
        private string $pais,
        private string $estado,
        private string $cidade
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
                'estado'           => $this->estado,
                'cidade'           => $this->cidade,
            ])
            ->get('/endereco/estrutura')
            ->object()->dado ?? [];
        if (!$lista) {
            return;
        }
        $this->lista = array_merge(['' => 'Escolha uma opção'], $lista);
    }
}
