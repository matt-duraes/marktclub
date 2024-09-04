<?php

namespace App\Models\Api\View\Pagina;

use Helpers\OrmHelper;

final class PaginaHelper extends OrmHelper
{
    public function __construct()
    {
        parent::__construct(TABELA_VIEW_PAGINA);
    }
}
