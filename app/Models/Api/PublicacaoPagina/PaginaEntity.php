<?php

namespace App\Models\Api\PublicacaoPagina;

use ORM\Entity;
use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class PaginaEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PUBLICACAO_PAGINA;
    protected array $ormInsert = [
        'id_admin_empresa'  => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo', 'texto', 'header_titulo', 'header_descricao', 'header_tag', 'status'
    ];
    protected array $ormBuscar = [
        'titulo', 'texto', 'header_titulo', 'header_descricao', 'header_tag', 'status', 'url'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    public string $titulo;
    public string $texto;
    public string $url;
    public Status $status;
    public string $header_titulo;
    public string $header_descricao;
    public array $header_tag;
    private int $idEmpresa;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa();
        $this->ormWherePadrao = ['id_admin_empresa', $this->idEmpresa];
    }
}
