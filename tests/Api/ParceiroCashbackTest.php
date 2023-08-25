<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use Tests\Tests;

class ParceiroCashbackTest extends Tests
{
    private string $idParceiro;

    private function getBody(): array
    {
        return [
            'titulo'          => nomeCompletoAleatorio(),
            'texto_descricao' => 'Descrição do parceiro',
            'texto_restricao' => 'Restrição do parceiro',
            'texto_outro'     => 'Outro texto do parceiro',
            'comissao_minima' => 2.8,
            'comissao_maxima' => 10000000,
            'status'          => Status::ATIVO,
            'empresa'         => ['14afa776394ada4be23be6acf7e3259e'],
            'link_site'       => 'https://www.google.com.br',
            'imagem'          => '123',
        ];
    }

    public function listarParceirosTest(): ParceiroCashbackTest
    {
        $this->api('parceiro_cashback:listar');
        $this
            ->Curl
            ->json([
                'pagina'     => 1,
                'quantidade' => 50
            ])
            ->get('/parceiro-cashback');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarNovoParceiroTest(): ParceiroCashbackTest
    {
        $this->api('parceiro_cashback:salvar');
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/parceiro-cashback')
            ->array()['dado'] ?? '';

        $this->idParceiro = $dado['id'] ?? '';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id');
    }

    public function buscarParceiroSalvoTest(): ParceiroCashbackTest
    {
        $this->api('parceiro_cashback:buscar');
        $this
            ->Curl
            ->get('/parceiro-cashback/' . $this->idParceiro);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function atualizarTodasAsInformacoesTest(): ParceiroCashbackTest
    {
        $this->api('parceiro_cashback:atualizar');
        $this
            ->Curl
            ->body($this->getBody())
            ->put('/parceiro-cashback/' . $this->idParceiro);

        return $this->checkStatus(204);
    }

    public function atualizarApenasOStatusTest(): ParceiroCashbackTest
    {
        $this->api('parceiro_cashback:atualizar');
        $this
            ->Curl
            ->body([
                'status' => Status::INATIVO
            ])
            ->put('/parceiro-cashback/' . $this->idParceiro);

        return $this->checkStatus(204);
    }

    public function deletarParceiroTest(): ParceiroCashbackTest
    {
        $this->api('parceiro_cashback:deletar');
        $this
            ->Curl
            ->delete('/parceiro-cashback/' . $this->idParceiro);

        return $this->checkStatus(204);
    }
}
