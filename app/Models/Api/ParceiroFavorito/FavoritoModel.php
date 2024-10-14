<?php

namespace App\Models\Api\ParceiroFavorito;

use ORM\ORM;

class FavoritoModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_FAVORITO;

    public function listarDado(): array
    {
        if (!defined('TOKEN') || !array_key_exists('usuario', TOKEN) || !object_key_exists('id', TOKEN['usuario'])) {
            return [];
        }
        $dado = $this
            ->campo(['uuid'])
            ->where(['id_usuario_cliente', TOKEN['usuario']->id])
            ->tabela(TABELA_PARCEIRO_LOJA)
            ->join('id', 'id_parceiro_loja')
            ->campo(['titulo', 'uuid', 'url'], 'parceiro')
            ->where(['status', 4])
            ->read();

        return $this->montarRetorno($dado);
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = (object)[
                'id'       => $r->uuid,
                'parceiro' => $r->parceiro_uuid,
                'titulo'   => $r->parceiro_titulo,
                'url'      => $r->parceiro_url
            ];
        }
        return $retorno;
    }
}
