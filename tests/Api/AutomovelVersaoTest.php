<?php

namespace Tests\Api;

use Tests\Api\Token\Clube;
use App\Classes\Geral\Status;

class AutomovelVersaoTest extends Clube
{
    private string $idAutomovel;
    private array $idModelo;

    public function __construct()
    {
        parent::__construct();
        $this->getListaModelos();
        $this->Curl->header(['Authorization' => $this->pegarToken()]);
    }

    public function salvarAutomovelTest(): AutomovelVersaoTest
    {
        $this->api('automovel_versao:salvar');
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/automovel-versao')
            ->array();

        $this->idAutomovel = $dado['dado']['id'] ?? 'sem-id';

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
            ->delete('/automovel-versao/' . $this->idAutomovel);

        return $this
            ->checkStatus(204);
    }

    private function getListaModelos(): void
    {
        $this->api('automovel_modelo:listar');
        $dado = $this
            ->Curl
            ->json([
                'pagina' => 1,
            ])
            ->get('/automovel-modelo')
            ->array();

        foreach ($dado['dado']['lista'] ?? [] as $modelo) {
            $this->idModelo[] = $modelo['id'];
        }
    }

    private function getBody()
    {
        return [
            'titulo'    => nomeCompletoAleatorio(),
            'cor'       => 'VERMELHO',
            'valor_de'  => rand(10000, 20000),
            'valor_por' => rand(5000, 10000),
            'status'    => valorAleatorio(array_keys((new Status())->select())),
            'modelo'    => !empty($this->idModelo) ? valorAleatorio($this->idModelo) : ''
        ];
    }
}
