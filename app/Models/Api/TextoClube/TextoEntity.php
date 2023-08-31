<?php

namespace App\Models\Api\TextoClube;

use ORM\Entity;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Classes\TextoClube\Tipo;

final class TextoEntity extends Entity
{
    protected string $ormTabela = TABELA_TEXTO_CLUBE;
    protected array $ormSalvar = [
        'id_admin_empresa', 'titulo', 'texto', 'header_titulo', 'header_descricao', 'header_descricao',
        'tipo', 'ordem', 'status'
    ];
    protected array $ormBuscar = [
        'id_admin_empresa', 'titulo', 'texto', 'tipo', 'ordem', 'data_criacao', 'data_atualizacao',
        'header_titulo', 'header_descricao', 'header_tag', 'status'
    ];
    private OrmHelper $ormEmpresa;
    protected array $id_admin_empresa;
    public array $empresa;
    public string $titulo;
    public string $texto;
    public string $header_titulo;
    public string $header_descricao;
    public array $header_tag;
    public Tipo $tipo;
    public ?int $ordem;
    public Status $status;
    protected string $ormValidarSalvar = '
        titulo|Titulo|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';

    public function __construct()
    {
        $this->ormEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->empresa = $this->ormEmpresa->mudarListaIdParaUuid($this->id_admin_empresa);
    }

    protected function regraSalvar()
    {
        if (!$this->propriedadeExiste('empresa') || empty($this->empresa)) {
            mensagemErro('Campo obrigatorio!', 'O campo empresa é obrigatorio.');
        }
        $this->id_admin_empresa = $this->ormEmpresa->mudarListaUuidParaId($this->empresa);
    }
}
