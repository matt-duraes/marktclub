<?php

namespace App\Models\Api\ComercialSubempresa;

use ORM\ORM;
use Http\Request;
use App\Classes\ComercialEmpresa\Helper;

final class SelectModel extends ORM
{
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;
    private int $idEmpresa;

    public function __construct(
        private ?Request $request = null
    ) {
        parent::__construct();
        $this->pegarEmpresa();
    }

    public function listarSelect(): array
    {
        return $this->pegarSelect('cod', 'nome_fantasia', $this->pegarWhere(), titulo: $this->request->titulo);
    }

    private function pegarWhere(): array
    {
        return [
            ['id_admin_empresa', $this->idEmpresa],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ];
    }

    private function pegarEmpresa()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(401, localhost: 'Token não foi encontrado no Model.');
        }
        $idEmpresa = TOKEN['empresa']->id;
        if ($idEmpresa == 1 && !$this->request->vazio('empresa')) {
            $idEmpresa = $this->where(['cod', $this->request->empresa])->primeiro('id');
        }

        if (empty($idEmpresa)) {
            mensagemErro(
                titulo: 'Não encontrado!',
                mensagem: 'Não foi encontrado nenhuma empresa pelo código enviado.',
                status: 404
            );
        }

        $this->idEmpresa = $idEmpresa;
    }
}
