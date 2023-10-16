<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;
use App\Classes\DemandaDado\Status;

final class ListaModel
{
    use TarefaTrait;

    private ApiHelper $Api;

    public function __construct()
    {
        $this->Api = new ApiHelper(token: true);
    }

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
        $dado = $this->Api
            ->json([
                'status' => $status,
                'area'   => $area,
                'ordem'  => 'ordem'
            ])
            ->get('/demanda-dado')
            ->object()->dado ?? [];

        return $this->montarDemanda($dado);
    }

    private function montarDemanda($dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'           => $r->id,
                'titulo'       => $r->titulo,
                'data_criacao' => dataBr($r->data_criacao),
                'data_entrega' => dataBr($r->data_entrega),
                'status'       => $r->status
            ];
        }
        return $retorno;
    }

    public function buscarTarefa($demanda): array
    {
        $tarefa = $this->Api
            ->validar(mensagem: 'Erro ao listar a tarefa, por favor, tente novamente.', login: true)
            ->json([
                'demanda' => $demanda
            ])
            ->get('/demanda-tarefa')
            ->object()->dado ?? [];
        return $this->montarTarefa($tarefa);
    }
}
