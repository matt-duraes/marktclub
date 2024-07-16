<?php

namespace App\Models\Api\ComunicacaoLogin;

use Helpers\OrmHelper;

final class BannerClubeEntity extends BannerEntity
{
    public function __construct(string $empresa)
    {
        parent::__construct();
        $id = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($empresa);
        try {
            $this->buscar([
                ['data_inicio', '<=', hoje()],
                ['data_fim', '>=', hoje()],
                ['status', 1],
                ['id_admin_empresa', 'json', $id]
            ]);
        } catch (\Throwable) {
            $this->buscar(['padrao', 1]);
        }
    }
}
