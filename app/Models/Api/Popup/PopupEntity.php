<?php

namespace App\Models\Api\Popup;

use App\Classes\Popup\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Modules\DataHora;
use ORM\Entity;

class PopupEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_POPUP;

    protected array $ormBuscar = [
        'slug', 'titulo', 'subtitulo', 'texto', 'formulario', 'imagem',
        'data_criacao', 'data_atualizacao', 'data_vencimento', 'status'
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

    protected string $slug;
    protected string $titulo;
    protected string $subtitulo;
    protected string $texto;
    protected string $formulario;
    protected string $imagem;
    protected DataHora $data_vencimento;
    protected Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
    }
}
