<?php

namespace App\Models\Api\ComercialEmpresa;

use ORM\ORM;

final class PerfilModel extends ORM
{
    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    public function listarDados()
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'nome_fantasia'])
            ->where([
                ['id_admin_empresa', 'null'],
            ])
            ->read();
        return $this->montarDado($dado);
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'     => $r->uuid,
                'nome'   => !empty($r->titulo) ? $r->titulo : $r->nome_fantasia,
                'imagem' => ''
            ];
        }
        return $retorno;
    }
}
