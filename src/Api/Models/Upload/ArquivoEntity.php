<?php

namespace ApiModel\Upload;

use App\Classes\StatusGeral\Status;
use App\Models\Api\UsuarioEquipe\PerfilModel;
use Erro\Erro;
use Erro\Excecao;
use Helpers\UploadHelper;
use ORM\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Throwable;

final class ArquivoEntity extends Entity
{
    public array $equipe;
    public int $id_upload_grupo;
    public int $id_usuario_equipe;
    public string $nome;
    public Status $status;
    public string $tamanho;
    public string $extensao;
    public int $altura;
    public int $largura;
    public ?string $privado;
    public string $link;
    protected string $ormTabela = TABELA_UPLOAD_ARQUIVO;
    protected array $ormBuscar = [
        'id_upload_grupo',
        'id_usuario_equipe',
        'arquivo',
        'nome',
        'extensao',
        'tamanho',
        'largura',
        'altura',
        'data_criacao',
        'privado'
    ];
    protected array $ormInsert = ['id_usuario_equipe'];
    protected array $ormSalvar = [
        'id_upload_grupo',
        'arquivo',
        'nome',
        'extensao',
        'tamanho',
        'largura',
        'altura',
        'status'
    ];
    protected array $ormUpdate = ['privado'];
    protected string $ormValidarInsert = '
        id_upload_grupo|Grupo|obrigatorio|vazio|int
        id_usuario_equipe|Equipe|obrigatorio|vazio|int
        nome|Nome|obrigatorio|vazio
    ';
    protected string $ormValidarSalvar = '
        status|Status|valido
    ';

    public function __construct(
        public string|UploadHelper|UploadedFile $arquivo = '',
        public ?GrupoEntity $Grupo = null,
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->equipe = (new PerfilModel())->pegarDado($this->id_usuario_equipe);
        $this->link = arquivoPrivado($this->id);
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar()
    {
        if ($this->Grupo instanceof GrupoEntity) {
            $this->id_upload_grupo = $this->Grupo->get('id');
        }
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert()
    {
        $this->id_usuario_equipe = TOKEN['usuario']->id;
        $this->subirImagem(md5(uniqid(time())));
        $this->nome = $this->arquivo->nomeReal();
        $this->status = new Status(Status::ATIVO);
    }

    /**
     * @throws Excecao
     */
    private function subirImagem($nome)
    {
        $diretorio = $this->Grupo->diretorio;
        $extensao = $this->Grupo->get('extensao');

        $this->arquivo = new UploadHelper(
            arquivo: $this->arquivo,
            diretorio: $diretorio,
            ext: $extensao,
            nome: $nome,
            nomeMaximo: 36,
            path: DIRETORIO_PRIVADO
        );

        $arquivoExtensao = $this->arquivo->extensao();
        if (in_array($arquivoExtensao, ['jpg', 'jpeg', 'png', 'gif'])) {
            $this->arquivo->redimencionar(1500);
            $this->largura = $this->arquivo->largura();
            $this->altura = $this->arquivo->altura();
        }
        $this->extensao = $arquivoExtensao;
        $this->tamanho = $this->arquivo->tamanho();
    }

    /**
     * @throws Excecao
     */
    protected function regraPosInsert()
    {
        $tamanho = arquivoTamanho(DIRETORIO_PRIVADO . '/' . $this->Grupo->diretorio . '/' . $this->arquivo);
        $this->dado(['tamanho' => $tamanho])->where(['arquivo', $this->arquivo])->update();
        $this->tamanho = $tamanho;
    }

    protected function regraPosDestruir()
    {
        try {
            $this->Grupo = new GrupoEntity();
            $this->Grupo->id($this->id_upload_grupo);
        } catch (Throwable) {
            return;
        }

        $path = DIRETORIO_PRIVADO . '/' . $this->Grupo->diretorio . '/' . $this->arquivo;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    /**
     * @throws Erro
     * @throws Excecao
     */
    protected function getArquivo()
    {
        return $this->prop('arquivo');
    }
}
