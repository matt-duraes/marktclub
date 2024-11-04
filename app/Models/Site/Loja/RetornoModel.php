<?php

namespace App\Models\Site\Loja;

use Helpers\ListaHelper;
use App\Classes\ParceiroLoja\TipoLoja;

final class RetornoModel
{
    public array $retorno = [];
    public array $mapa = [];

    public function __construct(array $dado)
    {
        $retorno = [];
        $dataNovo = dataRemover(hoje(), 1, 'mes');
        $listaEstado = (new ListaHelper())->estado()->r();
        $Favorito = new FavoritoModel();
        foreach ($dado as $r) {
            $link = route('loja.detalhe');
            if ($r->tipo_loja == TipoLoja::FARMACIA) {
                $link = route('farmacia.detalhe');
            } elseif ($r->tipo_loja == TipoLoja::AUTOMOVEL) {
                $link = route('automovel.modelo');
            } elseif ($r->tipo_loja == TipoLoja::CASHBACK) {
                $link = route('cashback.detalhe');
            } elseif ($r->tipo_loja == TipoLoja::PREMIUM) {
                $link = route('premium.detalhe');
            }
            $link = $link . '/' . $r->url;

            $estadoArray = jsonDecode($r->endereco_estado, true, true);
            $estadoNumero = count($estadoArray);
            $estado = '';
            if (in_array('GR', $estadoArray)) {
                $estado = 'Internacional';
            } elseif ($estadoNumero == 27) {
                $estado = 'Nacional';
            } elseif ($estadoNumero == 1 && array_key_exists($estadoArray[0], $listaEstado)) {
                $estado = $listaEstado[$estadoArray[0]];
            } elseif ($estadoNumero > 1) {
                $estado = $estadoNumero . ' estados';
            }
            $dado = [
                'id'       => $r->id,
                'titulo'   => $r->titulo,
                'link'     => $link,
                'imagem'   => $r->imagem_logo,
                'desconto' => $r->desconto,
                'favorito' => $Favorito->favorito($r->id),
                'novo'     => !empty($r->data_publicacao) && $r->data_publicacao > $dataNovo ? 'sim' : 'nao',
                'estado'   => $estado,
                'tipo'     => $r->tipo_loja,
            ];

            foreach ($r->geolocalizacao ?? [] as $mapa) {
                $this->mapa[] = (object)array_merge($dado, [
                    'latitude'  => $mapa->latitude,
                    'longitude' => $mapa->longitude
                ]);
            }
            $retorno[] = (object)$dado;
        }
        $this->retorno = $retorno;
    }
}
