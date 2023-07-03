<?php

namespace App\Models\Api\Painel;

use ORM\Entity;

final class ConfiguracaoEntity extends Entity
{
    protected string $ormTabela = TABELA_PAINEL_CONFIG;
    protected array $ormBuscar = ['permissao', 'configuracao', 'campo_obrigatorio', 'upload_grupo', 'campo_permitido'];
    public array $permissao;
    public array $configuracao;
    public array $campo_obrigatorio;
    public array $campo_permitido;
    public array $upload_grupo;

    public function __construct()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(404);
        }

        parent::__construct();

        try {
            $this->buscar(['id_admin_empresa', TOKEN['empresa']->get('id')]);
        } catch (\Throwable) {
            $this->buscar(['id_admin_empresa', 0]);
        }
    }
}
