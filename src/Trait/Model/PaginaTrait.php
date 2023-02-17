<?php

namespace System\Trait\Model;

use Erro\Excecao;

trait PaginaTrait
{
    /**
     * Pega a página da request
     *
     * @param   bool    $obrigatorio    Se é obrigatório ter uma página no request
     * @param   bool    $valido         Se a página deve ser um número valido
     * @return  int                     O número da página
     * @throws  Excecao                 Uma exeção com o erro
     */
    protected function pegarPagina(bool $obrigatorio = true, bool $valido = true): int
    {
        $valor = '';
        if (property_exists($this, 'request')) {
            $valor = $this->request->pagina;
        }

        if (empty($valor) && $obrigatorio) {
            mensagemErro('Campo obrigatório!', 'O campo pagina é obrigatório.');
        } else if (empty($valor)) {
            return 1;
        }

        $validar = preg_match('/^[1-9]{1}[0-9]{0,}$/', $valor);
        if (!$validar && $valido) {
            mensagemErro('Campo inválido!', 'O campo pagina está inválido.');
        }
        return !$validar ? 1 : $valor;
    }
}
