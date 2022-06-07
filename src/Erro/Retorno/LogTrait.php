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

        $mensagem = '
            Ocorreu um erro inesperado, clique em retornar para voltar a navegar.
            Geralmente esse tipo de erro é temporário, mas para os casos ele continue ocorrendo,
            já sinalizamos para a equipe técnica sobre o ocorrido, mas caso queira, você pode
            entre em contato com o suporte e informá-lo.
        ';

        try {
            $Api = new ApiHelper('error_log');
            $Api->body($log)->post('/log/error')->object();
        } catch (\Throwable) {
            $mensagem = '
                Ocorreu um erro inesperado, clique em retornar para voltar a navegar.
                Esse tipo de erro pode ser temporário, e normalmente os reportamos de forma
                automaticamente para a equipe técnica, infelizmente esse não foi reportado,
                por isso, caso o erro continue, entre em contato com o suporte e nos informe
                sobre esse para para ajudar a corrigí-lo o mais rápido possível.
            ';
        }

        require_once ROOT . '/src/Html/Excecao/' . $status . '.php';
        exit();
    }
}
