<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Geral\Status;
use App\Classes\Geral\Target;
use App\Classes\SiteMenu\Tipo;

class SiteMenuTest extends Tests
{
    private string $idUnareg = '4cceef2a4ee3d677dd15955daace4bba';
    protected string $scope = 'site_menu';
    protected string $uri = '/uri';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        $this
            ->tabela(TABELA_SITE_MENU)
            ->resetar();
        parent::__construct();
    }

    protected function pegarBody(string $link = null, string $empresa = null, string $menu = null)
    {
        return [
            'titulo'  => nomeAleatorio(),
            'empresa' => $empresa,
            'link'    => $link,
            'menu'    => $menu,
            'tipo'    => Tipo::MENU,
            'target'  => Target::SELF,
            'ordem'   => rand(1, 10),
            'status'  => Status::ATIVO
        ];
    }
}
