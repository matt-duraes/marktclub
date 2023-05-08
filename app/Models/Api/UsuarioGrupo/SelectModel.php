<?php

namespace App\Models\Api\UsuarioGrupo;

use ORM\ORM;
use Http\Request;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class SelectModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_GRUPO;
    private int $idEmpresa;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
    }

    public function listarDados(): array
    {
        return $this->pegarSelect(
            indice: 'indice',
            valor: 'titulo',
            where: $this->pegarWhere(),
            titulo: $this->request->titulo
        );
    }

    private function pegarWhere(): array
    {
        $idEmpresa = $this->pegarEmpresa();
        return [
            ['status', 1],
            ['id_admin_empresa', $idEmpresa]
        ];
    }
    private function pegarEmpresa()
    {
        $id = TOKEN['empresa']->get('id');
        if ($this->request->vazio('empresa') || $id != 1) {
            return $id;
        }

        try {
            $Empresa = new EmpresaEntity();
            $Empresa->uuid($this->request->empresa);
            return $Empresa->get('id');
        } catch (\Throwable) {
            return $id;
        }
    }
}
