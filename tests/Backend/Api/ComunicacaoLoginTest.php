<?php

namespace Tests\Api;

use Tests\Tests;

class ComunicacaoLoginTest extends Tests
{
    protected string $idUltimo;
    protected string $scope = 'comunicacao_login';
    protected string $uri = '/comunicacao-login';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        parent::__construct();
        $this->tabela(TABELA_COMUNICACAO_LOGIN)->resetar();
    }

    protected function pegarBody(
        string|array $empresa = ['369fc307129e405b3f2f00620c7b012d']
    ) {
        return [
            'titulo'      => 'titulo',
            'arquivo_1'   => 'https://www.google.com',
            'arquivo_2'   => 'https://www.google.com',
            'arquivo_3'   => 'https://www.google.com',
            'data_fim'    => dataFuturaAleatorio(),
            'data_inicio' => dataPassadaAleatorio(),
            'empresa'     => $empresa,
        ];
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
            ->body($this->pegarBody(empresa: ['9954c5edcc9a7b72fed65715f326df81', '14afa776394ada4be23be6acf7e3259f']))
            ->put('/comunicacao-login/' . $this->idUltimo);

        return $this
            ->checkStatus(204);
    }

    public function atualizarRemovendoEmpresasDoBannerTest()
    {
        $this->api('comunicacao_login:atualizar');
        $this
            ->Curl
            ->body($this->pegarBody())
            ->put('/comunicacao-login/' . $this->idUltimo);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAtualizarBannerComEmpresaInvalidaTest()
    {
        $this->api('comunicacao_login:atualizar');
        $this
            ->Curl
            ->body($this->pegarBody(empresa: ['empresa_invalida']))
            ->put('/comunicacao-login/' . $this->idUltimo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro');
    }
}
