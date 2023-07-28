<?php

namespace App\Models\Api\Publicidade;

use App\Classes\Publicidade\Tipo;
use App\Classes\StatusGeral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Helpers\UploadHelper;
use ORM\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PublicidadeEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $titulo;
    public UploadedFile|UploadHelper|string $imagem;
    public string $link;
    public string $target;
    public Tipo $tipo;
    public Status $status;
    protected string $ormTabela = TABELA_PUBLICIDADE;
    protected array $ormBuscar = [
        'titulo', 'imagem', 'target', 'link', 'tipo', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo', 'imagem', 'target', 'link', 'tipo', 'status'
    ];
    protected string $ormValidarSalvar = '
       titulo|Título|obrigatorio|vazio
       imagem|imagem|obrigatorio|vazio|valido
       target|Target|obrigatorio|vazio
       link|Link|obrigatorio|vazio
       tipo|Tipo|obrigatorio|vazio|valido
       status|Status|obrigatorio|vazio|valido
    ';
    protected ?int $idEmpresa;

    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }
}
