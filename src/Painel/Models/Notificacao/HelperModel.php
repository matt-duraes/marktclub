<?php

namespace PainelModel\Notificacao;

final class HelperModel
{

    public function tratarRetorno($lista)
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = [
                'id' => $r->id,
                'mensagem' => $r->titulo,
                'nome' => $r->dono->nome,
                'imagem' => $r->dono->imagem,
                'data_social' => dataSocial($r->data_criacao),
                'data_real' => dataHoraBr($r->data_criacao),
                'status' => $r->status
            ];
        }
        return $retorno;
    }
}
