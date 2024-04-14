<?php

namespace App\Models\Site\TelefoneEmail;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class ContatoModel extends ClubeApiHelper
{
    public stdClass $contato;

    public function __construct(
        private string $vinculo,
        private string $local,
        private string $tipo,
        private int $pagina,
    ) {
        parent::__construct();
        $this->buscarContato();
        $this->montarContato();
    }

    private function buscarContato()
    {
        $lista = [
            'loja' => 'parceiro_loja'
        ];
        $busca = $this
            ->json([
                'vinculo'          => $this->vinculo,
                'local_principal'  => $lista[$this->local] ?? '',
                'local_secundario' => 'clube',
                'tipo'             => $this->tipo,
                'pagina'           => $this->pagina
            ])
            ->get('/contato')
            ->object();
        if (!chaveExiste('dado.lista', $busca)) {
            $this->contato = (object)[
                'lista'  => [],
                'pagina' => []
            ];
            return;
        }
        $this->contato = (object)[
            'lista'  => $busca->dado->lista,
            'pagina' => $busca->dado->pagina
        ];
    }

    private function montarContato()
    {
        $retorno = [];
        foreach ($this->contato->lista as $r) {
            $retorno[] = (object)[
                'id'      => $r->id,
                'titulo'  => !empty($r->titulo) ? $r->titulo : $r->nome,
                'valor'   => $r->valor
            ];
        }
        $this->contato->lista = $retorno;
    }
}
