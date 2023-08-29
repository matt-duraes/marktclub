<?php

namespace App\Models\Api\AdminConstrutor;

use Modules\Botao;

final class ClubeModel
{
    public array $construtor;

    public function __construct(
        private ConstrutorEntity $Construtor
    ) {
        $this->montarClube($Construtor);
    }

    private function montarClube(ConstrutorEntity $Construtor)
    {
        $api = $Construtor->api_status->valor();
        $dependente = $Construtor->menu_dependente->valor();
        $linkAndroid = $Construtor->link_app_android;
        $linkIos = $Construtor->link_app_ios;
        $horario = $Construtor->horario_atendimento;
        $endereco = $Construtor->contato_endereco;
        $this->construtor = [
            'id'                  => $Construtor->id,
            'titulo'              => $Construtor->titulo,
            'cor'                 => $Construtor->cor,
            'link_logo'           => $Construtor->link_logo,
            'link_login'          => $Construtor->link_login,
            'link_app_android'    => $linkAndroid,
            'link_app_ios'        => $linkIos,
            'contato_endereco'    => !empty($endereco) ? $endereco : 'SIG Quadra 4 Lote 125, Bloco A Sala 10 - Asa Sul, Brasília/DF - CEP: 70610-440',
            'horario_atendimento' => !empty($horario) ? $horario : 'Seg. à Sex. das 9h às 18h',
            'contato_telefone'    => $Construtor->contato_telefone->numero(),
            'contato_whatsapp'    => $Construtor->contato_whatsapp->numero(),
            'contato_email'       => $Construtor->contato_email->email(),
            'header_tag'          => $Construtor->header_tag,
            'header_descricao'    => $Construtor->header_descricao,
            'menu'                => [
                'primeiro_acesso' => $api == Botao::NAO || $dependente == Botao::SIM ? Botao::SIM : Botao::NAO,
                'baixar_app'      => !empty($linkAndroid) || !empty($linkIos) ? Botao::SIM : Botao::NAO,
                'faq'             => $Construtor->menu_faq->valor(),
                'acesso_rapido'   => $Construtor->menu_acesso_rapido->valor(),
                'como_funciona'   => $Construtor->menu_como_funciona->valor(),
                'convenio'        => $Construtor->menu_convenio->valor(),
                'convenio_mapa'   => $Construtor->menu_convenio_mapa->valor(),
                'cinema'          => $Construtor->menu_cinema->valor(),
                'turismo'         => $Construtor->menu_turismo->valor(),
                'historico'       => $Construtor->menu_historico->valor(),
                'sicoob_credito'  => $Construtor->menu_sicoob_credito->valor(),
                'medicamento'     => $Construtor->menu_medicamento->valor(),
                'automovel'       => $Construtor->menu_automovel->valor(),
                'saude_vitoria'   => $Construtor->menu_saude_vitoria->valor(),
                'saude_amil'      => $Construtor->menu_saude_amil->valor(),
                'saude_seguros'   => $Construtor->menu_saude_seguros->valor(),
                'cashback'        => $Construtor->menu_cashback->valor(),
                'indicacao'       => $Construtor->menu_indicacao->valor(),
                'cupom'           => $Construtor->menu_cupom->valor(),
                'odontologia'     => $Construtor->menu_odontologia->valor(),
                'premium'         => $Construtor->menu_premium->valor(),
                'dependente'      => $dependente,
                'carteiria'       => $Construtor->menu_carteiria->valor(),
                'salavip'         => $Construtor->menu_salavip->valor(),
                'sair'            => $Construtor->menu_sair->valor()
            ],
            'api' => $api
        ];
    }
}
