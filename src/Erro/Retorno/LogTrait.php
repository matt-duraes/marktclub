<?php

namespace Erro\Retorno;

use Helpers\ApiHelper;

trait LogTrait
{

    private function salvarLogErro($mensagem, $codigo, $arquivo, $linha, $trace)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = is_string($uri) ? urldecode($uri) : '';
        if (ROTA_USO == 'Api' && $uri == '/log/erro') {
            return;
        }
        $status = in_array($codigo, [400, 401, 403, 404, 500]) ? $codigo : 500;
        http_response_code($status);

        $body = [
            'mensagem' => $mensagem,
            'codigo' => $codigo,
            'status' => $status,
            'arquivo' => $arquivo,
            'linha' => $linha,
            'trace' => json_encode($trace)
        ];

        $mensagem = '
            Ocorreu um erro inesperado, clique em retornar para voltar a navegar.
            Geralmente esse tipo de erro é temporário, mas para os casos ele continue ocorrendo,
            já sinalizamos para a equipe técnica sobre o ocorrido, mas caso queira, você pode
            entre em contato com o suporte e informá-lo.
        ';

        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, env('API_URL') . '/log/error');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);


            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

            curl_exec($ch);
            curl_close($ch);
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
