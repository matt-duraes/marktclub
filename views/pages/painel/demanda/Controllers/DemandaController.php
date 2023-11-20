<?php

namespace Painel\Demanda\Controllers;

use Http\Request;
use Http\Response;
use Modules\Botao;
use Helpers\ApiHelper;
use Controller\Controller;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Tipo;
use App\Classes\DemandaTarefa\Status;
use Painel\Demanda\Models\CriarCotacaoAutomovelModel;
use Painel\Demanda\Models\ListaModel;
use Painel\Demanda\Models\CriacaoModel;
use Painel\Demanda\Models\DetalheModel;
use Painel\Demanda\Models\SorteioModel;
use Painel\Demanda\Models\CriarBugModel;
use Painel\Demanda\Models\CriarOutroModel;
use Painel\Demanda\Models\CriarBrindeModel;
use Painel\Demanda\Models\CriarEventoModel;
use Painel\Demanda\Models\CriarClienteModel;
use Painel\Demanda\Models\TarefaSalvarModel;
use Painel\Demanda\Models\CriarCampanhaModel;
use Painel\Demanda\Models\CriarAuditoriaModel;
use Painel\Demanda\Models\CriarIndicacaoModel;
use Painel\Demanda\Models\CriarAssociacaoModel;
use Painel\Demanda\Models\CriarCotacaoCarroModel;
use Painel\Demanda\Models\CriarAutoindicacaoModel;
use Painel\Demanda\Models\CriarCotacaoProdutoModel;
use App\Classes\DemandaTarefa\Tipo as DemandaTarefaTipo;

final class DemandaController extends Controller
{
    private ApiHelper $Api;

    public function __construct()
    {
        parent::__construct();
        $this->Api = new ApiHelper(token: true);
    }

    public function tecnologia()
    {
        $quadro = (new ListaModel())->quadroTi();
        return $this->listar('Demanda da Tecnologia', Area::TECNOLOGIA, $quadro);
    }

    public function criacao()
    {
        $quadro = (new ListaModel())->quadroCriacao();
        return $this->listar('Demanda da criação', Area::CRIACAO, $quadro);
    }

    public function convenio()
    {
        $quadro = (new ListaModel())->quadroConvenio();
        return $this->listar('Demanda do convênio', Area::CONVENIO, $quadro);
    }

    public function postListar(Request $request)
    {
        return mensagemSucesso((new ListaModel())->buscarDemanda($request->area, $request->status));
    }

    private function listar($titulo, $area, $quadro)
    {
        $empresa = $this->Api
            ->json(['titulo' => 'Escolha um cliente'])
            ->get('/comercial-empresa/select')
            ->array()['dado'] ?? [];
        $equipe = $this->Api
            ->json(['titulo' => 'Escolha um usuário'])
            ->get('/usuario-equipe/select')
            ->array()['dado'] ?? [];

        return view('painel.demanda.index', [
            'app'       => 'demanda-' . $area,
            'appTitulo' => $titulo,
            'area'      => $area,
            'quadro'    => $quadro,
            'empresa'   => $empresa,
            'equipe'    => $equipe,
            'tipoLista' => (new DemandaTarefaTipo())->select('Escolha uma opção'),
            'Tipo'      => new Tipo(),
            'Area'      => new DemandaTarefaTipo(),
        ]);
    }

    public function demanda(string $id)
    {
        return view('painel.demanda.demanda', [
            'r'      => (new DetalheModel($id))->montarDado(),
            'Tipo'   => new DemandaTarefaTipo(),
            'Status' => new Status()
        ]);
    }

    public function demandaSalvar(string $area)
    {
        $empresa = $this->Api
            ->json(['titulo' => 'Escolha um cliente'])
            ->get('/comercial-empresa/select')
            ->array();

        return view('painel.demanda.demanda-salvar', [
            'empresa' => $empresa['dado'] ?? [],
            'area'    => $area
        ]);
    }

    public function demandaEditar(string $id)
    {
        $demanda = $this->Api->get('/demanda-dado/' . $id)->object();
        return view('painel.demanda.demanda-editar', [
            'r'                 => $demanda->dado,
            'empresa'           => $empresa['dado'] ?? [],
            'equipe'            => $equipe['dado'] ?? [],
            'dataEntregaClasse' => $demanda->dado->com_prazo == 'sim' ? 'ativo' : ''
        ]);
    }

    public function postDemandaSeguir(Request $request)
    {
        $this
            ->Api
            ->validar('Erro ao seguir demanda, por favor, tente novamente.', login: true)
            ->post('/demanda-dado/seguir/' . $request->id);
        return new Response(status: 204);
    }

    public function postDemandaSeguirParar(Request $request)
    {
        $this
            ->Api
            ->validar('Erro ao parar de seguir demanda, por favor, tente novamente.', login: true)
            ->delete('/demanda-dado/seguir/' . $request->id);
        return new Response(status: 204);
    }

