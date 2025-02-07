<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;
use App\Classes\ParceiroLoja\Status;

final class MaisAcessadoModel extends ORM
{
    protected string $ormTabela = TABELA_ANALYTICS_LOJA;
    public array $id = [];
    private array $lista = [];

    public function __construct(int $empresa, int $quantidade)
    {
        parent::__construct();
        $this->pegarListaId($empresa);
        $this->somarResultados();
        $this->ordernarResultado();
        $this->pegarMaisAcessado($quantidade);
    }

    private function pegarListaId($empresa)
    {
        $this->lista = $this
            ->campo(['id_parceiro_loja', 'quantidade'])
            ->where([
                ['id_admin_empresa', $empresa],
                ['data_acesso', 'between', [dataRemover(hoje(), 20, 'dias'), hoje() . ' 23:59:59']]
            ])
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join(campo: 'id', relacao: 'id_parceiro_loja')
            ->where(['status', new Status(Status::CONCLUIDO)])
            ->read();
    }

    private function somarResultados()
    {
        $retorno = [];
        foreach ($this->lista as $r) {
            if (!array_key_exists($r->id_parceiro_loja, $retorno)) {
                $retorno[$r->id_parceiro_loja] = $r->quantidade;
                continue;
            }
            $retorno[$r->id_parceiro_loja] += $r->quantidade;
        }
        $this->lista = $retorno;
    }

    private function ordernarResultado()
    {
        if (!$this->lista) {
            return;
        }
        $lista = $this->lista;
        asort($lista);
        $this->lista = array_keys($lista);
    }

    private function pegarMaisAcessado($quantidade)
    {
        if (!$this->lista) {
            return;
        }
        $retorno = [];
        $i = 0;
        foreach ($this->lista as $id) {
            if ($i >= $quantidade) {
                break;
            }
            $retorno[] = $id;
            $i++;
        }
        $this->id = $retorno;
    }
}
