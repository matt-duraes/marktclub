<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;
use App\Classes\DemandaDado\Area;

final class ListaModel
{
    public function quadroCriacao()
    {
        $area = Area::CRIACAO;
        return [
            [
                'titulo' => 'Backlog',
                'lista' => $this->buscarDemanda('nova', 'mais-novo', $area)
            ],
            [
                'titulo' => 'Liberada',
                'lista' => $this->buscarDemanda('liberada', 'ordem', $area)
            ],
            [
                'titulo' => 'Em andamento',
                'lista' => $this->buscarDemanda('andamento', 'mais-novo', $area)
            ],
            [
                'titulo' => 'Aguardando aprovação',
                'lista' => $this->buscarDemanda('teste', 'mais-novo', $area)
            ],
            [
                'titulo' => 'Concluída',
                'lista' => $this->buscarDemanda('concluida', 'mais-novo', $area)
            ],
        ];
    }
    public function quadroTi()
    {
        $area = Area::TECNOLOGIA;
        return [
            [
                'titulo' => 'Backlog',
                'lista' => $this->buscarDemanda('nova', 'mais-novo', $area)
            ],
            [
                'titulo' => 'Liberada',
                'lista' => $this->buscarDemanda('liberada', 'ordem', $area)
            ],
            [
                'titulo' => 'Em andamento',
                'lista' => $this->buscarDemanda('andamento', 'mais-novo', $area)
            ],
            [
                'titulo' => 'Teste',
                'lista' => $this->buscarDemanda('teste', 'mais-novo', $area)
            ],
            [
                'titulo' => 'Concluída',
                'lista' => $this->buscarDemanda('concluida', 'mais-novo', $area)
            ],
        ];
    }

    private function buscarDemanda($status, $ordem, $area)
    {
        return (new ApiHelper(token: true))
            ->json([
                'status' => $status,
                'area' => $area,
                'ordem' => $ordem
            ])
            ->get('/demanda-dado')
            ->object()->dado ?? [];
    }
}
