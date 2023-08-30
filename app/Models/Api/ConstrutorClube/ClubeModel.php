<?php

namespace App\Models\Api\ConstrutorClube;

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
        $horario = $Construtor->contato_horario;
        $endereco = $Construtor->contato_endereco;
        $this->construtor = [
            'id'                      => $Construtor->id,
            'titulo'                  => $Construtor->titulo,
            'cor'                     => $Construtor->cor,
            'logo'                    => $Construtor->logo,
            'favicon'                 => $Construtor->favicon,
            'link_login'              => $Construtor->link_login,
            'link_cadastro'           => $Construtor->link_cadastro,
            'link_odontologico'       => $Construtor->link_odontologico,
            'link_salavip'            => $Construtor->link_salavip,
            'link_app_android'        => $linkAndroid,
            'link_app_ios'            => $linkIos,
            'contato_endereco'        => !empty($endereco) ? $endereco : 'SIG Quadra 4 Lote 125, Bloco A Sala 10 - Asa Sul, Brasília/DF - CEP: 70610-440',
            'contato_horario'         => !empty($horario) ? $horario : 'Seg. à Sex. das 9h às 18h',
            'contato_telefone'        => $Construtor->contato_telefone->numero(),
            'contato_whatsapp'        => $Construtor->contato_whatsapp->numero(),
            'contato_email'           => $Construtor->contato_email->email(),
            'header_tag'              => $Construtor->header_tag,
            'header_descricao'        => $Construtor->header_descricao,
            'menu'                    => [
                'primeiro_acesso'     => $api == Botao::NAO || $dependente == Botao::SIM ? Botao::SIM : Botao::NAO,
                'baixar_app'          => !empty($linkAndroid) || !empty($linkIos) ? Botao::SIM : Botao::NAO,
                'faq'                 => $Construtor->menu_faq->valor(),
                'acesso_rapido'       => $Construtor->menu_acesso_rapido->valor(),
                'como_funciona'       => $Construtor->menu_como_funciona->valor(),
                'loja'                => $Construtor->menu_loja->valor(),
                'mapa'                => $Construtor->menu_mapa->valor(),
                'cinema'              => $Construtor->menu_cinema->valor(),
                'turismo'             => $Construtor->menu_turismo->valor(),
                'historico'           => $Construtor->menu_historico->valor(),
                'credito_sicoob'      => $Construtor->menu_credito_sicoob->valor(),
                'farmacia'            => $Construtor->menu_farmacia->valor(),
                'automovel'           => $Construtor->menu_automovel->valor(),
                'saude_vitoria'       => $Construtor->menu_saude_vitoria->valor(),
                'saude_amil'          => $Construtor->menu_saude_amil->valor(),
                'saude_seguro'        => $Construtor->menu_saude_seguro->valor(),
                'saude_cnu'           => $Construtor->menu_saude_cnu->valor(),
                'saude_florianopolis' => $Construtor->menu_saude_florianopolis->valor(),
                'cashback'            => $Construtor->menu_cashback->valor(),
                'indicacao'           => $Construtor->menu_indicacao->valor(),
                'cupom'               => $Construtor->menu_cupom->valor(),
                'odontologico'        => $Construtor->menu_odontologico->valor(),
                'premium'             => $Construtor->menu_premium->valor(),
                'dependente'          => $dependente,
                'carteira'            => $Construtor->menu_carteira->valor(),
                'salavip'             => $Construtor->menu_salavip->valor(),
                'sair'                => $Construtor->menu_sair->valor()
            ],
            'tipo_ativacao' => $Construtor->tipo_ativacao->indice(),
            'chat'          => $Construtor->chat_status->valor(),
            'api'           => $api
        ];
    }
}
