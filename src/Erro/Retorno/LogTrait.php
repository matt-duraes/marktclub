<?php

namespace Erro\Retorno;

use Helpers\ApiHelper;

trait LogTrait
{

    private function salvarLogErro($mensagem, $codigo, $arquivo, $linha, $trace)
    {
        $status = in_array($codigo, [400, 401, 403, 404, 500]) ? $codigo : 500;
        http_response_code($status);

        $log = [
            'mensagem' => $mensagem,
            'codigo' => $codigo,
            'status' => $status,
            'arquivo' => $arquivo,
            'linha' => $linha,
            'trace' => $trace
        ];

        try {
            $Api = new ApiHelper('error_log');
            $Api->body($log)->post('/log/error')->object();
        } catch (\Throwable) {
        }

        $mensagem = '
            Ocorreu um erro inesperado, clique em retornar para voltar a navegar.
            Caso o problema continue, entre em contato com o suporte.
        ';

        require_once ROOT . '/src/Html/Excecao/' . $status . '.php';
        exit();
    }
}