    public function postDemandaEditar(Request $request, string $id)
    {
        $request
            ->vazio('titulo', mensagem: 'Digite um título para continuar.')
            ->vazio('empresa', mensagem: 'Escolha uma empresa para continuar.')
            ->vazio('dono', mensagem: 'Escolha um dono da demanda para continuar.');

        $this->Api
            ->validar('Ocorre um erro ao editar sua demanda, por favor, tente novamente.')
            ->body([
                'titulo'            => $request->titulo,
                'id_admin_empresa'  => $request->empresa,
                'id_usuario_equipe' => $request->dono,
                'com_prazo'         => $request->com_prazo,
                'data_entrega'      => !empty($request->data_entrega) ? dataBanco($request->data_entrega) : '',
            ])
            ->put('/demanda-dado/' . $id);

        return new Response(status: 204);
    }

    public function postDemandaCancelar(Request $request, string $id)
    {
        $request->vazio('motivo', mensagem: 'O campo motivo é obrigatório!');

        $this->Api
            ->validar('Ocorreu um erro ao cancelar demanda.')
            ->body([
                'motivo' => $request->motivo
            ])
            ->post('/demanda-dado/cancelar/' . $id);

        return new Response(status: 204);
        ;
    }

    public function postDemandaLiberar(string $id)
    {
        $this->Api
            ->validar('Ocorreu um erro ao liberar demanda, por favor, tente novamente.')
            ->body(['status' => 'liberada'])
            ->put('/demanda-dado/' . $id);

        return new response(status: 204);
    }

    public function postDemandaOrdenar(Request $request)
    {
        $i = 1;
        foreach ($request->id as $id) {
            $this->Api
                ->validar(mensagem: 'Erro ao mudar a ordem, por favor, tente novamente.', login: true)
                ->body(['ordem' => $i])
                ->put('/demanda-dado/' . $id);
            $i++;
        }
        return new Response(status: 204);
    }

    public function postDemandaStatus(Request $request)
    {
        $this->Api
            ->validar(mensagem: 'Erro ao mudar o status, por favor, tente novamente.', login: true)
            ->body([
                'status' => $request->status
            ])
            ->put('/demanda-dado/' . $request->id);
        return new Response(status: 204);
    }

    public function postTarefaListar(string $demanda)
    {
        $Lista = new ListaModel();
        return mensagemSucesso($Lista->buscarTarefa($demanda));
    }

    public function tarefaSalvar(string $demanda)
    {
        $Tipo = new DemandaTarefaTipo();
        return view('painel.demanda.tarefa-salvar', [
            'tipoLista' => $Tipo->select('Escolha uma opção'),
            'demanda'   => $demanda
        ]);
    }

    public function tarefaEditar(string $id, string $demanda)
    {
        $tarefa = $this->Api->get('/demanda-tarefa/' . $id)->object();

        if (!object_key_exists('dado', $tarefa)) {
            mensagemStatus(404);
        }

        $Tipo = new DemandaTarefaTipo();

        return view('painel.demanda.tarefa-editar', [
            'demanda'   => $demanda,
            'tipoLista' => $Tipo->select('Escolha uma opção'),
            'r'         => $tarefa->dado
        ]);
    }

    public function postTarefaSalvar(Request $request)
    {
        $Tarefa = new TarefaSalvarModel(
            titulo: $request->titulo,
            texto: $request->getPost('texto', html: false),
            tipo: $request->tipo,
            demanda: $request->demanda
        );

        return mensagemSucesso($Tarefa->tarefa, 201);
    }

    public function postTarefaEditar(Request $request, string $id)
    {
        new TarefaSalvarModel(
            titulo: $request->titulo,
            texto: $request->getPost('texto', html: false),
            tipo: $request->tipo,
            id: $id
        );

        return new Response(status: 204);
    }

    public function postTarefaDeletar(string $id)
    {
        $this->Api
            ->validar(mensagem: 'Erro ao deletar tarefa, por favor, tente novamente.', login: true)
            ->delete('/demanda-tarefa/' . $id);

        return mensagemSucesso([
            'id' => uuid()
        ]);
    }

