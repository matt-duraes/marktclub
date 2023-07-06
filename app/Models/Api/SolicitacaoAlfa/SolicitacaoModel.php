<?php

namespace App\Models\Api\SolicitacaoAlfa;

use Erro\Excecao;
use ORM\ORM;

class SolicitacaoModel extends ORM
{
    protected string $ormTabela = TABELA_SOLICITACAO_ALFA;

    /**
     * @param string $codigo
     *
     * @return bool
     * @throws Excecao
     */
    public function verificarExisteCodigo(string $codigo): bool
    {
        $solicitacao = $this
            ->tabela($this->ormTabela)
            ->campo(['uuid', 'codigo'])
            ->where(['codigo', '=', $codigo])
            ->read();
        return !empty($solicitacao);
    }
}
