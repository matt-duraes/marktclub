<?php

namespace System\System;

use Erro\Excecao;
use Helpers\CryptHelper;
use Http\Request as RequestPsr7;
use Route\Config as RouteConfig;

final class Request
{
    private Request|RequestPsr7 $request;
    public function __construct(
        private RouteConfig $route,
    ) {
        $criptografia = $route->rotaUso()['criptografia'] ?? [];
        $this->request = new RequestPsr7(
            $criptografia['lista'] ?? [],
            $criptografia['chave'] ?? null
        );
        $this->validarRequest();
    }

    public function request(): RequestPsr7
    {
        return $this->request;
    }

    private function validarRequest(): void
    {
        $rota = $this->route->rotaUso();
        if ($rota['metodo'] == 'VIEW') {
            return;
        }

        $get = $rota['request']['get'] ?? [];
        $post = $rota['request']['post'] ?? [];
        $files = $rota['request']['files'] ?? [];
        $put = $rota['request']['put'] ?? [];
        $json = $rota['request']['json'] ?? [];

        $request = $this->request;

        if ($request->metodo() != 'PUT') {
            $this->verificaRequestEstaOk($request->getGet(), $get);
        }

        $this->verificaRequestEstaOk($request->getJson(), $json);
        $this->verificaRequestEstaOk($request->getPost(), $post);
        $this->verificaRequestEstaOk($request->getFiles(), $files);

        if ($request->metodo() == 'PUT') {
            $this->verificaRequestEstaOk($request->getPut(), $put);
        }
    }

    private function verificaRequestEstaOk(array $request, string|array $lista): void
    {
        if (empty($request) && empty($lista)) {
            return;
        }

        if (
            array_key_exists('form_system_captcha', $request) &&
            !$this->validarCaptcha($request['form_system_captcha'])
        ) {
            throw new Excecao(
                titulo: 'Erro no Captcha!',
                mensagem: 'Não foi possível validar seu captcha, por favor, recarregue a página e tente novamente.',
                lista: [
                    'captcha' => false
                ]
            );
        } elseif (
            array_key_exists('form_system_hash', $request) &&
            !$this->validarHash(
                hash: $request['form_system_hash'],
                validacao: $request['form_system_validacao'] ?? false
            )
        ) {
            throw new Excecao(
                titulo: 'Erro!',
                mensagem:
                    'Não foi possível validar os dados enviados, por favor, recarregue a página e tente novamente.',
                lista: ['validacao' => false]
            );
        }

        if ($lista == '*') {
            return;
        }

        if (in_array('hash_validacao_captcha', $lista)) {
            $lista[] = 'form_system_captcha';
            $lista[] = 'form_system_hash';
            $lista[] = 'form_system_validacao';
            $lista = array_flip($lista);
            unset($lista['hash_validacao_captcha']);
            $lista = array_keys($lista);
        } elseif (in_array('hash_validacao', $lista)) {
            $lista[] = 'form_system_hash';
            $lista[] = 'form_system_validacao';
            $lista = array_flip($lista);
            unset($lista['hash_validacao']);
            $lista = array_keys($lista);
        }

        if (empty($lista) && !empty($request)) {
            $this->montarRetorno($lista, $request);
        } elseif (empty($request) && empty($lista)) {
            return;
        }

        $indiceRequest = array_keys($request);
        $indiceLista = array_values($lista);

        $arrayIndiceListaLimpo = [];
        foreach ($indiceLista as $val) {
            if (substr($val, 0, 1) != '!' && !in_array($val, $indiceRequest)) {
                $this->montarRetorno($lista, $request);
            }
            if (substr($val, 0, 1) == '!') {
                $arrayIndiceListaLimpo[] = substr($val, 1);
            } else {
                $arrayIndiceListaLimpo[] = $val;
            }
        }

        foreach ($indiceRequest as $val) {
            if (!in_array($val, $arrayIndiceListaLimpo)) {
                $this->montarRetorno($lista, $request);
            }
        }

        return;
    }

    private function validarCaptcha(string $captcha): bool
    {
        if (SISTEMA == 'LOCALHOST') {
            return true;
        }
        if (empty($captcha)) {
            return false;
        }
        $content = [
            'secret' => env('RECAPTCHA_SECRET'),
            'response' => $captcha,
            'remoteip' => ip(),
        ];

        $curl = curl_init('https://www.google.com/recaptcha/api/siteverify');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        $validation = curl_exec($curl);
        curl_close($curl);

        $response = jsonDecode($validation);

        if (
            is_object($response) &&
            isset($response->success, $response->score) &&
            $response->success &&
            $response->score >= env('RECAPTCHA_SCORE')
        ) {
            return true;
        }
        return false;
    }

    private function validarHash(string $hash, bool | string $validacao = ''): bool
    {
        $hash = explode('.', $hash);
        if (count($hash) != 2 || $hash[0] != md5(ip()) || is_bool($validacao) || !empty($validacao)) {
            return false;
        }

        $hash = (new CryptHelper())->decode($hash[1]);
        if (!isset($hash['indice'], $hash['hash'], $hash['data'])) {
            return false;
        }
        $session = sessao('FORMHASH_' . $hash['indice'], padrao: false);
        if (empty($session) || $session == $hash['hash']) {
            return true;
        }
        return false;
    }

    private function montarRetorno($lista, $request): void
    {
        $falta = array_diff($lista, array_keys($request));
        $aMais = [];

        $listaFlip = array_flip($lista);

        if ($request) {
            foreach (array_keys($request) as $ind) {
                if (!array_key_exists($ind, $listaFlip) && !array_key_exists('!' . $ind, $listaFlip)) {
                    $aMais[] = $ind;
                }
            }
        }
        if ($falta) {
            foreach ($falta as $ind => $val) {
                if (substr($val, 0, 1) == '!') {
                    unset($falta[$ind]);
                }
            }
        }
        if (($falta && $aMais) || (count($falta) > 1 || count($aMais) > 1)) {
            $mensagem = 'Erro nos parâmetros enviado.';
        } else {
            $mensagem = 'Erro no parâmetro enviado.';
        }

        if ($falta && count($falta) > 1) {
            $mensagem .= " Falta os parametros: '" . implode("', '", $falta) . "'.";
        } elseif ($falta) {
            $mensagem .= " Falta o parametro: '" . implode("', '", $falta) . "'.";
        }
        if ($aMais && count($aMais) > 1) {
            $mensagem .= " Foi enviado parametros a mais: '" . implode("', '", $aMais) . "'.";
        } elseif ($aMais) {
            $mensagem .= " Foi enviado um parametro a mais: '" . implode("', '", $aMais) . "'.";
        }

        if (SISTEMA == 'producao') {
            throw new Excecao(
                titulo: 'Erro no envio!',
                mensagem: 'Ocorreu um erro no envio dos dados, por favor, tente novamente.'
            );
        }

        throw new Excecao(
            titulo: 'Requisição ruim!',
            mensagem: $mensagem
        );
    }
}
