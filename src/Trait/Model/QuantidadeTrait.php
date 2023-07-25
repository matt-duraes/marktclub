<?php

namespace System\Trait\Model;

use Erro\Excecao;
use Modules\Quantidade;

trait QuantidadeTrait
{
    /**
     * Pega a quantidade de registros por busca da request
     *
     * @param  bool    $obrigatorio Se é obrigatório ter uma quantidade no request
     * @param  bool    $valido      Se a quantidade deve ser um número valido
     * @return int     O número com a quantidade
     * @throws Excecao Uma exeção com o erro
     */
    protected function pegarQuantidade(bool $obrigatorio = false, bool $valido = true): int
    {
        $valor = '';
        if (property_exists($this, 'request') && $this->request->existe('quantidade')) {
            $valor = $this->request->quantidade;
        } elseif (property_exists($this, 'quantidade') && $this->quantidade instanceof Quantidade) {
            $valor = $this->quantidade->numero();
        } elseif (property_exists($this, 'quantidade')) {
            $valor = $this->quantidade;
        }

        if (empty($valor) && $obrigatorio) {
            mensagemErro('Campo obrigatório!', 'O campo quantidade é obrigatório.');
        } elseif (empty($valor)) {
            return 20;
        }

        $validar = preg_match('/^[1-9]{1}[0-9]{0,}$/', $valor);
        if (!$validar && $valido) {
            mensagemErro('Campo inválido!', 'O campo quantidade está inválido.');
        }
        return !$validar ? 20 : $valor;
    }
}
