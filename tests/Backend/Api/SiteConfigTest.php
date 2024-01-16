<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Geral\Status;
use App\Classes\SiteConfig\Template;

class SiteConfigTest extends Tests
{
    private string $idAnafe = '0ffc5c56b99f81ca0edea8bdf524b688';
    protected string $scope = 'site_config';
    protected string $uri = '/site-config';
    public string $automatico = 'lbsad';

    public function __construct()
    {
        $this
            ->tabela(TABELA_SITE_CONFIG)
            ->resetar();
        parent::__construct();
    }

    public function naoPodeSalvarEmpresaDuplicadaTest()
    {
        $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody())
            ->post('/site-config');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O valor do campo Empresa já existe.');
    }

    public function naoPodeSalvarLinkSiteDuplicadoTest()
    {
        $this
            ->Curl
            ->loginPainel()
            ->body($this->pegarBody(empresa: $this->idAnafe, dominio: 'https://unareg.org.br'))
            ->post('/site-config');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O valor do campo Link do site já existe.');
    }

    protected function pegarBody(?string $empresa = null, ?string $dominio = null)
    {
        $dominio = !empty($dominio) ? $dominio : 'https://' . dominioAleatorio();
        $empresa = !empty($empresa) ? $empresa : '14afa776394ada4be23be6acf7e3259e';
        return [
            'titulo_painel'    => nomeCompletoAleatorio(),
            'titulo'           => 'Novo registro',
            'empresa'          => $empresa,
            'logo_principal'   => uuid(),
            'favicon'          => uuid(),
            'descricao'        => 'Descrição do site novo registro',
            'template'         => Template::PADRAO,
            'contato_telefone' => '6132730512',
            'contato_celular'  => '61984008812',
            'contato_whatsapp' => '61984008812',
            'contato_email'    => 'atendimento@unareg.org.br',
            'contato_endereco' => 'SAUS Quadra 4 - Bloco A - Salas 923/924 - Ed. Victória Office Tower - Asa Sul - CEP: 70070-938 - Brasília/DF',
            'mapa_imagem'      => '',
            'link_site'        => $dominio,
            'mapa_link'        => 'https://www.google.com.br/maps/dir//4Legal+-+SAUS,+Quadra+04,+Bloco+A,+Sala+725,+Edif%C3%ADcio+Victoria+Office+Tower+%E2%80%93+Asa+Sul,+Bras%C3%ADlia+-+DF,+70070-938/@-15.8035647,-47.8869622,15.75z/data=!4m8!4m7!1m0!1m5!1m1!1s0x935a3b20a9f142cf:0x4de620ef019d03c3!2m2!1d-47.8819771!2d-15.8031241',
            'cor_principal'    => '#2b8ac8',
            'rede_youtube'     => 'https://youtube.com',
            'rede_facebook'    => 'https://facebook.com',
            'rede_instagram'   => 'https://instagram.com',
            'rede_x'           => 'https://x.com',
            'status'           => Status::ATIVO,
        ];
    }
}
