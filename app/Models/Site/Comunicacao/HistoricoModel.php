<?php

namespace App\Models\Site\Comunicacao;

use Modules\Botao;
use App\Helpers\ClubeApiHelper;
use App\Classes\ComunicacaoPublicidade\Tipo;

final class HistoricoModel extends ClubeApiHelper
{
    use LinkTrait;

    public function listarDados()
    {
        $dado = $this
            ->json([
                'tipo'       => Tipo::HISTORICO,
                'publicado'  => Botao::SIM,
                'pagina'     => 1,
                'quantidade' => 50
            ])
            ->get('/comunicacao-publicidade')
            ->object()->dado->lista ?? [];
        if (!$dado) {
            return [];
        }
        return $this->montarDado($dado);
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $idParceiro = $r->parceiro->id;
            if (!array_key_exists($idParceiro, $retorno)) {
                $retorno[$idParceiro] = [
                    'id'     => $idParceiro,
                    'titulo' => $r->parceiro->titulo,
                    'logo'   => $r->parceiro->logo,
                    'imagem' => []
                ];
            }
            $retorno[$idParceiro]['imagem'][] = [
                'id'     => $r->id,
                'link'   => $this->pegarLink($r->link, $r->parceiro->url, $r->parceiro->tipo),
                'imagem' => $r->imagem_desktop,
            ];
        }
        return array_values($retorno);
    }
}
