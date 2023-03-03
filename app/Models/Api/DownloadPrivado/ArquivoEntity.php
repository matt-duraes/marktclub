<?php

namespace App\Models\Api\DownloadPrivado;

use ORM\Entity;
use Modules\Botao;
use Helpers\ExcelHelper;
use App\Classes\DownloadPrivado\Status;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use ApiModel\PainelNotificacao\NotificacaoEntity;

final class ArquivoEntity extends Entity
{
    protected string $_tabela = TABELA_SISTEMA_DOWNLOAD;
    protected array $_insert = ['id_admin_empresa', 'id_usuario_equipe', 'arquivo'];
    protected array $_update = ['status'];
    protected array $_buscar = ['id_usuario_equipe', 'arquivo', 'status'];

    protected int $id_admin_empresa;
    protected int $id_usuario_equipe;
    public string $arquivo;
    public string $link;
    public Status $status;

    private EquipeEntity $Equipe;
    public array $dono;
    public Botao $vencido;

    public function __construct(
        private ?array $dado = null,
        private ?string $usuario = null
    ) {
        parent::__construct();
    }

    public function regraPosBuscar()
    {
        $Perfil = new PerfilModel();
        $this->dono = $Perfil->pegarDado($this->id_usuario_equipe);
        $vencido = dataBanco($this->data_criacao) == hoje() ? 'sim' : 'nao';
        $this->vencido = new Botao($vencido);
        $this->link = arquivoPublico('download', $this->arquivo);
    }

    /*
    |--------------------------------------------------------------------------
    | SALVAR
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->gerarArquivo();
        $this->pegarUsuario();
        $this->id_admin_empresa = TOKEN['empresa']->get('id');
        $this->id_usuario_equipe = $this->Equipe->get('id');
        $this->status = new Status(1);
    }

    protected function regraPosInsert()
    {
        $this->enviarNotificacaoParaUsuario();
    }

    private function gerarArquivo()
    {
        if (empty($this->dado)) {
            return;
        }
        $Excel = new ExcelHelper(
            border: true,
            path: DIRETORIO_PUBLICO . '/download'
        );

        $Excel->titulo(array_keys($this->dado[0]));
        foreach ($this->dado as $linha) {
            $Excel->linha($linha);
        }

        $this->arquivo = md5(uniqid(time())) . '.xlsx';
        $Excel->salvar($this->arquivo);
    }

    private function pegarUsuario(): int
    {
        $this->Equipe = new EquipeEntity(validarToken: false);
        $this->Equipe->id($this->usuario);
        return $this->Equipe->get('id');
    }

    private function enviarNotificacaoParaUsuario()
    {
        $Noticicacao = new NotificacaoEntity(
            titulo: 'Seu arquivo ficou pronto para download',
            mensagem: 'O download do seu arquivo ficou pronto, acesse o painel e verifique sua notificações para fazer o download',
            link: '{{LINK}}/download-privado/' . $this->id,
            target: '_blank',
            botao: 'Abrir Painel',
            Equipe: $this->Equipe,
            Dono: $this->Equipe
        );
        $Noticicacao->salvar();
    }
}
