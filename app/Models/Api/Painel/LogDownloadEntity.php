<?php

namespace App\Models\Api\Painel;

use ORM\Entity;

final class LogDownloadEntity extends Entity
{
    protected string $_tabela = TABELA_PAINEL_LOG_DOWNLOAD;
    protected array $_insert = ['id_admin_empresa', 'id_usuario_equipe', 'app', 'request', 'quantidade'];

    private int $idEmpresa;
    private int $idUsuario;

    public function __construct(
        protected string $app,
        protected array $request,
        protected int $quantidade
    ) {
        parent::__construct();

        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Painel\LogDownloadEntity');
        }

        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->idUsuario = TOKEN['usuario']->get('id');
        $this->_wherePadrao = ['id_admin_empresa', $this->idEmpresa];
    }

    protected function regraInsert()
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->id_usuario_equipe = $this->idUsuario;
    }
}
