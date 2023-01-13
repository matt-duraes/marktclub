<?php

namespace System\Trait\Model;

trait QuantidadeTrait
{
    private function pegarQuantidade()
    {
        $request = $this->request;
        if (!$request->existe('quantidade')) {
            return 1;
        }
        $request->vazio('quantidade', 'O campo quantidade é obrigatório.');
        if (!preg_match('/^[1-9]{1}[0-9]{0,}$/', $request->quantidade)) {
            mensagemErro('Campo inválido!', 'O campo quantidade não é um valor padrão.');
        } else if ($request->quantidade > 50) {
            mensagemErro('Campo inválido!', 'O campo quantidade não pode ser maior que 50.');
        }
        return $request->quantidade;
    }
}
