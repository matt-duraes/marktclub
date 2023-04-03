<?php

namespace App\Models\Api\Rotina;

use ORM\ORM;

final class SalvarModel extends ORM
{
    public function __construct(string $tabela)
    {
        $this->ormTabela = $tabela;
        parent::__construct();
    }

    public function salvar($data, $campo, $dado)
    {
        foreach ($dado as $indice) {
            if ($this->naoPodeSalvar($data, $campo, $indice[$campo], $indice['id_admin_empresa'])) {
                continue;
            }
            $this->dado($indice)->insert();
        }
    }

    public function salvarDia($data, $dado)
    {
        if ($this->naoPodeSalvar($data, 'id_admin_empresa', $dado['id_admin_empresa'], $dado['id_admin_empresa'])) {
            return;
        }
        $this->dado($dado)->insert();
    }

    public function salvarSemValidar($dado)
    {
        $this->dado($dado)->insert();
    }

    private function naoPodeSalvar($data, $campo, $valor, $idEmpresa)
    {
        return $this->existe([
            [$campo, $valor],
            ['data_acesso', $data],
            ['id_admin_empresa', $idEmpresa]
        ]);
    }
}
