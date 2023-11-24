<?php

namespace Tests\Api;

use Tests\Token\Clube;

class BannerLoginTest extends Clube
{
    private string $id;

    public function __construct()
    {
        parent::__construct();
        $this->tabela(TABELA_COMUNICACAO_LOGIN)->resetar();
    }

    private function pegarBody(
        string|array $empresa = ['369fc307129e405b3f2f00620c7b012d']
    ) {
        return [
            'titulo'  => 'titulo',
            'arquivo_1'   => 'https://www.google.com',
            'arquivo_2'   => 'https://www.google.com',
            'arquivo_3'   => 'https://www.google.com',
            'data_fim' => dataFuturaAleatorio(),
            'data_inicio' => dataPassadaAleatorio(),
            'empresa' => $empresa,
        ];
    }

    public function listarBannersTest()
    {
        $this->api('comunicacao_login:listar');
        $this
            ->Curl
            ->json(['pagina' => 1])
            ->get('/comunicacao-login');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarBannerTest()
    {
        $this->api('comunicacao_login:salvar');
        $dado = $this
            ->Curl
            ->body($this->pegarBody())
            ->post('/comunicacao-login')
            ->array();

        $this->id = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function buscarEmpresaComBannerTest()
    {
        $this->api('comunicacao_login:buscar');
        $this
            ->Curl
            ->get('/comunicacao-login/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->id);
    }


    public function naoPodelSalvarDoisBannersMesmaEmpresaTest()
    {
        $this->api('comunicacao_login:salvar');
        $this
            ->Curl
            ->body($this->pegarBody())
            ->post('/comunicacao-login');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceExiste('erro.titulo')
            ->checkIndiceIgual('erro.titulo', 'Banner já cadastrado')
            ->checkIndiceIgual('erro.mensagem', 'Já existe um banner cadastrado para uma ou mais empresas informadas.');
    }

    public function naoPodeSalvarBannerSemEmpresaTest()
    {
        $this->api('comunicacao_login:salvar');
        $this
            ->Curl
            ->body($this->pegarBody(empresa: ''))
            ->post('/comunicacao-login');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceExiste('erro.titulo')
            ->checkIndiceIgual('erro.titulo', 'Campo obrigatório!')
            ->checkIndiceIgual('erro.mensagem', 'O campo empresa não pode ser vazio ou o formato está errado.');
    }

    public function naoPodelSalvarComEmpresaInvalidaTest()
    {
        $this->api('comunicacao_login:salvar');
        $this
            ->Curl
            ->body($this->pegarBody(empresa: ['empresa_invalida']))
            ->post('/comunicacao-login');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceExiste('erro.titulo')
            ->checkIndiceIgual('erro.titulo', 'Empresa inválida')
            ->checkIndiceIgual('erro.mensagem', 'Uma ou mais empresas informadas não são válidas.');
    }

    public function atualizarAdicionandoEmpresasDoBannerTest()
    {
        $this->api('comunicacao_login:atualizar');
        $this
            ->Curl
            ->body($this->pegarBody(empresa: ['14afa776394ada4be23be6acf7e3259e', '0ffc5c56b99f81ca0edea8bdf524b688']))
            ->put('/comunicacao-login/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function atualizarRemovendoEmpresasDoBannerTest()
    {
        $this->api('comunicacao_login:atualizar');
        $this
            ->Curl
            ->body($this->pegarBody())
            ->put('/comunicacao-login/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAtualizarBannerComEmpresaInvalidaTest()
    {
        $this->api('comunicacao_login:atualizar');
        $this
            ->Curl
            ->body($this->pegarBody(empresa: ['empresa_invalida']))
            ->put('/comunicacao-login/' . $this->id);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro');
    }

    public function deletarBannerTest()
    {
        $this->api('comunicacao_login:deletar');
        $this
            ->Curl
            ->delete('/comunicacao-login/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function validarSeApagouTest()
    {
        $this->api('comunicacao_login:buscar');
        $this
            ->Curl
            ->get('/comunicacao-login/' . $this->id);

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceExiste('erro.titulo')
            ->checkIndiceIgual('erro.titulo', 'Página não existe!')
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }
}
