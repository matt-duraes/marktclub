<?php

namespace App\Models\Api\ParceiroCashback;

use ORM\ORM;
use stdClass;
use Http\Request;
use App\Classes\StatusGeral\Status;

class CashbackModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_CASHBACK;

    public function __construct(
        private ?Request $request
    ) {
        parent::__construct();
    }

    public function pegarRetorno(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'data_criacao', 'status'])
            ->pagina($this->request->pagina, 50)
            ->order('id', 'DESC')
            ->read();
        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno($lista): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($lista as $r) {
            $retorno[] = [
                'id'           => $r->uuid,
                'titulo'       => $r->titulo,
                'data_criacao' => $r->data_criacao,
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
