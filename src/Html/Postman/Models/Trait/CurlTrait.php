<?php

namespace System\Html\Postman\Models\Trait;

trait CurlTrait
{
    private array $variavel = [];
    private string $link;

    protected function enviarCurl(
        string $metodo,
        string $uri,
        ?array $body = null,
        ?array $parametro = null,
        ?array $json = null,
        ?array $header = null
    ) {
        $variavel = $this->variavel;
        $link = str_replace(array_keys($variavel), array_values($variavel), str_replace('{{LINK}}', $this->link, $uri));

        if ($parametro) {
            $parametroFinal = [];
            foreach ($parametro as $ind => $val) {
                $parametroFinal[] = $ind . '=' . urlencode($val);
            }
            $parametroFinal = implode('&', $parametroFinal);
            $link .= str_contains($link, '?') ? '&' . $parametroFinal : '?' . $parametroFinal;
        }

        $requestBody = [];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        if ($body) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
            $requestBody = $body;
        } elseif ($json) {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, jsonEncode($json));
            $requestBody = $json;
        }
        if ($header) {
            $headerFinal = [];
            foreach ($header as $ind => $val) {
                $headerFinal[] = $ind . ': ' . $val;
            }
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headerFinal);
        }

        $retornoHeader = [];
        curl_setopt($ch, CURLOPT_HEADERFUNCTION, function ($ch, $valor) use (&$retornoHeader) {
            $matches = [];

            if (preg_match('/^([^:]+)\s*:\s*([^\x0D\x0A]*)\x0D?\x0A?$/', $valor, $matches)) {
                $ind = mb_strtolower($matches[1], 'UTF-8');
                $retornoHeader[$ind] = $matches[2];
            }

            return strlen($valor);
        });

        $retorno = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $erro = curl_error($ch);
        $info = curl_getinfo($ch);
        curl_close($ch);

        $this->requisicao = [
            'link'   => $link,
            'body'   => $requestBody,
            'header' => $header,
            'metodo' => $metodo,
            'tempo' => $info['total_time'] ?? 'Erro'
        ];

        return (object)[
            'retorno' => $retorno,
            'status'  => $status,
            'header' => $retornoHeader,
        ];
    }
}
