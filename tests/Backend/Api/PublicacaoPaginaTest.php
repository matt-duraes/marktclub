<?php

namespace Tests\Api;

use Tests\Token\Clube;

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
        $dado = $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/publicacao-pagina')
            ->array();

        $this->idPublicacao = $dado['dado']['lista'][0]['id'] ?? 'sem-id';

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceIgual('status', 'sucesso');
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
