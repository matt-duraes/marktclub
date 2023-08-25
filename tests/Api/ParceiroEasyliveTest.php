<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Geral\Status;
use App\Classes\ParceiroEasylive\Tipo;

class ParceiroEasyliveTest extends Tests
{
    private array $idParceiro;

    private function getBody(array $array = []): array
    {
        return array_merge([
            'titulo'        => nomeAleatorio(),
            'tipo'          => valorAleatorio(array_keys((new Tipo())->select())),
            'data_validade' => '2021-12-31',
            'status'        => 'ativo',
            'imagem'        => 'imagem.jpg',
            'empresa'       => [1, 2]
        ], $array);
    }

    public function listarTodosTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:listar');
        $dado = $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/parceiro-easylive')
            ->array()['dado'];

        $this->idParceiro[] = $dado['lista'][0]['id'] ?? 'sem-id';

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista');
    }

    public function listarStatusAtivoTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:listar');
        $this
            ->Curl
            ->json([
                'pagina' => 1,
                'status' => 'ativo'
            ])
            ->get('/parceiro-easylive');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista');
    }

    public function buscarPorIdTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:buscar');
        $this
            ->Curl
            ->get('/parceiro-easylive/' . valorAleatorio($this->idParceiro));

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function salvarValidoTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody())
            ->post('/parceiro-easylive');

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function salvarComImagemVaziaTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody([
                'imagem' => ''
            ]))
            ->post('/parceiro-easylive')
            ->array();

        $this->idParceiro[] = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function salvarComDataVaziaTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody([
                'data_validade' => ''
            ]))
            ->post('/parceiro-easylive')
            ->array();

        $this->idParceiro[] = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function atualizarTodosOsValoresTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody())
            ->put('/parceiro-easylive/' . valorAleatorio($this->idParceiro));

        return $this
            ->checkStatus(204);
    }

    public function atualizarApenasOStatusTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => Status::INATIVO
            ])
            ->put('/parceiro-easylive/' . valorAleatorio($this->idParceiro));

        return $this
            ->checkStatus(204);
    }

    public function deletarTodosCriadosTest(): ParceiroEasyliveTest
    {
        foreach ($this->idParceiro as $id) {
            $this->api('parceiro_easylive:deletar');
            $this
                ->Curl
                ->loginPainel()
                ->delete('/parceiro-easylive/' . $id);

            $this->checkStatus(204);
        }
        return $this;
    }
}
