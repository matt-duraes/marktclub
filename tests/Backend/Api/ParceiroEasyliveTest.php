<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Geral\Status;
use App\Classes\ParceiroEasylive\Tipo;

class ParceiroEasyliveTest extends Tests
{
    private array $idParceiro;
    protected string $scope = 'parceiro_easylive';
    protected string $uri = '/parceiro-easylive';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        parent::__construct();
        $this->tabela(TABELA_PARCEIRO_EASYLIVE)->resetar();
    }

    public function salvarComImagemVaziaTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody([
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
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceIgual('dado.lista.0.status', Status::ATIVO);
    }

    public function salvarComDataVaziaTest(): ParceiroEasyliveTest
    {
        $this->api('parceiro_easylive:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody([
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

    protected function pegarBody(array $array = []): array
    {
        return array_merge([
            'titulo'        => nomeAleatorio(),
            'tipo'          => valorAleatorio(array_keys((new Tipo())->select())),
            'data_validade' => dataFuturaAleatorio(),
            'status'        => Status::ATIVO,
            'imagem'        => 'imagem.jpg',
            'empresa'       => ['14afa776394ada4be23be6acf7e3259e']
        ], $array);
    }
}
