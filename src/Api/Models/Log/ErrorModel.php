<?php

namespace ApiModel\Log;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Classes\LogErro\Status;

final class ErrorModel extends ORM
{
    protected string $ormTabela = TABELA_LOG_ERRO;

    public function __construct(
        private Request $request
    ) {
        parent::__construct();
    }

    public function listarDado(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'mensagem', 'quantidade', 'status_http', 'data_criacao', 'status'])
            ->where([
                ['status', 1]
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();
        $dado->lista = $this->montarDado($dado->lista ?? []);
        return $dado;
    }

    private function montarDado($dado)
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'mensagem' => $r->mensagem,
                'quantidade' => $r->quantidade,
                'status_http' => $r->status_http,
                'data_criacao' => $r->data_criacao,
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    private function pegarPagina(): int
    {
        $pagina = $this->request->pagina;
        if (empty($pagina)) {
            return 1;
        }
        return preg_match('/^[1-9]{1}[0-9]{0,}$/', $pagina) ? $pagina : 1;
    }
    private function pegarQuantidade(): int
    {
        $quantidade = $this->request->quantidade;
        if (empty($quantidade)) {
            return 50;
        }
        return preg_match('/^[1-9]{1}[0-9]{0,}$/', $quantidade) ? $quantidade : 50;
    }
}
