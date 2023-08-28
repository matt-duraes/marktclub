<?php

namespace Tests\Api;

use Tests\Api\Token\Clube;

class PublicacaoPaginaTest extends Clube
{
    private string $idPublicacao;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'titulo'           => 'Título da página',
            'texto'            => 'Texto da página',
            'header_titulo'    => '',
            'header_descricao' => '',
            'header_tag'       => [1, 2],
        ], $array);
    }

    public function listarPaginasTest(): PublicacaoPaginaTest
    {
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/publicacao-pagina');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function salvarNovaPaginaTest(): PublicacaoPaginaTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/publicacao-pagina')
            ->array();

        $this->idPublicacao = $dado['dado']['id'] ?? 'sem-id';

        return $this
               ->checkStatus(201)
               ->checkIndiceExiste('dado.id')
               ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeSalvarComTituloVazioTest(): PublicacaoPaginaTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'titulo' => ''
            ]))
            ->post('/publicacao-pagina');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro.titulo')
            ->checkIndiceIgual('erro.mensagem', 'O campo Título não pode ser vazio.')
            ->checkIndiceIgual('status', 'erro');
    }

    public function naoPodeSalvarComTextoVazioTest(): PublicacaoPaginaTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'texto' => ''
            ]))
            ->post('/publicacao-pagina');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro.mensagem')
            ->checkIndiceIgual('erro.mensagem', 'O campo Texto não pode ser vazio.')
            ->checkIndiceIgual('status', 'erro');
    }

    public function atualizarPaginaTest(): PublicacaoPaginaTest
    {
        $this
            ->Curl
            ->body($this->getBody())
            ->put('/publicacao-pagina/' . $this->idPublicacao);

        return $this
            ->checkStatus(204);
    }
}
