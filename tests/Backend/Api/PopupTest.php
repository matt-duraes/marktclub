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
        $this->api('comunicacao_popup:salvar');
        $popup = $this
            ->Curl
            ->body([
                'titulo'         => 'venha conferir a melhor',
                'texto'          => 'Aqui vc tera o mejor do melhor sempre',
                'imagem'         => 'https://via.placeholder.com/500.png',
                'data_inicio'    => dataPassadaAleatorio(),
                'status'         => 'ativo'
            ])
            ->post('/comunicacao-popup')
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
        $this->api('comunicacao_popup:buscar');
        $this
            ->Curl
            ->get('/comunicacao-popup/' . $this->idPopup);

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
        $this->api('comunicacao_popup:atualizar');
        $this
            ->Curl
            ->body([
                'status'    => 'inativo'
            ])
            ->put('/comunicacao-popup/' . $this->idPopup);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return PopupTest
     * @throws Excecao
     */
    public function deletarPopupTest(): PopupTest
    {
        $this->api('comunicacao_popup:deletar');
        $this
            ->Curl
            ->delete('/comunicacao-popup/' . $this->idPopup);

        return $this
            ->checkStatus(204);
    }
}
