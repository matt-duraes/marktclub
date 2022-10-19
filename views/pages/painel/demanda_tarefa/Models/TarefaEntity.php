<?php

namespace Painel\Demanda\Models;

use ORM\Entity;
use Erro\Excecao;
use Modules\Data;
use Modules\DataHora;
use Painel\UsuarioEquipe\Models\ListarModel as UsuarioModel;
use Painel\UsuarioEquipe\Models\UsuarioEquipeHelper as UsuarioHelper;

final class TarefaEntity extends Entity
{
    protected string $_tabela = TABELA_DEMANDA_TAREFA;
    protected array $_buscar = [
        'id_admin_empresa', 'id_usuario_dono', 'id_usuario_dev', 'seguindo_tarefa', 'titulo', 'texto',
        'prioridade', 'tipo', 'data_criacao', 'data_estimada', 'data_entrega', 'status'
    ];
    protected array $_update = [
        'seguindo_tarefa', 'status'
    ];

    protected DataHora $data_criacao;
    protected Data $data_estimada;
    protected Data $data_entrega;

    private array $donoDev;
    private array $usuarioSeguindo;
    private MensagemEntity $Mensagem;

    protected function regraPosBuscar()
    {
        $this->donoDev = [$this->id_usuario_dono];
        if ($this->id_usuario_dev > 0) {
            $this->donoDev[] = $this->id_usuario_dev;
        }
        $this->usuarioSeguindo = jsonDecode($this->seguindo_tarefa);
    }

    /**
     * Arquivar tarefa
     */
    public function arquivar()
    {
        $this->status = 1;
        $this->salvarNovaMensagem(
            'O usuário <strong>' . sessao('USUARIO.nome') . '</strong> arquivou essa tarefa.'
        );
    }

    /**
     * Pega a lista de mensagem da tarefa
     */
    public function pegarMensagem()
    {
        $id = $this->prop('id');
        $Mensagem = new MensagemModel;
        return $Mensagem->pegarMensagensDaTarefa($id);
    }

    /**
     * Pega a lista de arquivos da tarefa
     */
    public function pegarArquivo()
    {
        $id = $this->prop('id');
        $Arquivo = new ArquivoModel;
        return $Arquivo->pegarArquivosDaTarefa($id);
    }

    /**
     * Adiciona uma nova lista de seguidores
     *
     * @param array $seguidor   Lista de ID dos seguidores
     * @return void
     */
    public function adicionarListaSeguidores(array $seguidor)
    {
        $UsuarioModel = new UsuarioModel();
        $id = $UsuarioModel->pegarListaDeIdPeloUuid($seguidor, ['id']);
        if (is_numeric($this->id_usuario_dev) && $this->id_usuario_dev > 0) {
            $id[] = $this->id_usuario_dev;
        }
        $id[] = $this->id_usuario_dono;
        $this->seguindo_tarefa = $id;

        $this->salvarNovaMensagem(
            'O usuario <strong>' . sessao('USUARIO.nome') .
                '</strong> atualizou a lista de seguidores da tarefa.'
        );
    }

    /**
     * Começa a seguir ou sai da tarefa
     *
     * @param string $acao  Ação que deve ser realizada
     * @return void
     */
    public function seguirOuSairDaTarefa(string $acao): void
    {
        $id = sessao('USUARIO.id');
        $nome = sessao('USUARIO.nome');
        $seguindo = array_flip($this->usuarioSeguindo);

        if ($acao == 'sair' && !array_key_exists($id, $seguindo)) {
            throw new Excecao('Erro!', 'Você não segue essa tarefa.');
        } else if ($acao == 'sair' && array_key_exists($id, $seguindo)) {
            unset($seguindo[$id]);
            $mensagem = 'O usuário <strong>' . $nome . '</strong> deixou de seguir essa tarefa.';
        } else if ($acao == 'seguir' && array_key_exists($id, $seguindo)) {
            throw new Excecao('Erro!', 'Você já segue essa tarefa.');
        } else if ($acao == 'seguir' && !array_key_exists($id, $seguindo)) {
            $seguindo[$id] = true;
            $mensagem = 'O usuário <strong>' . $nome . '</strong> começou a seguir essa tarefa.';
        } else {
            throw new Excecao('Erro!', 'Ocorreu um erro na solicitação.');
        }

        $this->seguindo_tarefa = array_keys($seguindo);
        $this->salvarNovaMensagem($mensagem);
    }

