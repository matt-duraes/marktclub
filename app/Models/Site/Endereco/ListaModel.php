<?php

namespace App\Models\Site\Endereco;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class ListaModel extends ClubeApiHelper
{
    use LocalTrait;
    use RetornoTrait;

    public stdClass $endereco;
    private stdClass $busca;

    public function __construct(
        private string $id,
        private string $local,
        private string $pais,
        private string $estado,
        private string $cidade,
    ) {
        parent::__construct();
        $this->buscarDado();
        $this->endereco = (object)[
            'pagina' => $this->busca->pagina,
            'lista'  => $this->montarRetorno($this->busca->lista)
        ];
    }

    private function buscarDado()
    {
        $busca = $this
            ->validar(login: true)
            ->json([
                'local_principal'  => $this->pegarLocal($this->local),
                'local_secundario' => 'clube',
                'vinculo'          => $this->id,
                'pagina'           => 1,
                'pais'             => $this->pais,
                'estado'           => $this->estado,
                'cidade'           => $this->cidade
            ])
            ->get('/endereco')
            ->object();

        if (!chaveExiste('dado', $busca)) {
            $this->busca = (object)[
                'pagina' => [],
                'lista'  => []
            ];
            return;
        }

        $this->busca = (object)[
            'pagina' => $busca->dado->pagina,
            'lista'  => $busca->dado->lista
        ];
    }
}
