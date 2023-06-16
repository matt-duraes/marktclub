<?php

namespace App\Models\Api\Popup;

use App\Classes\Popup\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\DataHora;
use ORM\Entity;

class PopupEntity extends Entity
{
    use ValidarEmpresaTrait;

    public string $slug;
    public string $titulo;
    public string $subtitulo;
    public string $texto;
    public string $formulario;
    public string $imagem;
    public DataHora $data_vencimento;
    public Status $status;
    protected string $ormTabela = TABELA_POPUP;
    protected array $ormBuscar = [
        'slug', 'titulo', 'subtitulo', 'data_criacao', 'status'
    ];
    protected array $ormSalvar = [
        'slug', 'titulo', 'subtitulo', 'texto', 'formulario',
        'imagem', 'status', 'data_vencimento'
    ];
    protected string $ormValidarSalvar = '
        slug|Slug|obrigatorio
        titulo|Título|obrigatorio
        subtitulo|Subtítulo|vazio
        texto|Conteúdo|obrigatorio
        formulario|Formulário|vazio
        imagem|Imagem|vazio
        data_vencimento|Data de Expiração|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
