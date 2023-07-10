<?php

namespace App\Models\Api\Painel;

use ORM\ORM;

final class MenuModel extends ORM
{
    protected string $ormTabela = TABELA_PAINEL_MENU;

    public const TIPO = [
        1 => 'titulo',
        2 => 'dropdown',
        3 => 'menu'
    ];

    public function __construct()
    {
        if (!defined('TOKEN')) {
            mensagemStatus(404);
        }

        parent::__construct();
    }

    public function listarDados(): array
    {
        $lista = $this
            ->campo(['uuid', 'tipo', 'titulo', 'menu', 'url', 'icone', 'permissao'])
            ->where([
                ['status', 1],
                ['id_admin_empresa', 1]
            ])
            ->order('ordem', 'ASC')
            ->read();

        return $this->montarDados($lista);
    }

    private function montarDados($lista): array
    {
        $retorno = [];
        foreach ($lista as $r) {
            $dado = [
                'id'        => $r->uuid,
                'titulo'    => strNull($r->titulo),
                'tipo'      => self::TIPO[$r->tipo] ?? '',
                'permissao' => jsonDecode($r->permissao, true, true)
            ];
            if (in_array($r->tipo, [2, 3])) {
                $dado['icone'] = $r->icone;
                $dado['menu'] = jsonDecode($r->menu, true, true);
            }
            if ($r->tipo == 3) {
                $dado['url'] = $r->url;
            }
            $retorno[] = $dado;
        }
        return $retorno;
    }
}
