<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;

final class ListaModel
{
    public function quadroCriacao()
    {
        return [
            [
                'titulo' => 'Backlog',
                'lista' => $this->buscarDemanda('nova', 'mais-novo', 'criacao')
            ],
            [
                'titulo' => 'Liberada',
                'lista' => $this->buscarDemanda('liberada', 'ordem', 'criacao')
            ],
            [
                'titulo' => 'Em andamento',
                'lista' => $this->buscarDemanda('andamento', 'mais-novo', 'criacao')
            ],
            [
                'titulo' => 'Aguardando aprovação',
                'lista' => $this->buscarDemanda('teste', 'mais-novo', 'criacao')
            ],
            [
                'titulo' => 'Concluída',
                'lista' => $this->buscarDemanda('concluida', 'mais-novo', 'criacao')
            ],
        ];
    }
    public function quadroTi()
    {
        return [
            [
                'titulo' => 'Backlog',
                'lista' => $this->buscarDemanda('nova', 'mais-novo', 'ti')
            ],
            [
                'titulo' => 'Liberada',
                'lista' => $this->buscarDemanda('liberada', 'ordem', 'ti')
            ],
            [
                'titulo' => 'Em andamento',
                'lista' => $this->buscarDemanda('andamento', 'mais-novo', 'ti')
            ],
            [
                'titulo' => 'Teste',
                'lista' => $this->buscarDemanda('teste', 'mais-novo', 'ti')
            ],
            [
                'titulo' => 'Concluída',
                'lista' => $this->buscarDemanda('concluida', 'mais-novo', 'ti')
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
