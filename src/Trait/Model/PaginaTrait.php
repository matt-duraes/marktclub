<?php

namespace System\Trait\Model;

trait PaginaTrait
{
    private function pegarPagina()
    {
        $request = $this->request;
        if (!$request->existe('pagina')) {
            return 1;
        }
        $request->vazio('pagina', 'O campo pagina é obrigatório.');
        if (!preg_match('/^[1-9]{1}[0-9]{0,}$/', $request->pagina)) {
            mensagemErro('Campo inválido!', 'O campo pagina não é um valor padrão.');
        }
        return $request->pagina;
    }
}
