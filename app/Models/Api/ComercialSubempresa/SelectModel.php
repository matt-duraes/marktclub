<?php

namespace App\Models\Api\ComercialSubempresa;

use App\Classes\ComercialEmpresa\Helper;
use Http\Request;
use ORM\ORM;

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

    public function listarSelect(): array
    {
        return $this->pegarSelect('cod', 'nome_fantasia', $this->pegarWhere(), titulo: $this->request->titulo);
    }

    private function pegarWhere(): array
    {
        if (!empty($this->request->todas) && $this->request->todas == 1) {
            return [
                ['id_admin_empresa', 'notnull'],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ];
        }
        return [
            ['id_admin_empresa', $this->idEmpresa],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ];
    }
}
