<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use Tests\Api\Token\Clube;

class PublicacaoDiretoriaTest extends Clube
{
    private string $idPublicacao;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'nome'   => nomeCompletoAleatorio(),
            'texto'  => 'Texto da publicação',
            'cargo'  => 'Cargo',
            'imagem' => 'imagem',
            'status' => valorAleatorio(array_keys((new Status())->select()))
        ], $array);
    }

    public function litarPublicacoesTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/publicacao-diretoria');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarPublicacaoValidaTest(): PublicacaoDiretoriaTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/publicacao-diretoria')
            ->array();

        $this->idPublicacao = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado.id');
    }

    public function naoPodeSalvarComStatusInvalidoTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'status' => 'STATUS INVALIDO'
            ]))
            ->post('/publicacao-diretoria');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    public function naoPodeSalvarComNomeSemSobrenomeTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'nome' => nomeAleatorio()
            ]))
            ->post('/publicacao-diretoria');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Nome não é um valor válido.');
    }

    public function naoPodeSalvarComNomeVaziosTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'nome'   => ''
            ]))
            ->post('/publicacao-diretoria');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Nome não pode ser vazio.');
    }

    public function atualizarApenasOStatusTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->body([
                'status' => valorAleatorio(array_keys((new Status())->select()))
            ])
            ->put('/publicacao-diretoria/' . $this->idPublicacao);

        return $this
            ->checkStatus(204);
    }

    public function buscarPublicacaoTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->get("/publicacao-diretoria/{$this->idPublicacao}");

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado');
    }

    public function deletarPublicacaoTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->delete('/publicacao-diretoria/' . $this->idPublicacao);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAcharPublicacaoApagadaTest(): PublicacaoDiretoriaTest
    {
        $this
            ->Curl
            ->get('/publicacao-diretoria/' . $this->idPublicacao);

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }
}
