<?php

namespace Tests\Api;

use App\Classes\ComunicacaoPublicidade\Tipo;
use App\Classes\Geral\Status;
use Tests\Api\Token\Clube;

class ComunicacaoPublicidadeTest extends Clube
{
    private string $idComunicacaoPublicidade;

    public function salvarComunicacaoPublicidadeTest(): ComunicacaoPublicidadeTest
    {
        $this->api('comunicacao_publicidade:salvar');
        $dado = $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($this->getBodyPadrao())
            ->post('/comunicacao-publicidade')
        ->array();

        $this->idComunicacaoPublicidade = $dado['dado']['id'] ?? '';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeSalvarDataInicioMaiorTest(): ComunicacaoPublicidadeTest
    {
        $this->api('comunicacao_publicidade:salvar');

        $body = $this->getBodyPadrao();
        $body['data_inicio'] = $this->dataFutura();
        $body['data_final'] = $this->dataPassada();

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/comunicacao-publicidade');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'A data de inicio não pode ser maior que a data final.')
            ->checkIndiceIgual('status', 'erro');
    }

    public function naoPodeSalvarSatausInvalidoTest(): ComunicacaoPublicidadeTest
    {
        $this->api('comunicacao_publicidade:salvar');

        $body = $this->getBodyPadrao();
        $body['status'] = valorAleatorio(array_values((new Status())->select()));

        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($body)
            ->post('/comunicacao-publicidade');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.')
            ->checkIndiceIgual('status', 'erro');
    }

    public function listarComunicacaoPublicidadeTest(): ComunicacaoPublicidadeTest
    {
        $this->api('comunicacao_publicidade:listar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->json([
                'pagina' => 1
            ])
            ->get('/comunicacao-publicidade');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function buscarComunicacaoPublicidadeTest(): ComunicacaoPublicidadeTest
    {
        $this->api('comunicacao_publicidade:buscar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->get('/comunicacao-publicidade/' . $this->idComunicacaoPublicidade);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function atualizarComunicacaoPublicidadeTest(): ComunicacaoPublicidadeTest
    {
        $this->api('comunicacao_publicidade:atualizar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->body($this->getBodyPadrao())
            ->put('/comunicacao-publicidade/' . $this->idComunicacaoPublicidade);

        return $this
            ->checkStatus(204);
    }

    public function deletarComunicacaoPublicidadeTest(): ComunicacaoPublicidadeTest
    {
        $this->api('comunicacao_publicidade:deletar');
        $this
            ->Curl
            ->header(['Authorization' => $this->pegarToken()])
            ->delete('/comunicacao-publicidade/' . $this->idComunicacaoPublicidade);

        return $this
            ->checkStatus(204);
    }

    private function getBodyPadrao()
    {
        return [
            'titulo'            => $this->nomeCompleto(),
            'data_inicio'       => $this->dataPassada(),
            'data_final'        => $this->dataFutura(),
            'parceiro'          => 'f10e05c0-5b02-4bff-8e22-719a8797f0d6',
            'status'            => valorAleatorio(array_keys((new Status())->select())),
            'imagem_desktop'    => '2ee20169-49eb-4dcd-891d-e1c03a85ec80',
            'link'              => '',
            'tipo'              => valorAleatorio(array_keys((new Tipo())->select())),
            'imagem_mobile'     => '2ee20169-49eb-4dcd-891d-e1c03a85ec80'
        ];
    }
}
