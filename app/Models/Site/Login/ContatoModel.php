<?php

namespace App\Models\Site\Login;

use Helpers\ApiHelper;

final class ContatoModel
{
    public function __construct(
        private string|array $parceiro
    ) {
    }

    public function buscarDados($tipo)
    {
        $dado = (new ApiHelper('contato:listar'))
            ->json([
                'vinculo' => $this->parceiro,
                'local'   => 'clube',
                'tipo'    => $tipo,
            ])
            ->get('/contato')
            ->array()['dado'] ?? [];
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {
        if (empty($dado)) {
            return [];
        }
        $retorno = [];
        foreach ($dado as $d) {
            $retorno[] = [
                'local'     => $d['local'],
                'tipo'      => $d['tipo'],
                'outro'     => $d['outro'],
                'nome'      => $d['nome'],
                'documento' => $d['documento'],
                'valor'     => $d['contato'] ? $d['contato'] . ' - ' . $d['valor'] : $d['valor'],
            ];
        }
        return $retorno;
    }
}
