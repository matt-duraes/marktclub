<?php

namespace Painel\DemandaGeral\Models;

use Helpers\ApiHelper;
use PainelModel\Perfil\Equipe;
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
        $gerente = sessao('USUARIO.gerente') != 'nao' || sessao('USUARIO.admin') != 'nao' ? 'drag' : '';

        return [
            [
                'titulo' => 'Liberada',
                'classe' => $gerente,
                'status' => Status::LIBERADA
            ],
            [
                'titulo' => 'Bloqueada',
                'classe' => $gerente,
                'status' => Status::BLOQUEADA
            ],
            [
                'titulo' => 'Em andamento',
                'classe' => $gerente,
                'status' => Status::ANDAMENTO
            ],
            [
                'titulo' => 'Teste',
                'classe' => $gerente,
                'status' => Status::TESTE
            ],
            [
                'titulo' => 'Concluída',
                'classe' => $gerente,
                'status' => Status::CONCLUIDA
            ],
        ];
    }

    public function quadroConvenio()
    {
        $gerente = sessao('USUARIO.gerente', padrao: false) || sessao('USUARIO.admin', padrao: false) ? 'drag' : '';
        return [
            [
                'titulo' => 'Backlog',
                'classe' => $gerente,
                'add'    => true,
                'status' => Status::NOVA
            ],
            [
                'titulo' => 'Bloqueada',
                'classe' => $gerente,
                'status' => Status::BLOQUEADA
            ],
            [
                'titulo' => 'Liberada',
                'classe' => $gerente,
                'status' => Status::LIBERADA
            ],
            [
                'titulo' => 'Em andamento',
                'classe' => $gerente,
                'status' => Status::ANDAMENTO
            ],
            [
                'titulo' => 'Concluída',
                'classe' => $gerente,
                'status' => Status::CONCLUIDA
            ],
        ];
    }

    public function buscarDemanda($request)
    {
        $data_entrega_de = '';
        $data_entrega_ate = '';
        $sprint = 'nao';
        if ($request->area != 'tecnologia' && $request->status == 'concluida') {
            $data_entrega_de = dataRemover(hoje(), 15, 'dias');
            $data_entrega_ate = hoje();
        }
        if ($request->area == 'tecnologia') {
            $sprint = 'sim';
        }
        $dado = $this->Api
            ->json([
                'status'           => $request->status,
                'area'             => $request->area,
                'data_entrega_de'  => $data_entrega_de,
                'data_entrega_ate' => $data_entrega_ate,
                'sprint'           => $sprint,
                'ordem'            => 'ordem'
            ])
            ->get('/demanda-dado')
            ->object()->dado ?? [];

        return $this->montarDemanda($dado);
    }

    private function montarDemanda($dado): array
    {
        $retorno = [];
        $Perfil = new Equipe();
        foreach ($dado as $r) {
            $retorno[] = [
                'id'           => $r->id,
                'equipe'       => $Perfil->unico($r->equipe),
                'titulo'       => $r->titulo,
                'texto'        => $r->texto,
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
