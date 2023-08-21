<?php

namespace App\Models\Site\Comunicacao;

use App\Classes\ParceiroLoja\Tipo;

trait LinkTrait
{
    private function pegarLink($link, $url, $tipo)
    {
        if (!empty($link)) {
            $link = str_replace(['clube.marktclub.com.br'], [LINK], $link);
            return preg_match('/^http\:\/\/|https\:\/\//', $link) ? $link : 'https://' . $link;
        }
        $rota = [
            Tipo::AUTOMOVEL => route('automovel.detalhe'),
            Tipo::FARMACIA  => route('farmacia.detalhe'),
            Tipo::LOJA      => route('loja.detalhe'),
            Tipo::PREMIUM   => route('premium.detalhe'),
        ];
        if (!array_key_exists($tipo, $rota)) {
            return '';
        };
        return $rota[$tipo] . '/' . $url;
    }

    private function pegarTarget($link)
    {
        $link = strCaixaBaixa(explode('/', preg_replace('/^http\:\/\/|htttps\:\/\/(www\.){0,1}/', '', $link))[0]);
        $comparacao = strCaixaBaixa(explode('/', preg_replace('/^http\:\/\/|htttps\:\/\/(www\.){0,1}/', '', LINK))[0]);
        if ($link == $comparacao) {
            return '_self';
        }
        return '_blank';
    }
}
