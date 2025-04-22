<?php

namespace Erro\Retorno;

use Erro\Trait\StatusTrait;
use App\Models\Site\Link\ApiModel;

trait LogTrait
{
    use StatusTrait;

    private function salvarLogErro($mensagem, $codigo, $arquivo, $linha, $trace)
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = is_string($uri) ? urldecode($uri) : '';
        if (defined('ROTA_USO') && ROTA_USO == 'Api' && $uri == '/log/erro') {
            return;
        }
        $status = in_array($codigo, [400, 401, 403, 404, 500]) ? $codigo : 500;
        http_response_code($status);

        $body = [
            'mensagem' => $mensagem,
            'codigo'   => $codigo,
            'status'   => $status,
            'arquivo'  => $arquivo,
            'linha'    => $linha,
            'trace'    => json_encode($trace),
            'url'      => LINK . URI . QUERY_STRING
        ];

        $mensagem = 'Ocorreu um erro inesperado, clique em retornar para voltar a navegar. Geralmente esse tipo de erro é temporário, mas para os casos ele continue ocorrendo, já sinalizamos para a equipe técnica sobre o ocorrido, mas caso queira, você pode entre em contato com o suporte e informá-lo.';
        $idErro = '';
        try {
            $linkProd = (new ApiModel())->link;
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $linkProd . '/log-erro');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . env('API_LOG_TOKEN', '')
            ]);

            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

            $retorno = json_decode(curl_exec($ch), true);
            if (validarIndiceExiste($retorno, 'dado.id')) {
                $idErro = $retorno['dado']['id'];
            }
            curl_close($ch);
        } catch (\Throwable $e) {
            $mensagem = 'Ocorreu um erro inesperado, clique em retornar para voltar a navegar. Esse tipo de erro pode ser temporário, e normalmente os reportamos de forma automaticamente para a equipe técnica, infelizmente esse não foi reportado,por isso, caso o erro continue, entre em contato com o suporte e nos informe sobre esse para para ajudar a corrigí-lo o mais rápido possível.';
        }

        if (
            (defined('ROTA_VIEW') && true !== ROTA_VIEW) ||
            (array_key_exists('REQUEST_METHOD', $_SERVER) && $_SERVER['REQUEST_METHOD'] != 'GET')
        ) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'erro',
                'erro'   => [
                    'titulo'   => 'Erro interno!',
                    'mensagem' => 'Ocorreu um erro interno, por favor, tente novamente, se o erro persistir, contate o suporte.',
                    'codigo'   => 500
                ]
            ]);
            exit();
        }

        if (defined('FW_LOG_ERRO_EXISTE')) {
            exit();
        }
        define('FW_LOG_ERRO_EXISTE', true);

        $this->buscarStatusProjeto($status);
        require_once ROOT . '/src/Html/Excecao/' . $status . '.php';
        exit();
    }
}
