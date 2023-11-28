<?php

namespace Tests\Api;

use Tests\Token\Clube;
use App\Classes\ParceiroLoja\Categoria;

class ParceiroLojaTest extends Clube
{
    private string $uri = '/parceiro-loja';
    private string $id;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    public function __destruct()
    {
        $this->tabela(TABELA_ANALYTICS_LOJA_VENDA)->resetar();
    }

    public function selectLojaTest()
    {
        $this->api('parceiro_loja:listar');
        $this
            ->Curl
            ->get($this->uri . '/select');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado');
    }

    public function listarLojaTest()
    {
        $this->api('parceiro_loja:listar');
        $dado = $this
            ->Curl
            ->json(['pagina' => 1])
            ->get($this->uri)
            ->array();

        $this->id = $dado['dado']['lista'][0]['id'] ?? 'sem-id';

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado.lista');
    }

    public function buscarLojaTest()
    {
        $this->api('parceiro_loja:buscar');
        $this
            ->Curl
            ->get($this->uri . '/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->id)
            ->checkNaoVazio('dado');
    }

    public function listarDestaqueVazioTest()
    {
        $this->api('parceiro_loja:destaque');
        $this
            ->Curl
            ->json([
                'categoria'    => '',
                'subcategoria' => '',
                'quantidade'   => 10
            ])
            ->get($this->uri . '/destaque');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado');
    }

    public function listarDestaquePorCategoriaTest()
    {
        $this->api('parceiro_loja:destaque');
        $this
            ->Curl
            ->json([
                'categoria'    => valorAleatorio(array_keys((new Categoria())->select())),
                'subcategoria' => '',
                'quantidade'   => 10
            ])
            ->get($this->uri . '/destaque');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado');
    }

    public function listarRelacionadoTest()
    {
        $this->api('parceiro_loja:relacionado');
        $this
            ->Curl
            ->get($this->uri . '/relacionado/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado');
    }
}
