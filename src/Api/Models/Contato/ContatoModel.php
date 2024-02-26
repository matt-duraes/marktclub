<?php

namespace ApiModel\Contato;

use ORM\ORM;
use System\Classes\Contato\Local;
use System\Classes\Contato\Tipo;
use System\Classes\Contato\Nome;
use System\Trait\Model\OrdemTrait;

final class ContatoModel extends ORM
{
    use OrdemTrait;

    protected string $ormTabela = TABELA_SISTEMA_CONTATO;

    public function __construct(
        private string|array|null $vinculo,
        private Local $local,
        private Tipo $tipo,
        private Nome $nome,
    ) {
        parent::__construct();
    }

    public function listarDados()
    {
        $dado = $this
            ->campo([
                'uuid', 'contato', 'local', 'tipo', 'outro', 'nome', 'documento', 'valor'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem())
            ->read();

        return $this->montarRetorno($dado);
    }

    private function pegarWhere()
    {
        $where = [];
        if ($this->local->valido()) {
            $where[] = ['local', $this->local->numero()];
        }
        if ($this->tipo->valido()) {
            $where[] = ['tipo', $this->tipo->numero()];
        }
        if (!empty($this->vinculo) && is_array($this->vinculo)) {
            $where[] = ['id_vinculo', 'IN', $this->vinculo];
        }
        if (!empty($this->vinculo) && !is_array($this->vinculo)) {
            $where[] = ['id_vinculo', $this->vinculo];
        }
        return $where;
    }

    private function montarRetorno($dado)
    {
        $local = new Local();
        $tipo = new Tipo();
        $nome = new Nome();

        $retorno = [];
        foreach ($dado as $item) {
            $retorno[] = [
                'contato'   => $item->contato,
                'local'     => $local->indice($item->local),
                'tipo'      => $tipo->indice($item->tipo),
                'outro'     => $item->outro,
                'nome'      => $nome->indice($item->nome),
                'documento' => $item->documento,
                'valor'     => $item->valor,
            ];
        }
        return $retorno;
    }
}
