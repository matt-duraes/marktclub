<?php

namespace App\Models\Site\Comunicacao;

use App\Classes\ParceiroLoja\TipoLoja;

trait LinkTrait
{
    private function pegarLink($link, $url, $tipo)
    {
        if (!empty($link)) {
            $link = str_replace(['clube.marktclub.com.br'], [LINK], $link);
            return preg_match('/^http\:\/\/|https\:\/\//', $link) ? $link : 'https://' . $link;
        }
        $rota = [
            TipoLoja::AUTOMOVEL => route('automovel.detalhe'),
            TipoLoja::FARMACIA  => route('farmacia.detalhe'),
            TipoLoja::LOJA      => route('loja.detalhe'),
            TipoLoja::PREMIUM   => route('premium.detalhe'),
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
