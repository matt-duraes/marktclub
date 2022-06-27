<?php

namespace App\Models\Api\AdminEmpresa;

use ORM\Entity;
use Modules\Cnpj;

final class EmpresaEntity extends Entity
{
    protected string $_tabela = TABELA_EMPRESA_NOVO;

    protected array $_buscar = [
        'razao_social',
        'nome_fantasia',
        '!cnpj' => 'cnpj',
    ];

    protected function getId()
    {
        return $this->prop('id');
    }
}
