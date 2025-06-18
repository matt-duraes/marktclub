<?php

namespace App\Models\Api\Saude\Convenio;

use App\Classes\Geral\Status;
use Helpers\OrmHelper;
use Modules\ArquivoPrivado;
use ORM\Entity;

final class PlanoEntity extends Entity
{
    public string         $titulo;
    public ArquivoPrivado $arquivo_imagem;
    public string         $url;
    public Status         $status;
    public array          $empresa;
    public array          $endereco_estado;
    protected array       $id_admin_empresa;
    protected string      $ormTabela = TABELA_SAUDE_CONVENIO;
    protected array       $ormSalvar = [
        'titulo', 'arquivo_imagem', 'url', 'status', 'id_admin_empresa', 'endereco_estado',
    ];
    protected array       $ormBuscar = [
        'titulo', 'arquivo_imagem', 'url', 'status', 'id_admin_empresa', 'endereco_estado',
    ];
    private OrmHelper     $OrmHelper;

    public function __construct()
    {
        parent::__construct();
        $this->OrmHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
    }

    protected function regraSalvar(): void
    {
        $this->id_admin_empresa = $this->OrmHelper->mudarListaUuidParaId($this->empresa);
    }

    protected function regraPosBuscar(): void
    {
        $this->empresa = $this->OrmHelper->mudarListaIdParaUuid($this->id_admin_empresa);
    }
}
