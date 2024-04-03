<?php

namespace PainelModel\Data;

use stdClass;
use Helpers\ApiHelper;
use PainelModel\Perfil\Equipe;

final class MontarListaModel
{
    public stdClass $retorno;

    public function __construct(
        private string $local_principal,
        private string $vinculo,
        private int $pagina
    ) {
        $this->buscar();
    }

    private function buscar()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar('Ocorreu um erro ao listar linha do tempo, por favor, tente novamente.')
            ->json([
                'local_principal' => $this->local_principal,
                'vinculo'         => $this->vinculo,
                'pagina'          => $this->pagina
            ])
            ->get('/data')
            ->object();
        $dado->dado->lista = $this->montarRetorno($dado->dado->lista);
        $this->retorno = $dado->dado;
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        $Perfil = new Equipe();
        foreach ($lista as $r) {
            $retorno[] = (object)[
                'id'       => $r->id,
                'equipe'   => $Perfil->unico($r->equipe),
                'mensagem' => $r->mensagem,
                'data'     => dataHoraBr($r->data)
            ];
        }
        return $retorno;
    }
}
