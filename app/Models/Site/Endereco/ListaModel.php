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
        private string $estado,
        private string $cidade,
        private int $pagina
    ) {
        parent::__construct();
        $this->buscarDado();
        if (vazio($this->busca)) {
            $this->endereco = (object)[
                'pagina' => null,
                'lista'  => []
            ];
            return;
        }
        $this->endereco = (object)[
            'pagina' => $this->busca->pagina,
            'lista'  => $this->montarRetorno($this->busca->lista)
        ];
    }

    private function buscarDado()
    {
        $this->busca = $this
            ->validar(login: true)
            ->json([
                'local_principal'  => $this->pegarLocal($this->local),
                'local_secundario' => 'clube',
                'vinculo'          => $this->id,
                'pagina'           => $this->pagina,
                'estado'           => $this->estado,
                'cidade'           => $this->cidade
            ])
            ->get('/endereco')
            ->object();
    }
}
