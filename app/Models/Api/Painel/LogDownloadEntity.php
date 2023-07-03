<?php

namespace App\Models\Api\Painel;

use ORM\Entity;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class LogDownloadEntity extends Entity
{
    protected string $ormTabela = TABELA_PAINEL_LOG_DOWNLOAD;
    protected array $ormInsert = ['id_admin_empresa', 'id_usuario_equipe', 'app', 'request', 'quantidade'];
    private int $idEmpresa;
    private int $idUsuario;
    protected int $id_admin_empresa;
    protected int $id_usuario_equipe;

    public function __construct(
        protected string $app,
        protected array $request,
        protected int $quantidade,
        protected ?string $usuario = null
    ) {
        parent::__construct();

        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Painel\LogDownloadEntity');
        }

        $this->idEmpresa = TOKEN['empresa']->get('id');
        $this->idUsuario = $this->pegarIdUsuario($usuario);

        $this->ormWherePadrao = ['id_admin_empresa', $this->idEmpresa];
    }

    protected function regraInsert()
    {
        $this->id_admin_empresa = $this->idEmpresa;
        $this->id_usuario_equipe = $this->idUsuario;
    }

    private function pegarIdUsuario(?string $usuario)
    {
        try {
            $Equipe = new EquipeEntity(validarToken: false);
            $Equipe->uuid($usuario);

            return $Equipe->get('id');
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Usuário não encontrado.', status: 404);
        }
    }
}
