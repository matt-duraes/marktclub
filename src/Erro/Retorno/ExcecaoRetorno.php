<?php

namespace Erro\Retorno;

use Erro\Excecao;

final class ExcecaoRetorno
{
    private $retorno;

    public function __construct(Excecao $excecao)
    {
        $retorno = $excecao->retorno();
        http_response_code($excecao->status());
        $this->setarHeader($excecao->header());
        if ($excecao->acao() == 'json') {
            header('Content-Type: application/json');
            return $this->retornoJson($retorno);
        }
        return $this->retornoHtml($retorno);
    }

    public function html()
    {
        return $this->retorno;
    }

    private function setarHeader(array $header): void
    {
        if (!$header) {
            return;
        }
        foreach ($header as $ind => $val) {
            header($ind . ': ' . $val);
        }
    }

    private function retornoJson(array $retorno): void
    {
        $this->retorno = json_encode($retorno, JSON_PARTIAL_OUTPUT_ON_ERROR);
    }

    private function retornoHtml(string $retorno): void
    {
        $this->retorno = $retorno;
    }
}
