<?php

namespace App\Models\Api\Carteirinha;

use App\Classes\Carteirinha\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Helpers\UploadHelper;
use ORM\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class CarteirinhaEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $texto;
    public string $texto_perdido;
    public UploadedFile|UploadHelper|string $bg_frente;
    public UploadedFile|UploadHelper|string $bg_fundo;
    public Status $status;
    public array $empresa;
    protected string $ormTabela = TABELA_CARTEIRINHA;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormBuscar = [
        'id_admin_empresa', 'uuid', 'texto', 'texto_perdido', 'bg_frente',
        'bg_fundo', 'status', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormSalvar = [
        'texto', 'texto_perdido', 'bg_frente', 'bg_fundo', 'status'
    ];
    protected string $ormValidarInsert = '
        texto|Texto
        texto_perdido|Texto Perdido
        bg_frente|Imagem frente|obrigatorio|vazio|valido
        bg_fundo|Imagem verso|obrigatorio|vazio|valido
    ';
    protected string $ormValidarUpdate = '
        texto|Texto
        texto_perdido|Texto Perdido
        bg_frente|Imagem frente|valido
        bg_fundo|Imagem verso|valido
        status|Status|valido
    ';
    protected ?int $idEmpresa;
    protected int $id_admin_empresa;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->status = new Status(Status::INATIVO);
    }

    public function regraPosBuscar(): void
    {
        $this->obterEmpresa();
    }

    private function obterEmpresa(): void
    {
        $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarUltimoRegistro([
            'id', $this->id_admin_empresa
        ], ['cod', 'nome_fantasia'], 'object');

        if (empty($empresa)) {
            return;
        }

        $this->empresa = [
            'id'   => $empresa->cod,
            'nome' => $empresa->nome_fantasia
        ];
    }
}
