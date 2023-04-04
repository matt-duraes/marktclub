<?php

namespace App\Models\Api\Demanda\Trait;

use App\Models\Api\ComercialEmpresa\EmpresaEntity;

trait EmpresaTrait
{
    private function pegarEmpresa($id)
    {
        $Empresa = new EmpresaEntity();
        $Empresa->id($id);

        return [
            'id' => $Empresa->id,
            'nome' => $Empresa->nome_fantasia,
            'imagem' => $Empresa->imagem
        ];
    }

    private function pegarIdEmpresa()
    {
        try {
            $Empresa = new EmpresaEntity();
            $Empresa->uuid($this->empresa);
            $this->id_admin_empresa = $Empresa->get('id');
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Não foi encontrado nenhuma empresa pelo id enviado.', status: 404);
        }
    }
}
