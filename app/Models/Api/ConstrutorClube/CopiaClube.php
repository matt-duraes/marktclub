<?php

namespace App\Models\Api\ConstrutorClube;

use ORM\ORM;

class CopiaClube extends ORM
{
    protected string $ormTabela = '';
    private int $idEmpresaPadrao = 6;

    public function __construct(
        private int $idEmpresa,
    ) {
        parent::__construct();
        $this->inserirEmpresa(TABELA_PARCEIRO_LOJA);
        $this->inserirEmpresa(TABELA_PARCEIRO_CASHBACK);
        $this->inserirEmpresa(TABELA_PARCEIRO_EASYLIVE);
    }

    private function inserirEmpresa(string $tabela, string $colunaEmpresa = 'id_admin_empresa')
    {
        $this->ormTabela = $tabela;
        $campos = $this
            ->tabela($tabela)
            ->campo(['id', $colunaEmpresa])
            ->where(
                [
                    'OR',
                    [$colunaEmpresa, 'json', $this->idEmpresaPadrao],
                    [$colunaEmpresa, 'json', sprintf('["%s"]', $this->idEmpresaPadrao)]
                ]
            )
            ->read();

        if (!$campos) {
            return;
        }

        foreach ($campos as $campo) {
            $empresa = json_decode($campo->{$colunaEmpresa});

            if (in_array($this->idEmpresa, $empresa)) {
                return;
            }

            $empresa[] = $this->idEmpresa;
            $empresaUnico = array_unique($empresa);

            $this
                ->dado([$colunaEmpresa => json_encode($empresaUnico)])
                ->where(['id', $campo->id])
                ->update();
        }
    }
}
