<?php

namespace System\Trait\Model;

use Erro\Excecao;
use Order\OrderInterface;

trait OrdemTrait
{
    /**
     * Pega a ordem do request
     *
     * @param   OrderInterface          $ordem          A classe de ordem
     * @param   null|string             $tabela         Tabela da ordem
     * @param   bool                    $obrigatorio    Se é obrigatório ter uma ordem no request
     * @param   bool                    $valido         Se a ordem deve ser valida
     * @return  string|OrderInterface                   id desc por padrão ou um OrdemInterface
     * @throws  Excecao                                 Uma exeção com o erro
     */
    protected function pegarOrdem(
        OrderInterface $ordem,
        ?string $tabela = '',
        bool $obrigatorio = false,
        bool $valido = true
    ): string|OrderInterface {
        $valor = '';
        if (property_exists($this, 'request')) {
            $valor = $this->request->ordem;
        }

        $tabela = !empty($tabela) ? '`' . $tabela . '`.' : '';
        $ordemPadrao = $tabela . '`id` DESC';
        $ordem->valor($valor);

        if ($ordem->vazio() && $obrigatorio) {
            mensagemErro('Campo obrigatório!', 'O campo ordem é obrigatório.');
        } else if ($ordem->vazio()) {
            return $ordemPadrao;
        }


        if (!$ordem->valido() && $valido) {
            mensagemErro('Campo inválido!', 'O campo ordem está inválido.');
        }
        return !$ordem->valido() ? $ordemPadrao : $ordem;
    }
}
