<?php

namespace Tests\Api;

use App\Classes\ComercialPopup\Status;
use Erro\Excecao;
use Tests\Tests;

class ComercialPopupTest extends Tests
{
    private string $idPopup;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return ComercialPopupTest
     * @throws Excecao
     */
    public function salvarPopupTest(): ComercialPopupTest
    {
        $this->api('comercial_popup:salvar');
        $popup = $this
            ->Curl
            ->body([
                'titulo'      => 'venha conferir a melhor',
                'texto'       => 'Aqui vc tera o mejor do melhor sempre',
                'data_inicio' => date('d/m/Y'),
                'data_final'  => date('d/m/Y')
            ])
            ->post('/comercial-popup')
            ->array();

        $this->idPopup = $popup['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    /**
     * @return ComercialPopupTest
     * @throws Excecao
     */
    public function buscarPopupTest(): ComercialPopupTest
    {
        $this->api('comercial_popup:buscar');
        $this
            ->Curl
            ->get('/comercial-popup/' . $this->idPopup);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idPopup);
    }

    /**
     * @return ComercialPopupTest
     * @throws Excecao
     */
    public function atualizarPopupTest(): ComercialPopupTest
    {
        $this->api('comercial_popup:atualizar');
        $this
            ->Curl
            ->body([
                'status' => Status::EXPIRADO
            ])
            ->put('/comercial-popup/' . $this->idPopup);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return ComercialPopupTest
     * @throws Excecao
     */
    public function deletarPopupTest(): ComercialPopupTest
    {
        $this->api('comercial_popup:deletar');
        $this
            ->Curl
            ->delete('/comercial-popup/' . $this->idPopup);

        return $this
            ->checkStatus(204);
    }
}
