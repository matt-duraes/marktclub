<?php

namespace System\Trait\Model;

use Erro\Excecao;
use Order\OrderInterface;

trait OrdemTrait
{
    /**
     * Pega a ordem do request
     *
     * @param  OrderInterface        $ordem       A classe de ordem
     * @param  null|string           $tabela      Tabela da ordem
     * @param  bool                  $obrigatorio Se é obrigatório ter uma ordem no request
     * @param  bool                  $valido      Se a ordem deve ser valida
     * @return string|OrderInterface id desc por padrão ou um OrdemInterface
     * @throws Excecao               Uma exeção com o erro
     */
    protected function pegarOrdem(
        ?OrderInterface $ordem = null,
        bool $obrigatorio = false,
        bool $valido = true
    ): string|OrderInterface {
        $valor = '';
        if (property_exists($this, 'request') && !empty($this->request->ordem)) {
            $valor = $this->request->ordem;
        }

        if (property_exists($this, 'request') && $this->request->existe('ordem')) {
            $valor = $this->request->ordem;
        } elseif (property_exists($this, 'ordem') && $this->ordem instanceof OrderInterface) {
            $valor = $this->ordem;
        } elseif (property_exists($this, 'ordem')) {
            $valor = $this->ordem;
        }

        if ($valor instanceof OrderInterface) {
            $ordem = $valor;
        } elseif ($ordem instanceof OrderInterface && is_string($valor)) {
            $ordem->valor($valor);
        }

        if ($ordem->vazio() && $obrigatorio) {
            mensagemErro('Campo obrigatório!', 'O campo ordem é obrigatório.');
        } elseif ($ordem->vazio()) {
            return $ordem;
        }

        if (!$ordem->valido() && $valido) {
            mensagemErro('Campo inválido!', 'O campo ordem está inválido.');
        }
        return $ordem;
    }
}
