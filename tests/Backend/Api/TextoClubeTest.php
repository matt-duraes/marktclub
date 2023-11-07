<?php

namespace Tests\Api;

use Tests\Token\Clube;

class TextoClubeTest extends Clube
{
    private string $id;

    private function getBody()
    {
        return [
            'empresa'          => '["14afa776394ada4be23be6acf7e3259e"]',
            'titulo'           => 'Titulo de teste',
            'texto'            => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.',
            'header_titulo'    => 'header_titulo',
            'header_descricao' => 'header_descricao',
            'header_tag'       => 'header_tag',
            'tipo'             => 'faq',
            'status'           => 'ativo'
        ];
    }

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    public function listarTextoTest()
    {
        $this->api('texto_clube:listar');
        $this
            ->Curl
            ->json(['pagina' => 1])
            ->get('/texto-clube');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarTextoTest()
    {
        $this->api('texto_clube:salvar');
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/texto-clube')
            ->array();

        $this->id = $dado['dado']['id'];

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function buscarTextoTest()
    {
        $this->api('texto_clube:buscar');
        $this
            ->Curl
            ->get('/texto-clube/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->id);
    }

    public function atualizarTextoTest()
    {
        $this->api('texto_clube:atualizar');
        $this
            ->Curl
            ->body($this->getBody())
            ->put('/texto-clube/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function deletarTextoTest()
    {
        $this->api('texto_clube:deletar');
        $this
            ->Curl
            ->delete('/texto-clube/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function verificarSeApagou()
    {
        $this->api('texto_clube:buscar');
        $this
            ->Curl
            ->get('/texto-clube/' . $this->id);

        return $this
            ->checkStatus(404);
    }
}
