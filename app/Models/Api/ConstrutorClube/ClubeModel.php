<?php

namespace App\Models\Api\ConstrutorClube;

use Modules\Botao;
use App\Helpers\PrimeiroAcessoHelper;
use App\Models\Api\Saude\Convenio\MenuModel as SaudeMenuModel;
use App\Models\Api\CampanhaVoucher\ValidarUsuarioTemVoucherModel;

final class ClubeModel
{
    public array $construtor;

    public function __construct(
        ConstrutorEntity $Construtor
    ) {
        $this->montarClube($Construtor);
    }

    /**
     * @param ConstrutorEntity $Construtor
     *
     * @return void
     */
    private function montarClube(ConstrutorEntity $Construtor): void
    {
        $api = $Construtor->api_status->valor();
        $dependente = $Construtor->menu_dependente->valor();
        $funcionario = $Construtor->menu_funcionario->valor();
        $indicarUsuario = $Construtor->menu_indicar_usuario->valor();
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
            'link_botao_sair'         => $Construtor->link_botao_sair,
            'link_login'              => $Construtor->link_login,
            'link_funcionario'        => $Construtor->link_funcionario,
            'link_cadastro'           => $Construtor->link_cadastro,
            'link_odontologico'       => $Construtor->link_odontologico,
            'link_salavip'            => $Construtor->link_salavip,
            'link_app_android'        => $linkAndroid,
            'app_versao_android'      => $Construtor->app_versao_android,
            'link_app_ios'            => $linkIos,
            'app_versao_ios'          => $Construtor->app_versao_ios,
            'contato_endereco'        => $Construtor->contato_endereco,
            'contato_horario'         => $Construtor->contato_horario,
            'contato_telefone'        => $Construtor->contato_telefone->numero(),
            'contato_whatsapp'        => $Construtor->contato_whatsapp->numero(),
            'contato_email'           => $Construtor->contato_email->email(),
            'header_tag'              => $Construtor->header_tag,
            'header_descricao'        => $Construtor->header_descricao,
            'redes_sociais'           => [
                'link_facebook'  => $Construtor->link_facebook,
                'link_instagram' => $Construtor->link_instagram,
                'link_twitter'   => $Construtor->link_twitter,
                'link_linkedin'  => $Construtor->link_linkedin,
                'link_youtube'   => $Construtor->link_youtube,
                'link_tiktok'    => $Construtor->link_tiktok,
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
                'manole'              => $Construtor->menu_manole->valor(),
                'saude_amil'          => $Construtor->menu_saude_amil->valor(),
                'saude_seguro'        => $Construtor->menu_saude_seguro->valor(),
                'saude_cnu'           => $Construtor->menu_saude_cnu->valor(),
                'saude_florianopolis' => $Construtor->menu_saude_florianopolis->valor(),
                'saude'               => (new SaudeMenuModel(idEmpresa: $Construtor->id_admin_empresa))->existe,
                'campanha_voucher'    => (new ValidarUsuarioTemVoucherModel())->existe,
                'cashback'            => $Construtor->menu_cashback->valor(),
                'indicar_loja'        => $Construtor->menu_indicar_loja->valor(),
                'indicar_usuario'     => $indicarUsuario,
                'meu_parceiro'        => $Construtor->menu_meu_parceiro->valor(),
                'cupom'               => $Construtor->menu_cupom->valor(),
                'odontologico'        => $Construtor->menu_odontologico->valor(),
                'premium'             => $Construtor->menu_premium->valor(),
                'samsung'             => $Construtor->menu_samsung->valor(),
                'lg'                  => $Construtor->menu_lg->valor(),
                'corrida'             => $Construtor->menu_corrida->valor(),
                'show_nacional'       => $Construtor->menu_show_nacional->valor(),
                'show_internacional'  => $Construtor->menu_show_internacional->valor(),
                'tema'                => $Construtor->menu_tema->valor(),
                'dependente'          => $dependente,
                'funcionario'         => $funcionario,
                'carteira'            => $Construtor->menu_carteira->valor(),
                'salavip'             => $Construtor->menu_salavip->valor(),
                'ponto_mais_acao'     => $Construtor->menu_ponto_mais_acao->valor(),
                'sair'                => $Construtor->menu_sair->valor()
            ],
            'campos_primeiro_acesso'  => $camposPrimeiroAcesso,
            'input_grupo'             => [
                'label'       => $Construtor->grupo_label,
                'placeholder' => $Construtor->grupo_placeholder
            ],
            'texto_login_usuario'     => $Construtor->texto_login_usuario,
            'texto_login_dependente'  => $Construtor->texto_login_dependente,
            'texto_login_funcionario' => $Construtor->texto_login_funcionario,
            'tipo_ativacao'           => $Construtor->tipo_ativacao->indice(),
            'ativacao_tipo'           => $Construtor->tipo_ativacao->indice(),
            'ativacao_status'         => $dependente == 'sim' || $funcionario == 'sim' || $indicarUsuario == 'sim' ? 'sim' : 'nao',
            'tipo_cargo'              => $Construtor->tipo_cargo->indice(),
            'administrado'            => $Construtor->administrado_status->valor(),
            'tela_login'              => $Construtor->tela_login->valor(),
            'login_status'            => $Construtor->tela_login->valor(),
            'login_escolha_status'    => $api == 'sim' && ($dependente == 'sim' || $funcionario == 'sim') ? 'sim' : 'nao',
            'recuperar_senha_status'  => $api == 'nao' || $dependente == 'sim' || $indicarUsuario == 'sim' ? 'sim' : 'nao',
            'botao_senha_status'      => $Construtor->botao_senha_status->valor(),
            'botao_senha_tipo'        => $Construtor->botao_senha_tipo->valido()
                ? $Construtor->botao_senha_tipo->indice()
                : '',
            'botao_senha_link'        => !empty($Construtor->botao_senha_link)
                ? $Construtor->botao_senha_link
                : '',
            'botao_cadastro_status'   => $Construtor->botao_cadastro_status->valor(),
            'botao_cadastro_tipo'     => $Construtor->botao_cadastro_tipo->valido()
                ? $Construtor->botao_cadastro_tipo->indice()
                : '',
            'botao_cadastro_link'     => !empty($Construtor->botao_cadastro_link) ? $Construtor->botao_cadastro_link : '',
            'botao_ativar_status'     => $Construtor->botao_ativar_status->valor(),
            'botao_ativar_tipo'       => $Construtor->botao_ativar_tipo->valido()
                ? $Construtor->botao_ativar_tipo->indice()
                : '',
            'botao_ativar_link'       => !empty($Construtor->botao_ativar_link) ? $Construtor->botao_ativar_link : '',
            'chat'                    => $Construtor->chat_status->valor(),
            'api'                     => $api
        ];
    }
}
