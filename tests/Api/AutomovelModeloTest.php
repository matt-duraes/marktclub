<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use Tests\Api\Token\Clube;

class AutomovelModeloTest extends Clube
{
    private array $idsParceiros;
    private array $statusValidos;
    private string $idModelo;

    public function __construct()
    {
        parent::__construct();
        $this->idsParceiros = $this->getIdParceiros();
        $this->statusValidos = array_keys((new Status())->select());
    }

    private function getBody(): array
    {
        return [
            'titulo'      => nomeCompletoAleatorio(),
            'parceiro'    => valorAleatorio($this->idsParceiros),
            'imagem'      => 'asdsdsd',
            'data_inicio' => $this->dataPassada(),
            'data_final'  => $this->dataFutura(),
            'status'      => valorAleatorio($this->statusValidos)
        ];
    }

    public function salvarModeloTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:salvar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($this->getBody())
            ->post('/automovel-modelo')
            ->array();

        $this->idModelo = $dado['dado']['id'] ?? '';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function naoPodeSalvarComUmParceiroInvalidoTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:salvar');

        $body = $this->getBody();
        $body['parceiro'] = 'PARCEIRO ERRADO';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Parceiro não encontrado.');
    }

    public function naoPodeSalvarComUmStatusInvalidoTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:salvar');

        $body = $this->getBody();
        $body['status'] = 'STATUS INVALIDO';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    public function naoPodeSalvarComUmParceiroVazioTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:salvar');

        $body = $this->getBody();
        $body['parceiro'] = '';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo parceiro é obrigatório.');
    }

    public function naoPodeSalvarComUmNomeVazioTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:salvar');

        $body = $this->getBody();
        $body['titulo'] = '';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Título não pode ser vazio.');
    }

    public function naoPodeSalvarDataInicioMaiorQueFinalTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:salvar');

        $body = $this->getBody();
        $body['data_final'] = $this->dataPassada();
        $body['data_inicio'] = $this->dataFutura();

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de inicio não pode ser maior que a data final.');
    }

    public function buscarModeloTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:buscar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function atualizarTudoTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:atualizar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($this->getBody())
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }

    public function atualizarSemStatusTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:atualizar');
        $body = $this->getBody();
        unset($body['status']);

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }

    public function atualizarApenasOStatusTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:atualizar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body(['status' => 'inativo'])
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAtualizarComStatusInvalidoTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:atualizar');
        $body = $this->getBody();
        $body['status'] = 'STATUS INVALIDO';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    public function naoPodeAtualizarComParceiroInvalidoTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:atualizar');
        $body = $this->getBody();
        $body['parceiro'] = 'PARCEIRO INVALIDO';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Parceiro não encontrado.');
    }

    public function naoPodeAtualizarComStatusVazioTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:atualizar');
        $body = $this->getBody();
        $body['status'] = '';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não pode ser vazio.');
    }

    public function naoPodeAtualizarComParceiroVazioTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:atualizar');
        $body = $this->getBody();
        $body['parceiro'] = '';

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo parceiro é obrigatório.');
    }

    public function deletarModeloTest(): AutomovelModeloTest
    {
        $this->api('automovel_modelo:deletar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->delete('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }

    private function getIdParceiros(): array
    {
        $this->api('');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->json([
                'pagina' => 1
            ])
            ->get('/parceiro-loja')
            ->array();

        $ids = [];
        foreach ($dado['dado']['lista'] as $parceiro) {
            $ids[] = $parceiro['id'];
        }

        return $ids;
    }
}
