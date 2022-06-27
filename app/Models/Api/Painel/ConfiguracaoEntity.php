<?php

namespace App\Models\Api\Painel;

use ORM\Entity;

final class ConfiguracaoEntity extends Entity
{
    protected string $_tabela = TABELA_PAINEL_CONFIG;
    protected array $_buscar = ['permissao', 'configuracao', 'campo_obrigatorio', 'campo_permitido'];

    public array $permissao;
    public array $configuracao;
    public array $campo_obrigatorio;
    public array $campo_permitido;

    public function __construct()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(404);
        }

        parent::__construct();

        try {
            $this->buscar(['id_admin_empresa', TOKEN['empresa']->get('id')]);
        } catch (\Throwable) {
            $this->buscar(['id_admin_empresa', 1]);
        }
    }
}
