<?php

namespace App\Models\Api\ConstrutorClube;

use Modules\Botao;
use App\Helpers\PrimeiroAcessoHelper;

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
        $corSecundaria = !empty($Construtor->cor_secundaria) ? $Construtor->cor_secundaria : $Construtor->cor_principal;
        $camposPrimeiroAcesso = !empty($Construtor->campos_primeiro_acesso) ? $Construtor->campos_primeiro_acesso : PrimeiroAcessoHelper::CAMPOS_PADRAO;
        $this->construtor = [
            'id'                      => $Construtor->id,
            'empresa'                 => $Construtor->empresa,
            'titulo'                  => $Construtor->titulo,
            'cor_principal'           => $Construtor->cor_principal,
            'cor_secundaria'          => $corSecundaria,
            'logo_principal'          => $Construtor->logo_principal,
            'logo_secundaria'         => $Construtor->logo_secundaria,
            'favicon'                 => $Construtor->favicon,
            'logo_footer'             => $Construtor->logo_footer,
            'link_login'              => $Construtor->link_login,
            'link_funcionario'        => $Construtor->link_funcionario,
            'link_cadastro'           => $Construtor->link_cadastro,
            'link_odontologico'       => $Construtor->link_odontologico,
            'link_salavip'            => $Construtor->link_salavip,
            'link_app_android'        => $linkAndroid,
            'link_app_ios'            => $linkIos,
            'contato_endereco'        => $Construtor->contato_endereco,
            'contato_horario'         => $Construtor->contato_horario,
            'contato_telefone'        => $Construtor->contato_telefone->numero(),
            'contato_whatsapp'        => $Construtor->contato_whatsapp->numero(),
            'contato_email'           => $Construtor->contato_email->email(),
            'header_tag'              => $Construtor->header_tag,
            'header_descricao'        => $Construtor->header_descricao,
            'redes_sociais'           => [
                'link_facebook'    => $Construtor->link_facebook,
                'link_instagram'   => $Construtor->link_instagram,
                'link_twitter'     => $Construtor->link_twitter,
                'link_linkedin'    => $Construtor->link_linkedin,
                'link_youtube'     => $Construtor->link_youtube,
                'link_tiktok'      => $Construtor->link_tiktok,
            ],
            'menu'                    => [
                'primeiro_acesso'     => $Construtor->menu_primeiro_acesso->valor(),
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
                'indicar_loja'        => $Construtor->menu_indicar_loja->valor(),
                'indicar_usuario'     => $Construtor->menu_indicar_usuario->valor(),
                'meu_parceiro'        => $Construtor->menu_meu_parceiro->valor(),
                'cupom'               => $Construtor->menu_cupom->valor(),
                'odontologico'        => $Construtor->menu_odontologico->valor(),
                'premium'             => $Construtor->menu_premium->valor(),
                'samsung'             => $Construtor->menu_samsung->valor(),
                'corrida'             => $Construtor->menu_corrida->valor(),
                'show_nacional'       => $Construtor->menu_show_nacional->valor(),
                'show_internacional'  => $Construtor->menu_show_internacional->valor(),
                'tema'                => $Construtor->menu_tema->valor(),
                'dependente'          => $dependente,
                'funcionario'         => $Construtor->menu_funcionario->valor(),
                'carteira'            => $Construtor->menu_carteira->valor(),
                'salavip'             => $Construtor->menu_salavip->valor(),
                'ponto_mais_acao'     => $Construtor->menu_ponto_mais_acao->valor(),
                'sair'                => $Construtor->menu_sair->valor()
            ],
            'campos_primeiro_acesso' => $camposPrimeiroAcesso,
            'input_grupo'            => [
                'label'       => $Construtor->grupo_label,
                'placeholder' => $Construtor->grupo_placeholder
            ],
            'texto_login_usuario'     => $Construtor->texto_login_usuario,
            'texto_login_dependente'  => $Construtor->texto_login_dependente,
            'texto_login_funcionario' => $Construtor->texto_login_funcionario,
            'tipo_ativacao'           => $Construtor->tipo_ativacao->indice(),
            'administrado'            => $Construtor->administrado_status->valor(),
            'tela_login'              => $Construtor->tela_login->valor(),
            'chat'                    => $Construtor->chat_status->valor(),
            'api'                     => $api,
        ];
    }
}