    public function postDemandaSalvar(Request $request)
    {
        if ($request->tipo == 'cliente') {
            $Demanda = new CriarClienteModel(
                empresaNome: $request->empresa_nome,
                empresa: $request->empresa,
                dominioTipo: $request->dominio_tipo,
                dominioLink: $request->dominio_link,
                loginApi: new Botao($request->login_api),
                loginLink: $request->login_link,
                app: new Botao($request->app),
                texto: $request->getPost('texto', html: false),
                cdn: new Botao($request->cdn)
            );
        } elseif (in_array($request->tipo, ['outro', 'feature'])) {
            $Demanda = new CriarOutroModel(
                empresaNome: $request->empresa_nome,
                titulo: $request->titulo,
                empresa: $request->empresa,
                tipo: $request->tipo
            );
        } elseif ($request->tipo == 'bug') {
            $Demanda = new CriarBugModel(
                empresaNome: $request->empresa_nome,
                titulo: $request->titulo,
                empresa: $request->empresa,
                critico: $request->critico,
                local: $request->local,
            );
        } elseif ($request->tipo == 'associacao') {
            $Demanda = new CriarAssociacaoModel(
                empresaNome: $request->empresa_nome,
                empresa: $request->empresa,
                texto: $request->getPost('texto', html: false)
            );
        } elseif ($request->tipo == Tipo::CRIACAO) {
            $Demanda = new CriacaoModel($request);
        } elseif ($request->tipo == Tipo::SORTEIO) {
            $Demanda = new SorteioModel($request);
        } elseif ($request->tipo == Tipo::EVENTO) {
            $Demanda = new CriarEventoModel($request);
        } elseif ($request->tipo == Tipo::BRINDE) {
            $Demanda = new CriarBrindeModel($request);
        } elseif ($request->tipo == Tipo::CAMPANHA) {
            $Demanda = new CriarCampanhaModel($request);
        } elseif ($request->tipo == Tipo::INDICACAO) {
            $Demanda = new CriarIndicacaoModel($request);
        } elseif ($request->tipo == Tipo::AUTOINDICACAO) {
            $Demanda = new CriarAutoindicacaoModel($request);
        } elseif ($request->tipo == Tipo::COTACAO_AUTOMOVEL) {
            $Demanda = new CriarCotacaoAutomovelModel($request);
        } elseif ($request->tipo == Tipo::COTACAO_PRODUTO) {
            $Demanda = new CriarCotacaoProdutoModel($request);
        } elseif ($request->tipo == Tipo::AUDITORIA) {
            $Demanda = new CriarAuditoriaModel($request);
        }

        return mensagemSucesso([
            'id'           => $Demanda->id(),
            'titulo'       => $request->empresa_nome . $request->titulo,
            'equipe'       => [
                'nome'   => sessao('USUARIO.nome'),
                'imagem' => sessao('USUARIO.imagem')
            ],
            'tipo'         => $request->tipo,
            'data_criacao' => agora(true),
            'data_entrega' => '',
            'status'       => 'nova'
        ], 201);
    }

    public function postTarefaArquivo(Request $request, string $id)
    {
        $this->Api
            ->validar('Ocorre um erro ao atualizar lista de arquivos, por favor, tente novamente.')
            ->body([
                'arquivo' => jsonEncode($request->arquivo)
            ])
            ->put('/demanda-dado/' . $id);

        return new Response(status: 204);
    }

    public function postTarefaLike(string $id)
    {
        $this->Api
            ->validar('Ocorreu um erro ao dar like na tarefa, por favor, tente novamente.')
            ->post('/demanda-tarefa/like/' . $id);

        return new Response(status: 204);
    }

    public function postTarefaDeslike(Request $request, string $id)
    {
        $request->vazio('motivo', mensagem: 'O campo motivo é obrigatório!');

        $this->Api
            ->validar('Ocorreu um erro ao dar like na tarefa, por favor, tente novamente.')
            ->body([
                'motivo' => $request->motivo
            ])
            ->post('/demanda-tarefa/deslike/' . $id);

        return new Response(status: 204);
    }

    public function getTrabalhoComecar(string $tarefa, string $demanda, string $area)
    {
        $mensagemErro = 'Erro ao começar a demanda, por favor, tente novamente.';
        $dado = $this->Api
            ->validar($mensagemErro)
            ->body(['tarefa' => $tarefa])
            ->post('/demanda-trabalho')
            ->object()->dado;

        sessao('TRABALHO', [
            'id'         => $dado->id,
            'tarefa'     => $tarefa,
            'demanda'    => $demanda,
            'iniciado'   => true,
            'minimizado' => false,
            'data'       => $dado->data_criacao,
            'tempo'      => $dado->tempo_trabalho,
            'total'      => $dado->tempo_total,
            'area'       => $area
        ]);

        return mensagemSucesso([
            'id'           => $dado->id,
            'tarefa'       => $tarefa,
            'data_criacao' => $dado->data_criacao,
            'tempo'        => $dado->tempo_trabalho,
            'total'        => $dado->tempo_total
        ]);
    }

    public function getTrabalhoMinimizar(string $acao)
    {
        sessao('TRABALHO.minimizado', $acao == 'sim');
    }

    public function getTrabalhoAtualizar(string $id)
    {
        $this->Api
            ->validar('Erro ao atualizar trabalho, por favor, tente novamente.')
            ->body(['acao' => 'atualizar'])
            ->put('/demanda-trabalho/' . $id);

        return new Response(status: 204);
    }

    public function getTrabalhoParar(string $id)
    {
        $this->Api
            ->validar('Erro ao parar trabalho, por favor, tente novamente.')
            ->body(['acao' => 'parar'])
            ->put('/demanda-trabalho/' . $id);

        sessaoDeletar('TRABALHO');

        return new Response(status: 204);
    }

    public function getTrabalhoConcluir(string $id)
    {
        $this->Api
            ->validar('Erro ao concluir trabalho, por favor, tente novamente.')
            ->body(['acao' => 'concluir'])
            ->put('/demanda-trabalho/' . $id);

        sessaoDeletar('TRABALHO');
        return new Response(status: 204);
    }
}
