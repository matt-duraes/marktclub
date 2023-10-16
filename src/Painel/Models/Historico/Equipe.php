<?php

namespace PainelModel\Historico;

final class Equipe
{
    public function pegarListaEquipe()
    {
        $Api = new \Helpers\ApiHelper(token: true);
        $dado = $Api->get('/usuario-equipe/perfil')->object()->dado ?? [];
        return $this->montarDadoEquipe($dado);
    }

    private function montarDadoEquipe($lista)
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = object([
                'id'     => $r->id,
                'perfil' => $r->perfil,
                'nome'   => $r->nome,
                'imagem' => $r->imagem
            ]);
        }
        return $retorno;
    }
}
