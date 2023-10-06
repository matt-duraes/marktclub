<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;
use App\Classes\DemandaDado\Status;

final class ListaModel
{
    public function quadroCriacao()
    {
        return [
            [
                'titulo' => 'Backlog',
                'classe' => 'drag',
                'add'    => true,
                'status' => Status::NOVA
            ],
            [
                'titulo' => 'Liberada',
                'classe' => 'drag',
                'status' => Status::LIBERADA
            ],
            [
                'titulo' => 'Em andamento',
                'classe' => 'drag',
                'status' => Status::ANDAMENTO
            ],
            [
                'titulo' => 'Aguardando aprovação',
                'classe' => 'drag',
                'status' => Status::TESTE
            ],
            [
                'titulo' => 'Concluída',
                'classe' => 'drag',
                'status' => Status::CONCLUIDA
            ],
        ];
    }

    public function quadroTi()
    {
        return [
            [
                'titulo' => 'Backlog',
                'classe' => 'drag',
                'add'    => true,
                'status' => Status::NOVA
            ],
            [
                'titulo' => 'Liberada',
                'classe' => 'drag',
                'status' => Status::LIBERADA
            ],
            [
                'titulo' => 'Em andamento',
                'classe' => 'drag',
                'status' => Status::ANDAMENTO
            ],
            [
                'titulo' => 'Teste',
                'classe' => 'drag',
                'status' => Status::TESTE
            ],
            [
                'titulo' => 'Concluída',
                'classe' => '',
                'status' => Status::CONCLUIDA
            ],
        ];
    }

    public function buscarDemanda($area, $status)
    {
        return (new ApiHelper(token: true))
            ->json([
                'status' => $status,
                'area'   => $area,
                'ordem'  => 'ordem'
            ])
            ->get('/demanda-dado')
            ->object()->dado ?? [];
    }
}
