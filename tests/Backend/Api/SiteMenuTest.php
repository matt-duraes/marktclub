<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Geral\Status;
use App\Classes\Geral\Target;
use App\Classes\SiteMenu\Tipo;

class SiteMenuTest extends Tests
{
    protected string $scope = 'site_menu';
    protected string $uri = '/site-menu';
    public string $automatico = 'crud';

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
