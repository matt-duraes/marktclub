<?php

namespace App\Models\Api\UsuarioCliente\Ativar;

use ORM\ORM;

final class CodigoModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLUBE_CODIGO;
    public int $idSubempresa = 0;

    public function __construct(
        int $idEmpresa,
        string $codigo
    ) {
        parent::__construct();

        $buscar = $this
            ->campo(['id_admin_subempresa'])
            ->where([
                [
                    ['id_admin_empresa', $idEmpresa],
                    ['codigo', strCaixaBaixa($codigo)],
                    ['status', 1]
                ]
            ])
            ->primeiro();
        if (empty($buscar)) {
            mensagemErro(
                'Código inválido!',
                'Seu código acesso está inválido, por favor, verifique o código informado e tente novamente.'
            );
        }
        $this->idSubempresa = $buscar->id_admin_subempresa;
    }
}
