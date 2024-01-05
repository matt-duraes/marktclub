<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Geral\Status;
use App\Classes\Geral\Target;
use App\Classes\SiteMenu\Tipo;

class SiteMenuTest extends Tests
{
    private string $idUnareg = '4cceef2a4ee3d677dd15955daace4bba';
    private string $idMenu = '';
    private string $idSubMenu = '';
    private string $novoTitulo = 'Novo título';

    public function __construct()
    {
        parent::__construct();
        $this
            ->tabela(TABELA_SITE_MENU)
            ->resetar();
    }

    public function listarTodosMenuTest(): self
    {
        $this->api('site_menu:listar');
        $this
            ->Curl
            ->get('/site-menu');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function pegarMenuUnaregTest(): self
    {
        $this
            ->api('site_menu:buscar')
            ->Curl
            ->json([
                'empresa' => $this->idUnareg
            ])
            ->get('/site-menu/' . $this->idUnareg);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceIgual('dado.lista.0.empresa', $this->idUnareg);
    }

    public function testarSalvarNovoMenuTest()
    {
        $dado = $this
            ->api('site_menu:salvar')
            ->Curl
            ->body($this->pegarMenu('/teste'))
            ->post('/site-menu')
            ->array();
        $this->idMenu = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function buscarMenuSalvoTest()
    {
        $this
            ->api('site_menu:buscar')
            ->Curl
            ->get('/site-menu/' . $this->idMenu);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->idMenu);
    }

    public function testarSalvarNovoSubMenuTest()
    {
        $dado = $this
            ->api('site_menu:salvar')
            ->Curl
            ->body($this->pegarSubMenu())
            ->post('/site-menu')
            ->array();
        $this->idSubMenu = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function buscarSubMenuSalvoTest()
    {
        $this
            ->api('site_menu:buscar')
            ->Curl
            ->get('/site-menu/' . $this->idSubMenu);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->idSubMenu);
    }

    public function atualizarTituloMenuTest()
    {
        $this
            ->api('site_menu:atualizar')
            ->Curl
            ->body(['titulo' => $this->novoTitulo])
            ->put('/site-menu/' . $this->idMenu);

        return $this->checkStatus(204);
    }

    public function verificarTituloMenuAtualizouTest()
    {
        $this
            ->api('site_menu:buscar')
            ->Curl
            ->get('/site-menu/' . $this->idSubMenu);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('dado.titulo', $this->novoTitulo);
    }

    public function atualizarTituloSubMenuTest()
    {
        $this
            ->api('site_menu:atualizar')
            ->Curl
            ->body(['titulo' => $this->novoTitulo])
            ->put('/site-menu/' . $this->idSubMenu);

        return $this->checkStatus(204);
    }

    public function verificarTituloSubMenuAtualizouTest()
    {
        $this
            ->api('site_menu:buscar')
            ->Curl
            ->get('/site-menu/' . $this->idSubMenu);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('dado.titulo', $this->novoTitulo);
    }

    public function deletaMenuSalvoTest()
    {
        $this
            ->api('site_menu:deletar')
            ->Curl
            ->delete('/site-menu/' . $this->idMenu);

        return $this->checkStatus(204);
    }

    public function verificaDeletouMenuTest()
    {
        $this
            ->api('site_menu:buscar')
            ->Curl
            ->get('/site-menu/' . $this->idMenu);

        return $this->checkStatus(404);
    }

    public function verificaDeletouSubMenuTest()
    {
        $this
            ->api('site_menu:buscar')
            ->Curl
            ->get('/site-menu/' . $this->idSubMenu);

        return $this->checkStatus(404);
    }

    private function pegarMenu(string $link)
    {
        return [
            'empresa' => $this->idUnareg,
            'tipo'    => Tipo::MENU,
            'titulo'  => 'Home',
            'link'    => $link,
            'target'  => Target::SELF,
            'ordem'   => rand(1, 10),
            'status'  => Status::ATIVO
        ];
    }

    private function pegarSubMenu()
    {
        return [
            'empresa' => $this->idUnareg,
            'menu'    => $this->idMenu,
            'tipo'    => Tipo::SUB_MENU,
            'titulo'  => 'Institucional',
            'ordem'   => rand(1, 10),
            'status'  => Status::ATIVO
        ];
    }
}
