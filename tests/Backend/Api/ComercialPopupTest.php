<?php

namespace Tests\Api;

use App\Classes\ComercialPopup\Status;
use Erro\Excecao;
use Tests\Tests;

class ComercialPopupTest extends Tests
{
    private array $idEmpresa = ['14afa776394ada4be23be6acf7e3259e'];
    private string $idPopup;

    public function __construct()
    {
        $this->tabela(TABELA_COMERCIAL_POPUP)->resetar();
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
                'empresa'     => $this->idEmpresa,
                'titulo'      => 'venha conferir a melhor',
                'texto'       => 'Aqui vc tera o mejor do melhor sempre',
                'data_inicio' => date('d/m/Y'),
                'data_final'  => date('d/m/Y'),
                'status'      => Status::ATIVO
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
    public function verificaSeFoiAtualizadoPopupTest(): ComercialPopupTest
    {
        $this->api('comercial_popup:buscar');
        $this
            ->Curl
            ->get('/comercial-popup/' . $this->idPopup);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idPopup)
            ->checkIndiceIgual('dado.status', Status::EXPIRADO);
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

    /**
     * @return ComercialPopupTest
     * @throws Excecao
     */
    public function verificaSeFoiDeletadoPopupTest(): ComercialPopupTest
    {
        $this->api('comercial_popup:buscar');
        $this
            ->Curl
            ->get('/comercial-popup/' . $this->idPopup);

        return $this
            ->checkStatus(404)
            ->checkIndiceExiste('erro')
            ->checkNaoVazio('erro')
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.titulo', 'Página não existe!')
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }
}
