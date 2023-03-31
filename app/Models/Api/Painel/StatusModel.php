<?php

namespace App\Models\Api\Painel;

use ORM\ORM;

final class StatusModel extends ORM
{
    protected string $ormTabela = TABELA_PAINEL_STATUS;

    private int $idEmpresa;
    public function __construct()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(404);
        }

        parent::__construct();
        $this->idEmpresa = TOKEN['empresa']->get('id');
    }
    public function listar($tabela, $campo)
    {
        $lista = $this->campo(['uuid', 'valor', 'nome', 'cor'])->where([
            ['tabela', $tabela],
            ['campo', $campo],
            ['status', 1],
            [
                'OR',
                ['id_admin_empresa', 'like', '%"' . $this->idEmpresa . '%"'],
                ['id_admin_empresa', 'null'],
                ['id_admin_empresa', '[]']
            ]
        ])->order('ordem', 'ASC')->read();

        return $this->montarRetorno($lista);
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'nome' => $r->nome,
                'valor' => $r->valor,
                'cor' => $r->cor
            ];
        }
        return $retorno;
    }
}
