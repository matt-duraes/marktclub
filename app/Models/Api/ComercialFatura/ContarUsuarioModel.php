<?php

namespace App\Models\Api\ComercialFatura;

use ORM\ORM;
use Modules\Botao;
use App\Classes\UsuarioCliente\Situacao;
use App\Classes\UsuarioCliente\TipoUsuario;

final class ContarUsuarioModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;
    public int $quantidade;

    public function __construct(
        int $empresa,
        Botao $aposentado
    ) {
        parent::__construct();
        $this->quantidade = $this->contar($this->pegarWhere($empresa, $aposentado));
    }

    private function pegarWhere(int $empresa, Botao $aposentado)
    {
        $where = [
            ['empresa', $empresa],
            ['status', 'in', [1, 2]],
            ['tipo', (new TipoUsuario(TipoUsuario::TITULAR))->numero()],
            [
                'OR',
                ['federacao', 'null'],
                ['federacao', '!=', 'FU']
            ]
        ];
        if (!$aposentado->bool()) {
            $where[] = [
                'OR',
                ['situacao', 'null'],
                ['situacao', '!=', (new Situacao(Situacao::APOSENTADO))->numero()]
            ];
        }
        return $where;
    }
}
