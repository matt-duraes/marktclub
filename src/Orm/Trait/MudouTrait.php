<?php

namespace ORM\Trait;

use Erro\Erro;

trait MudouTrait
{
    /**
     * @param Null|String   $propriedade    Propriedade que deseja verificar, caso não informado,
     *                                          buscara mudança na Entity inteira
     */
    protected function mudou(?string $propriedade = null)
    {
        if (empty($propriedade)) {
            return $this->ormVerificarSeEntityMudou();
        }
        return $this->ormVerificarSePropriedadeMudou($propriedade);
    }

    private function ormVerificarSePropriedadeMudou(string $propriedade)
    {
        $valorNovo = $this->ormPegarValorPropriedade($propriedade);
        $acao = empty($this->ormEntityId) ? 'insert' : 'update';
        if ($acao == 'insert') {
            return !empty($valorNovo);
        }

        $listaAlias = $this->ormListaAliasReal;
        $propriedadeReal = $listaAlias[$propriedade] ?? '';
        if (empty($propriedadeReal)) {
            return !empty($valorNovo);
        }

        $dadoAtual = $this->ormEntityRetorno;
        $valorAtual = $dadoAtual[$propriedadeReal] ?? '';
        return !is_null($valorNovo) && $valorAtual != $valorNovo;
    }

    private function ormVerificarSeEntityMudou()
    {
        if (!$this->ormListaSet) {
            throw new Erro(mensagem: 'Não existe uma lista para verificar se a Entity foi alterada.');
        }
        foreach ($this->ormListaSet as $val) {
            if (!property_exists($this, $val) || is_null($this->$val)) {
                continue;
            }
            if ($this->ormVerificarSePropriedadeMudou($val)) {
                return true;
            }
        }
        return false;
    }
}
