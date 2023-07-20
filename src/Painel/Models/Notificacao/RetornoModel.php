<?php

namespace PainelModel\Notificacao;

final class RetornoModel
{
    public function tratarRetorno($lista)
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = object([
                'id'          => $r->id,
                'mensagem'    => $r->titulo,
                'nome'        => $r->dono->nome,
                'imagem'      => $r->dono->imagem,
                'target'      => $r->target,
                'rel'         => $r->target == '_blank' ? 'rel="noopener noreferrer"' : '',
                'data_social' => dataSocial($r->data_criacao),
                'data_real'   => dataHoraBr($r->data_criacao),
                'status'      => $r->status
            ]);
        }
        return $retorno;
    }
}
