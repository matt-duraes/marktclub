<?php

namespace Tests\Api;

use Tests\Tests;
use Modules\Botao;
use App\Classes\Geral\Status;
use App\Classes\PublicacaoNoticia\Tipo;

class PublicacaoNoticiaTest extends Tests
{
    private array $idsNotocia;
    protected string $scope = 'publicacao_noticia';
    protected string $uri = '/publicacao-noticia';
    public string $automatico = 'lbsad';
    public bool $automaticoPainel = true;

    public function naoPodeSalvarNoticiaSemTituloTest(): PublicacaoNoticiaTest
    {
        $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody([
                'titulo_grande' => ''
            ]))
            ->post('/publicacao-noticia');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Título grande não pode ser vazio.');
    }

    public function salvarComAsInformacoesMinimasTest(): PublicacaoNoticiaTest
    {
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody([
                'titulo_pequeno'   => '',
                'subtitulo'        => '',
                'data_final'       => '',
                'data_atualizada'  => agora(),
                'texto_pequeno'    => '',
                'imagem_grande'    => '',
                'imagem_pequena'   => '',
                'imagem_social'    => '',
                'fonte_noticia'    => '',
                'fonte_link'       => '',
                'autor_noticia'    => '',
                'header_titulo'    => '',
                'header_descricao' => '',
                'header_tag'       => '',
            ]))
            ->post('/publicacao-noticia')
            ->array();

        $this->idsNotocia[] = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    public function naoPodeAtualizarComTituloVazioTest(): PublicacaoNoticiaTest
    {
        $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody([
                'titulo_grande' => ''
            ]))
            ->put('/publicacao-noticia/' . $this->idsNotocia[0]);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Título grande não pode ser vazio.');
    }

    public function deletarNoticiasTest(): PublicacaoNoticiaTest
    {
        foreach ($this->idsNotocia as $id) {
            $this
                ->Curl
                ->loginPainel()
                ->delete('/publicacao-noticia/' . $id);

            $this
                ->checkStatus(204);
        }
        return $this;
    }

    public function naoPodeAcharNoticiaDeletadaTest(): PublicacaoNoticiaTest
    {
        foreach ($this->idsNotocia as $id) {
            $this
                ->Curl
                ->loginPainel()
                ->get('/publicacao-noticia/' . $id);

            $this
                ->checkStatus(404)
                ->checkIndiceExiste('erro')
                ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
        }
        return $this;
    }

    protected function pegarBody(array $array = []): array
    {
        return array_merge([
            'titulo_grande'      => nomeCompletoAleatorio(),
            'titulo_pequeno'     => nomeCompletoAleatorio(),
            'subtitulo'          => nomeCompletoAleatorio(),
            'data_inicio'        => agora(),
            'data_final'         => '2040-01-01 00:00:00',
            'data_atualizada'    => agora(),
            'texto_grande'       => nomeCompletoAleatorio(),
            'texto_pequeno'      => nomeCompletoAleatorio(),
            'imagem_grande'      => '',
            'imagem_pequena'     => '',
            'imagem_social'      => '',
            'fonte_noticia'      => nomeCompletoAleatorio(),
            'fonte_link'         => 'https://www.google.com.br',
            'autor_noticia'      => nomeCompletoAleatorio(),
            'permissao_restrita' => valorAleatorio(array_keys((new Botao())->select())),
            'header_titulo'      => nomeCompletoAleatorio(),
            'header_descricao'   => nomeCompletoAleatorio(),
            'header_tag'         => ['tag1', 'tag2', 'tag3'],
            'permissao_site'     => valorAleatorio(array_keys((new Botao())->select())),
            'home'               => '',
            'tipo'               => valorAleatorio(array_keys((new Tipo())->select())),
            'status'             => valorAleatorio(array_keys((new Status())->select()))
        ], $array);
    }
}
