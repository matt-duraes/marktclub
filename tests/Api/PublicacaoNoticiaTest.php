<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use App\Classes\PublicacaoNoticia\Local;
use App\Classes\PublicacaoNoticia\Tipo;
use Modules\Botao;
use Tests\Api\Token\Clube;

class PublicacaoNoticiaTest extends Clube
{
    private array $idsNotocia;

    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    private function getBody(array $array = []): array
    {
        return array_merge([
            'titulo_grande'      => 'Título grande',
            'titulo_pequeno'     => '',
            'subtitulo'          => '',
            'data_inicio'        => $this->agora(),
            'data_final'         => '',
            'data_atualizada'    => '',
            'texto_grande'       => 'Texto grande',
            'texto_pequeno'      => '',
            'imagem_grande'      => '',
            'imagem_pequena'     => '',
            'imagem_social'      => '',
            'fonte_noticia'      => '',
            'fonte_link'         => '',
            'autor_noticia'      => '',
            'permissao_restrita' => valorAleatorio(array_keys((new Botao())->select())),
            'header_titulo'      => '',
            'header_descricao'   => '',
            'header_tag'         => '',
            'permissao_site'     => valorAleatorio(array_keys((new Botao())->select())),
            'local'              => valorAleatorio(array_keys((new Local())->select())),
            'tipo'               => valorAleatorio(array_keys((new Tipo())->select())),
            'status'             => valorAleatorio(array_keys((new Status())->select()))
        ], $array);
    }

    public function listarNoticiasTest(): PublicacaoNoticiaTest
    {
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/publicacao-noticia');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function salvarNovaNoticiaTest(): PublicacaoNoticiaTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
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

    public function atualizarNoticiaTest(): PublicacaoNoticiaTest
    {
        $this
            ->Curl
            ->body($this->getBody())
            ->put('/publicacao-noticia/' . $this->idsNotocia[0]);

        return $this
            ->checkStatus(204);
    }

    public function buscarNoticiaTest(): PublicacaoNoticiaTest
    {
        $this
            ->Curl
            ->get('/publicacao-noticia/' . $this->idsNotocia[0]);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->idsNotocia[0]);
    }

    public function naoPodeSalvarNoticiaSemTituloTest(): PublicacaoNoticiaTest
    {
        $this
            ->Curl
            ->body($this->getBody([
                'titulo_grande' => ''
            ]))
            ->post('/publicacao-noticia');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Título grande não pode ser vazio.');
    }

    public function salvarComTodasAsInformacoesPreenchidasTest(): PublicacaoNoticiaTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody([
                'titulo_pequeno'   => 'Título pequeno',
                'subtitulo'        => 'Subtítulo',
                'data_final'       => '2021-01-01 00:00:00',
                'data_atualizada'  => $this->agora(),
                'texto_pequeno'    => 'Texto pequeno',
                'imagem_grande'    => 'Imagem grande',
                'imagem_pequena'   => 'Imagem pequena',
                'imagem_social'    => 'Imagem social',
                'fonte_noticia'    => 'Fonte notícia',
                'fonte_link'       => 'Fonte link',
                'autor_noticia'    => 'Autor notícia',
                'header_titulo'    => 'Header título',
                'header_descricao' => 'Header descrição',
                'header_tag'       => 'Header tag',
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
            ->body($this->getBody([
                'titulo_grande' => ''
            ]))
            ->put('/publicacao-noticia/' . $this->idsNotocia[0]);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Título grande não pode ser vazio.');
    }

    public function deletarNoticiaTest(): PublicacaoNoticiaTest
    {
        foreach ($this->idsNotocia as $id) {
            $this
                ->Curl
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
                ->get('/publicacao-noticia/' . $id);

            $this
                ->checkStatus(404)
                ->checkIndiceExiste('erro')
                ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
        }
        return $this;
    }
}
