<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Tests;

class PopupTest extends Tests
{
    private string $idPopup;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return PopupTest
     * @throws Excecao
     */
    public function salvarPopupTest(): PopupTest
    {
        $this->api('popup:salvar');
        $popup = $this
            ->Curl
            ->body([
                'titulo'         => 'venha conferir a melhor',
                'subtitulo'      => 'opa mais e mais',
                'texto'          => 'Aqui vc tera o mejor do melhor sempre',
                'formulario'     => '[]',
                'imagem'         => 'https://via.placeholder.com/500.png',
                'data_expiracao' => '31/12/2012 12:12:12',
                'status'         => 'ativo'
            ])
            ->post('/popup')
            ->array();

        $this->idPopup = $popup['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    /**
     * @return PopupTest
     * @throws Excecao
     */
    public function buscarPopupTest(): PopupTest
    {
        $this->api('popup:buscar');
        $this
            ->Curl
            ->get('/popup/' . $this->idPopup);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idPopup);
    }

    /**
     * @return PopupTest
     * @throws Excecao
     */
    public function atualizarPopupTest(): PopupTest
    {
        $this->api('popup:atualizar');
        $this
            ->Curl
            ->body([
                'subtitulo' => 'opa mais e mais',
                'status'    => 'inativo'
            ])
            ->put('/popup/' . $this->idPopup);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idPopup);
    }

    /**
     * @return PopupTest
     * @throws Excecao
     */
    public function deletarPopupTest(): PopupTest
    {
        $this->api('popup:deletar');
        $this
            ->Curl
            ->delete('/popup/' . $this->idPopup);

        return $this
            ->checkStatus(204);
    }
}
