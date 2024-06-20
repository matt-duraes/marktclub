<?php

namespace ApiModel\PainelHistorico;

use Erro\Erro;
use Throwable;
use ORM\Entity;
use Erro\Excecao;
use Helpers\DataHelper;
use Helpers\UploadHelper;
use System\Classes\PainelHistorico\Acao;
use System\Classes\PainelHistorico\Status;
use App\Models\Api\Trait\ValidarUsuarioTrait;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use ApiModel\PainelNotificacao\NotificacaoEntity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class HistoricoEntity extends Entity
{
    use ValidarUsuarioTrait;

    public int $id_usuario_equipe;
    public string $mensagem;
    public array $relacionado;
    public array $app;
    public Acao $acao;
    public Status $status;
    public string $notificar_titulo = '';
    public string $notificar_link = '';
    public array $notificar_equipe = [];
    public array $arquivo = [];
    private array $arquivoNome = [];
    protected string $ormTabela = TABELA_PAINEL_HISTORICO;
    protected array $ormInsert = [
        'id_relacionado' => '->relacionado',
        'id_usuario_equipe', 'app', 'acao', 'dado', 'arquivo'
    ];
    protected array $ormSalvar = ['mensagem', 'status'];
    protected array $ormBuscar = ['arquivo', 'mensagem', 'status', 'data_criacao'];
    protected string $ormValidarInsert = '
        relacionado|Relacionado|obrigatorio|vazio|isArray
        app|App|obrigatorio|vazio|isArray
        acao|Ação|obrigatorio|vazio
        dado|Dados|array
        status|Status|valido
    ';
    protected string $ormValidarUpdate = '
        mensagem|Mensagem|obrigatorio|vazio
        status|Status|valido
    ';
    private array $usuarioNotificado = [];

    public function __construct()
    {
        parent::__construct();
        $this->setarIdUsuario();
    }

    protected function regraInsert()
    {
        $this->id_usuario_equipe = $this->idUsuario;
        $this->status = new Status(empty($this->mensagem) ? Status::SEM_MENSAGEM : Status::COM_MENSAGEM);
    }

    protected function regraPosInsert()
    {
        if ($this->notificar_equipe) {
            $this->enviarNotificacaoParaUsuario(
                !empty($this->notificar_titulo) ? $this->notificar_titulo : 'Fez um comentário',
                $this->notificar_equipe,
                'uuid'
            );
        }

        preg_match_all("/@[a-z0-9\.]{1,}/", $this->mensagem, $usuario);
        if (array_key_exists(0, $usuario) && $usuario[0]) {
            $this->enviarNotificacaoParaUsuario('Marcou você em um comentário', $usuario[0], 'nome_perfil');
        }
    }

    /**
     * Adicionar arquivo
     *
     * @param UploadedFile $arquivo
     */
    public function arquivo(UploadedFile $arquivo): void
    {
        $nome = md5(uniqid(time()));
        $Imagem = new UploadHelper(
            arquivo: $arquivo,
            diretorio: 'historico',
            nome: $nome,
            ext: ['jpg', 'jpeg', 'jpg', 'fig'],
            path: DIRETORIO_PRIVADO
        );
        $Imagem->redimencionar(1000, 1000)->salvar();
        $this->arquivo[] = $nome . '.' . $Imagem->extensao();
    }

    protected function regraPosBuscar()
    {
        $this->arquivoNome = $this->arquivo;
        $arquivo = [];
        foreach ($this->arquivo as $nome) {
            $arquivo[] = arquivoPublico('historico', $nome, privado: true);
        }
        $this->arquivo = $arquivo;
    }

    /**
     * @throws Excecao
     */
    private function enviarNotificacaoParaUsuario(string $titulo, array $usuario, string $campo)
    {
        try {
            $Dono = new EquipeEntity(validarToken: false);
            $Dono->id($this->id_usuario_equipe);
        } catch (Throwable) {
            return;
        }
        foreach ($usuario as $valor) {
            $valor = str_replace('@', '', $valor);
            try {
                $Equipe = new EquipeEntity(validarToken: false);
                $Equipe->buscar([$campo, $valor]);
            } catch (Throwable) {
                continue;
            }

            $idEquipe = $Equipe->get('id');
            if (in_array($idEquipe, $this->usuarioNotificado) || $idEquipe == $this->id_usuario_equipe) {
                continue;
            }
            $this->usuarioNotificado[] = $idEquipe;

            try {
                $Notificacao = new NotificacaoEntity(
                    titulo: $titulo,
                    mensagem: nl2br($this->mensagem),
                    link: $this->notificar_link,
                    botao: 'Ver comentário',
                    Equipe: $Equipe,
                    Dono: $Dono,
                );
                $Notificacao->salvar();
            } catch (Throwable) {
                continue;
            }
        }
    }

    /**
     * @throws Excecao
     * @throws Erro
     */
    protected function regraUpdate()
    {
        if (!empty($this->prop('mensagem'))) {
            mensagemErro('Erro!', 'Já existe uma mensagem para esse histórico.');
        } elseif ($this->prop('status') == 1) {
            mensagemErro(
                'Erro!',
                'Não é mais possível atualizar esse histórico.',
                localhost: 'O status já está com valor 1'
            );
        }
        $this->status = new Status(1);
    }

    /**
     * @throws Excecao
     */
    protected function regraDestruir()
    {
        $data = (new DataHelper(agora()))->remover(1, 'minuto')->formato('Y-m-d H:i:s');
        if ($data > $this->data_criacao->date()) {
            mensagemErro('Erro!', 'Essa mensagem não pode mais ser deletada.');
        }
        foreach ($this->arquivoNome as $nome) {
            $path = DIRETORIO_PRIVADO . '/historico/' . $nome;
            if (file_exists($path)) {
                unlink($path);
            }
        }
    }
}
