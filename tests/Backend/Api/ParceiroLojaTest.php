<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\ParceiroLoja\Categoria;

class ParceiroLojaTest extends Tests
{
    protected string $idUltimo;
    protected string $scope = 'parceiro_loja';
    protected string $uri = '/parceiro-loja';
    public string $automatico = 'lb';

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
            ->get($this->uri . '/relacionado/' . $this->idUltimo);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado');
    }
}
