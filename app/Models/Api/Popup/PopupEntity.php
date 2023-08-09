<?php

namespace App\Models\Api\Popup;

use App\Classes\Popup\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Helpers\UploadHelper;
use Modules\DataHora;
use ORM\Entity;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class PopupEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $slug;
    public string $titulo;
    public string $subtitulo;
    public string $texto;
    public string $formulario;
    public UploadedFile|UploadHelper|string $imagem;
    public DataHora $data_expiracao;
    public Status $status;
    protected string $ormTabela = TABELA_POPUP;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormBuscar = [
        'slug', 'titulo', 'subtitulo', 'texto', 'formulario', 'imagem',
        'data_criacao', 'data_expiracao', 'status'
    ];
    protected array $ormSalvar = [
        'slug', 'titulo', 'subtitulo', 'texto', 'formulario', 'imagem',
        'data_expiracao', 'status'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        subtitulo|Subtítulo
        texto|Conteúdo|obrigatorio|vazio
        formulario|Formulário
        imagem|Imagem
        data_expiracao|Data de Expiração|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected ?int $idEmpresa;

    public function __construct()
    {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraInsert(): void
    {
        $this->slug = strSlug($this->titulo);
    }
}
