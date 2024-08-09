<?php

namespace App\Models\Api\ComercialEmpresa;

use Helpers\OrmHelper;

final class HelperModel extends OrmHelper
{
    /**
     * @param boolean $livre Se vai buscar sem validar o TOKEN
     */
    public function __construct(bool $livre = false)
    {
        parent::__construct(TABELA_COMERCIAL_EMPRESA, $livre);
    }
}
