<?php

namespace App\Models\Api\ComercialRegra;

use ORM\Entity;
use App\Models\Api\ComercialEmpresa\HelperModel;

final class RegraEntity extends Entity
{
    protected string $ormTabela = TABELA_COMERCIAL_REGRA;
    protected array $ormBuscar = [
        'id_comercial_empresa', 'titulo', 'texto'
    ];
    protected array $ormSalvar = [
        'id_comercial_empresa', 'titulo', 'texto'
    ];
    protected string $ormValidar = '
        titulo|Título|vazio|obrigatorio
        texto|Texto|vazio|obrigatorio
        empresa|Empresa|vazio|obrigatorio
    ';
    public string $titulo;
    public string $texto;
    public array $empresa;
    public array $id_comercial_empresa;

    protected function regraSalvar()
    {
        $this->id_comercial_empresa = (new HelperModel())->mudarListaUuidParaId($this->empresa);
    }

    protected function regraPosBuscar()
    {
        $this->empresa = (new HelperModel())->mudarListaIdParaUuid($this->id_comercial_empresa);
    }
}
