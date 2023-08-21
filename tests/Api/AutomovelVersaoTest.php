<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use Tests\Api\Token\Clube;

class AutomovelVersaoTest extends Clube
{
    private string $idAutomovel;
    private array $IDsModelo;
    public function __construct()
    {
        parent::__construct();
        $this->IDsModelo = $this->getListaModelos();
    }

    public function salvarAutomovelTest(): AutomovelVersaoTest
    {
        $this->api('automovel_versao:salvar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($this->getBody())
            ->post('/automovel-versao')
            ->array();

        $this->idAutomovel = $dado['dado']['id'] ?? "";

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function naoSalvarComModeloValidoTest(): AutomovelVersaoTest
    {
        $this->api('automovel_versao:salvar');

        $body = $this->getBody();
        $body['modelo'] = 'MODELO ERRADO';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/automovel-versao')
            ->array();

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O modelo passado não foi encontrado.');
    }

    public function naoSalvarComValorPorVazioTest(): AutomovelVersaoTest
    {
        $this->api('automovel_versao:salvar');

        $body = $this->getBody();
        $body['valor_por'] = '';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/automovel-versao');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Valor por não pode ser vazio.');
    }

    public function buscarAutomovelTest(): AutomovelVersaoTest
    {
        $this->api('automovel_versao:buscar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/automovel-versao/' . $this->idAutomovel);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('dado.id', $this->idAutomovel);
    }

    public function atualizarAutomovelTest(): AutomovelVersaoTest
    {
        $this->api('automovel_versao:atualizar');

        $body = $this->getBody();
        unset($body['modelo']);

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->put('/automovel-versao/' . $this->idAutomovel);

        return $this
            ->checkStatus(204);
    }

    public function deletarAutomovelTest(): AutomovelVersaoTest
    {
        $this->api('automovel_versao:deletar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->delete('/automovel-versao/' . $this->idAutomovel);

        return $this
            ->checkStatus(204);
    }

    private function getListaModelos(): array
    {
        $IDsModelo = [];

        $this->api('automovel_modelo:listar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->json([
                'pagina' => 1,
            ])
            ->get('/automovel-modelo')
            ->array();

        foreach ($dado['dado']['lista'] as $modelo) {
            $IDsModelo[] = $modelo['id'];
        }

        return $IDsModelo;
    }

    private function getBody()
    {
        return [
            'titulo' => nomeCompletoAleatorio(),
            'cor' => 'VERMELHO',
            'valor_de' => rand(10000, 20000),
            'valor_por' => rand(5000, 10000),
            'status' => valorAleatorio(array_keys((New Status())->select())),
            'modelo' => valorAleatorio($this->IDsModelo)
        ];
    }
}
