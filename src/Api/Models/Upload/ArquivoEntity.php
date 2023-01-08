<?php

namespace ApiModel\Upload;

use ORM\Entity;
use Helpers\UploadHelper;
use App\Classes\UploadArquivo\Status;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class ArquivoEntity extends Entity
{

    protected string $_tabela = TABELA_UPLOAD_ARQUIVO;
    protected array $_buscar = [
        '!id_upload_grupo', 'arquivo', 'nome', 'extensao', 'tamanho', 'largura', 'altura', 'data_criacao'
    ];
    protected array $_insert = ['id_upload_grupo', 'id_usuario_equipe'];
    protected array $_salvar = ['arquivo', 'nome', 'extensao', 'tamanho', 'largura', 'altura', 'status'];
    protected array $_update = ['privado'];

    public int $id_upload_grupo;
    public int $id_usuario_equipe;
    public string $nome;
    public Status $status;
    public int $tamanho;
    public array $extensao;
    public int $altura;
    public int $largura;

    public function __construct(
        protected string|UploadHelper|UploadedFile $arquivo = '',
        protected ?string $grupo = null,
    ) {
        parent::__construct();
    }

    protected function regraInsert()
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($this->grupo);

        $this->id_upload_grupo = $Grupo->get('id');
        $this->id_usuario_equipe = sessao('USUARIO.id');

        $this->subirImagem($Grupo, md5(uniqid(time())));

        $this->nome = $this->arquivo->nomeReal();
        $this->status = new Status(1);
    }
    protected function regraPosInsert()
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($this->id_upload_grupo);

        $tamanho = arquivoTamanho(DIRETORIO_PRIVADO . '/' . $Grupo->diretorio . '/' . $this->arquivo);
        $this->dado(['tamanho' => $tamanho])->where(['uuid', $this->id])->update();
        $this->tamanho = $tamanho;
    }

    protected function regraPosDestruir()
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($this->prop('id_upload_grupo'));
        $path = DIRETORIO_PRIVADO . '/' . $Grupo->diretorio . '/' . $this->arquivo;

        if (file_exists($path)) {
            unlink($path);
        }
    }

    private function subirImagem($Grupo, $nome)
    {
        $diretorio = $Grupo->diretorio;
        $extensao = $Grupo->get('extensao');

        $this->arquivo = new UploadHelper(
            arquivo: $this->arquivo,
            diretorio: $diretorio,
            nome: $nome,
            nomeMaximo: 36,
            ext: $extensao,
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
}
