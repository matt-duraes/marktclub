<?php

namespace App\Models\Api\BannerLogin;

use App\Classes\Geral\Status;
use Helpers\OrmHelper;
use ORM\Entity;

class BannerEntity extends Entity
{
    protected string $ormTabela = TABELA_BANNER_LOGIN;
    public string|null $uuid = null;
    public string $id;
    public array $id_admin_empresa;
    public string $url_1;
    public string $url_2;
    public string $url_3;
    public Status $status;
    public array $empresa;
    protected array $ormBuscar = [
        'url_1', 'url_2', 'url_3', 'status'
    ];
    protected array $ormSalvar = [
        'url_1', 'url_2', 'url_3', 'status', 'id_admin_empresa'
    ];

    public function regraSalvar(): void
    {
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $this->id_admin_empresa = $ormHelper->mudarListaUuidParaId($this->empresa);
        $this->uuid = $this->id ?? null;

        $this->validarSeJaExiste();
    }

    private function validarSeJaExiste()
    {
        $ormHelper = new OrmHelper(TABELA_BANNER_LOGIN);
        $dado = $ormHelper
            ->where([
                ['id_admin_empresa', 'json', json_encode($this->id_admin_empresa)],
                ['status', 1]
            ])
            ->read();

        if (!empty($dado) && $dado[0]->uuid !== $this->uuid) {
            return mensagemErro(
                'Banner já cadastrado',
                'Já existe um banner cadastrado para esta empresa',
                400
            );
        }
    }
}
