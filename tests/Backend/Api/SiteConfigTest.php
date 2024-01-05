<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Geral\Status;

class SiteConfigTest extends Tests
{
    private string $idUnareg = '4cceef2a4ee3d677dd15955daace4bba';
    private string $idNovo = '';
    private string $novoTitulo = 'Novo Título';

    public function __construct()
    {
        parent::__construct();
        $this
            ->tabela(TABELA_SITE_CONFIG)
            ->resetar();
    }

    public function listarTodosConfigTest(): self
    {
        $this->api('site_config:listar');
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/site-config');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }

    public function pegarConfigUnaregTest(): self
    {
        $this
            ->api('site_config:buscar')
            ->Curl
            ->get('/site-config/' . $this->idUnareg);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('data.id', $this->idUnareg);
    }

    public function testarSalvarNovoRegistroTest()
    {
        $dado = $this
            ->api('site_config:salvar')
            ->Curl
            ->body($this->pegarBody())
            ->post('/site-config')
            ->array();
        $this->idNovo = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('data.id');
    }

    public function buscarIndiceSalvoTest()
    {
        $this
            ->api('site_config:buscar')
            ->Curl
            ->get('/site-config/' . $this->idNovo);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->idNovo);
    }

    public function naoPodeSalvarEmpresaDuplicadaTest()
    {
        $this
            ->api('site_config:salvar')
            ->Curl
            ->body($this->pegarBody($this->idUnareg))
            ->post('/site-config');

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('data.erro.mensagem', '');
    }

    public function atualizarTituloUnaregTest()
    {
        $this
            ->api('site_config:atualizar')
            ->Curl
            ->body(['titulo_painel' => $this->novoTitulo])
            ->put('/site-config/' . $this->idNovo);

        return $this->checkStatus(204);
    }

    public function verificarTituloAtualizouTest()
    {
        $this
            ->api('site_config:buscar')
            ->Curl
            ->get('/site-config/' . $this->idNovo);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('dado.titulo_painel', $this->novoTitulo);
    }

    public function deletaRegistroSalvoTest()
    {
        $this
            ->api('site_config:deletar')
            ->Curl
            ->delete('/site-config/' . $this->idNovo);

        return $this->checkStatus(204);
    }

    public function verificaDeletouRegistroTest()
    {
        $this
            ->api('site_config:buscar')
            ->Curl
            ->get('/site-config/' . $this->idNovo);

        return $this->checkStatus(404);
    }

    private function pegarBody(?string $empresa = null)
    {
        $empresa = !empty($empresa) ? $empresa : '14afa776394ada4be23be6acf7e3259e';
        return [
            [
                'empresa'          => $empresa,
                'logo_principal'   => '123',
                'favicon'          => '',
                'titulo_painel'    => 'Unareg',
                'titulo'           => 'Unareg',
                'descricao'        => 'Descrição do site da UNAREG',
                'template'         => 'UNAREG',
                'contato_telefone' => '6132730512',
                'contato_celular'  => '61984008812',
                'contato_whatsapp' => '61984008812',
                'contato_email'    => 'atendimento@unareg.org.br',
                'contato_endereco' => 'SAUS Quadra 4 - Bloco A - Salas 923/924 - Ed. Victória Office Tower - Asa Sul - CEP: 70070-938 - Brasília/DF',
                'mapa_arquivo'     => '',
                'mapa_link'        => 'https://www.google.com.br/maps/dir//4Legal+-+SAUS,+Quadra+04,+Bloco+A,+Sala+725,+Edif%C3%ADcio+Victoria+Office+Tower+%E2%80%93+Asa+Sul,+Bras%C3%ADlia+-+DF,+70070-938/@-15.8035647,-47.8869622,15.75z/data=!4m8!4m7!1m0!1m5!1m1!1s0x935a3b20a9f142cf:0x4de620ef019d03c3!2m2!1d-47.8819771!2d-15.8031241',
                'cor_principal'    => '#2b8ac8',
                'rede_youtube'     => 'https://youtube.com',
                'rede_facebook'    => 'https://facebook.com',
                'rede_instagram'   => 'https://instagram.com',
                'rede_x'           => 'https://x.com',
                'status'           => Status::ATIVO,
            ]
        ];
    }
}
