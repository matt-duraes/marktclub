<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\ORM;

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
        $this->lista = $this->campo(['id_parceiro_loja', 'quantidade'])->where([
            ['id_admin_empresa', $empresa],
            ['data_acesso', 'between', [dataRemover(hoje(), 7, 'dias'), hoje() . ' 23:59:59']]
        ])->read();
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
        arsort($lista);
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
            if ($i > $quantidade) {
                return;
            }
            $retorno[] = $id;
            $i++;
        }
        $this->id = $retorno;
    }
}