    private function salvarNovaMensagem($mensagem)
    {
        try {
            $this->Mensagem = new MensagemEntity(
                tarefa: $this->prop('id'),
                texto: $mensagem
            );
            $this->Mensagem->salvar();
        } catch (\Throwable) {
            throw new Excecao('Erro!', 'Ocorreu um erro na solicitação.');
        }
    }

    /**
     * Pega mensagem nova
     */
    public function pegarMensagemNova()
    {
        return $this->Mensagem->texto;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADO
    |--------------------------------------------------------------------------
    */
    protected function getTipo()
    {
        $Helper = new Helper;
        return (object)[
            'nome' => $Helper->pegarNomeTipo($this->tipo),
            'icone' => $Helper->pegarIconeTipo($this->tipo),
            'cor' => $Helper->pegarCorTipo($this->tipo)
        ];
    }
    protected function getPrioridade()
    {
        $Helper = new Helper;
        return (object)[
            'nome' => $Helper->pegarNomePrioridade($this->prioridade),
            'icone' => $Helper->pegarIconePrioridade($this->prioridade),
            'cor' => $Helper->pegarCorPrioridade($this->prioridade)
        ];
    }

    protected function getStatus()
    {
        return (new Helper)->pegarNomeStatus($this->status);
    }

    protected function getCriado()
    {
        return explode(' ', $this->data_criacao->data())[0];
    }

    protected function getEntrega()
    {
        $estimado = $this->data_estimada->data();
        $entrega = $this->data_entrega->data();

        return (object)[
            'data' => empty($entrega) ? $estimado : $entrega,
            'estimado' => empty($entrega) && !empty($estimado),
        ];
    }

    protected function getId()
    {
        return $this->prop('id');
    }

    protected function getSeguir()
    {
        $meuId = sessao('USUARIO.id');
        if (in_array($meuId, $this->donoDev)) {
            return (object)['status' => false];
        }

        return (object)[
            'status' => true,
            'seguindo' => in_array($meuId, $this->usuarioSeguindo)
        ];
    }

    protected function getEquipe()
    {
        $campo = [
            'id', 'uuid', 'nome_real', 'imagem_tipo', 'imagem_arquivo', 'imagem_facebook', 'imagem_google'
        ];

        $Equipe = new UsuarioModel();

        $dev = [];
        if (!empty($this->id_usuario_dev)) {
            $dev = $Equipe->listarPelosIds([$this->id_usuario_dev], $campo);
        }

        $lista = $Equipe->listarTodosMenosIds($this->donoDev, $campo);
        $dono = $Equipe->listarPelosIds([$this->id_usuario_dono], $campo);

        return (object)[
            'dono' => $this->retornarNomeImagem($dono)[0] ?? [],
            'dev' => $this->retornarNomeImagem($dev)[0] ?? [],
            'lista' => $this->retornarNomeImagem($lista),
            'seguindo' => $this->pegarUuidDosSeguidores()
        ];
    }
    protected function getSeguindo()
    {
        $Equipe = new UsuarioModel();

        $lista =  $Equipe->listarPelosIds($this->usuarioSeguindo, [
            'uuid', 'nome_real', 'imagem_tipo', 'imagem_arquivo',
            'imagem_facebook', 'imagem_google'
        ]);
        return $this->retornarNomeImagem($lista);
    }

    private function retornarNomeImagem($dado)
    {
        if (!$dado) {
            return [];
        }

        $UsuarioHelper = new UsuarioHelper;
        $lista = [];
        foreach ($dado as $r) {
            $lista[] =  (object) [
                'id' => $r->uuid,
                'nome' => explode(' ', $r->nome_real)[0],
                'imagem' => $UsuarioHelper->pegarImagem($r->imagem_tipo, $r->imagem_arquivo, $r->imagem_google, $r->imagem_facebook)
            ];
        }
        return $lista;
    }

    private function pegarUuidDosSeguidores()
    {
        $dado = $this->getSeguindo();
        if (!$dado) {
            return [];
        }

        $lista = [];
        foreach ($dado as $r) {
            $lista[] = $r->id;
        }
        return $lista;
    }
}
