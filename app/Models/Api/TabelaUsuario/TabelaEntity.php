<?php

namespace App\Models\Api\TabelaUsuario;

use ORM\Entity;
use App\Classes\Solicitacao\Status;
use App\Classes\TabelaUsuario\Tipo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Helpers\OrmHelper;
use Helpers\UploadHelper;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class TabelaEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SISTEMA_USUARIO;
    protected array $ormBuscar = [
        'id_admin_empresa',
        'id_usuario_equipe',
        'arquivo', 'erro', 'novo', 'obrigatorio',
        'atualizado', 'tipo', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'arquivo', 'tipo', 'status', 'obrigatorio',
        'id_admin_empresa'    => '->idEmpresa',
        'id_admin_subempresa' => '->idSubempresa',
        'id_usuario_equipe'   => '->idUsuario'
    ];
    protected string $ormValidarInsert = '
        arquivo|Arquivo|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio
    ';
    protected string $ormValidarUpdate = '
        status|Status|obrigatorio|vazio
    ';
    private int $idEmpresa;
    private ?int $idUsuario = null;
    private int $idSubempresa;
    protected int $id_admin_empresa;
    protected int $id_usuario_equipe;
    public array $obrigatorio;
    public string $arquivo;
    public int $erro;
    public int $novo;
    public int $atualizado;
    public Tipo $tipo;
    public Status $status;
    public array $empresa;
    public array $usuario;

    public function __construct(
        protected UploadedFile|UploadHelper|null $arquivoUpload = null
    ) {
        $this->validarEmpresa();
        $this->validarSubempresa();
        parent::__construct();
    }

    protected function regraInsert(): void
    {
        $this->pegarArquivo();
        $this->status = new Status(Status::NOVO);
    }

    private function pegarArquivo(): void
    {
        $this->arquivoUpload = (new UploadHelper(
            $this->arquivoUpload,
            diretorio: 'tabela_usuario',
            nome: md5(uniqid(rand(), true)),
            nomeForcar: true,
            mbMaximo: 10,
            path: DIRETORIO_PRIVADO
        ))->salvar();

        $this->arquivo = $this->arquivoUpload->nome();
    }

    protected function regraPosBuscar(): void
    {
        $this->buscarEmpresa();
        $this->buscarUsuario();
    }

    private function buscarEmpresa(): void
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))
            ->pegarPrimeiroRegistro([
                ['id', $this->id_admin_empresa]
            ], ['cod', 'nome_fantasia'], 'object');

        if (empty($empresa->cod)) {
            return;
        }

        $this->empresa = [
            'id'   => $empresa->cod,
            'nome' => $empresa->nome_fantasia
        ];
    }

    private function buscarUsuario(): void
    {
        $usuario = (new OrmHelper(TABELA_USUARIO_EQUIPE))
            ->pegarPrimeiroRegistro([
                ['id', $this->id_usuario_equipe]
            ], ['uuid', 'nome_real'], 'object');

        if (empty($usuario->uuid)) {
            return;
        }

        $this->usuario = [
            'id'     => $usuario->uuid,
            'nome'   => $usuario->nome_real,
        ];
    }
}
