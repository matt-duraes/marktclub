<?php

namespace Tests\Api;

use Erro\Excecao;
use Tests\Tests;

class ComunicacaoPopupTest extends Tests
{
    private string $idPopup;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return ComunicacaoPopupTest
     * @throws Excecao
     */
    public function salvarPopupTest(): ComunicacaoPopupTest
    {
        $this->api('comunicacao_popup:salvar');
        $popup = $this
            ->Curl
            ->body([
                'titulo'      => 'venha conferir a melhor',
                'texto'       => 'Aqui vc tera o mejor do melhor sempre',
                'data_inicio' => '12/12/2012',
                'data_final'  => '31/12/2012',
                'status'      => 'ativo'
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
     * @return ComunicacaoPopupTest
     * @throws Excecao
     */
    public function buscarPopupTest(): ComunicacaoPopupTest
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
     * @return ComunicacaoPopupTest
     * @throws Excecao
     */
    public function atualizarPopupTest(): ComunicacaoPopupTest
    {
        $this->api('comunicacao_popup:atualizar');
        $this
            ->Curl
            ->body([
                'titulo' => 'opa mais e mais',
                'status' => 'inativo'
            ])
            ->put('/comunicacao-popup/' . $this->idPopup);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return ComunicacaoPopupTest
     * @throws Excecao
     */
    public function deletarPopupTest(): ComunicacaoPopupTest
    {
        $this->api('comunicacao_popup:deletar');
        $this
            ->Curl
            ->delete('/comunicacao-popup/' . $this->idPopup);

        return $this
            ->checkStatus(204);
    }
}
