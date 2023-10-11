<?php

namespace Painel\Demanda\Models;

use Helpers\ApiHelper;
use App\Classes\DemandaDado\Status;
use App\Classes\DemandaTarefa\Status as DemandaTarefaStatus;
use App\Classes\DemandaTarefa\Tipo;
use PainelModel\Perfil\Equipe;

final class ListaModel
{
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
        return $this->Api
            ->json([
                'status' => $status,
                'area'   => $area,
                'ordem'  => 'ordem'
            ])
            ->get('/demanda-dado')
            ->object()->dado ?? [];
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

    private function montarTarefa($tarefa): array
    {
        $retorno = [];
        $Status = new DemandaTarefaStatus();
        $Tipo = new Tipo();
        $Equipe = new Equipe();
        foreach ($tarefa as $r) {
            $retorno[] = (object)[
                'id'          => $r->id,
                'equipe'      => $Equipe->unico($r->equipe),
                'dono'        => $r->equipe == sessao('USUARIO.id'),
                'titulo'      => $r->titulo,
                'texto'       => $r->texto,
                'tipo'        => $Tipo->nome($r->tipo),
                'data_inicio' => dataBr($r->data_producao_inicio),
                'data_final'  => dataBr($r->data_producao_final),
                'status'      => $Status->nome($r->status)
            ];
        }
        return $retorno;
    }
}
