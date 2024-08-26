<?php

namespace App\Middlewares\Site;

use Helpers\ApiHelper;
use Helpers\UserAgentHelper;

final class ClubeMiddleware extends ApiHelper
{
    private string $id;

    public function __construct()
    {
        parent::__construct('construtor_clube:buscar');
        $this->id = env('CONSTRUTOR_VERSAO', '');
    }

    public function buscar(): bool
    {
        $this->buscarDispositivo();
        $this->montarDispositivo();
        $this->buscarClube();
        $this->montarDefine();
        return true;
    }

    private function buscarDispositivo()
    {
        if (sessaoExiste('DISPOSITIVO_' . $this->id) && sessaoExiste('DISPOSITIVO')) {
            return;
        }

        $dispositivo = (new UserAgentHelper());
        sessao('DISPOSITIVO_' . $this->id, true);
        sessao('DISPOSITIVO', (object)[
            'tipo'      => $dispositivo->dispositivo(),
            'mobile'    => $dispositivo->mobile(),
            'navegador' => $dispositivo->navegador(),
            'os'        => $dispositivo->os(),
            'tablet'    => $dispositivo->tablet(),
            'versao'    => $dispositivo->versao(),
        ]);
    }

    private function montarDispositivo()
    {
        $dispositivo = sessao('DISPOSITIVO');
        define('DISPOSITIVO_TIPO', $dispositivo->tipo);
        define('DISPOSITIVO_MOBILE', $dispositivo->mobile);
        define('DISPOSITIVO_NAVEGADOR', $dispositivo->navegador);
        define('DISPOSITIVO_OS', $dispositivo->os);
        define('DISPOSITIVO_TABLET', $dispositivo->tablet);
        define('DISPOSITIVO_VERSAO', $dispositivo->versao);
        define('DISPOSITIVO_CRHOME', strcasecmp(DISPOSITIVO_NAVEGADOR, 'Chrome') == 0);
        define('DISPOSITIVO_ANDROID', strcasecmp(DISPOSITIVO_OS, 'Android') == 0);
        define('DISPOSITIVO_IOS', strcasecmp(DISPOSITIVO_OS, 'Ios') == 0);
    }

    private function buscarClube()
    {
        if (sessaoExiste('CLUBE_' . $this->id) && sessaoExiste('CLUBE') && eProducao()) {
            return;
        }
        $host = preg_replace('/^http(s)?\:\/\/(www.)?/', '', LINK);
        if (eLocalhost()) {
            $host = explode(':', $host)[0];
        }

        $dado = $this
            ->validar(status: 404)
            ->get('/construtor-clube/clube/' . $host)
            ->object();

        sessao('CLUBE_' . $this->id, true);
        sessao('CLUBE', $this->montarClube($dado->dado));
    }

    private function montarClube($dado)
    {
        $dado->contato_telefone = strTelefone($dado->contato_telefone);
        $dado->contato_whatsapp = strTelefone($dado->contato_whatsapp);
        $menu = [];
        foreach ($dado->menu as $ind => $val) {
            $menu[$ind] = $val == 'sim';
        }
        $dado->menu = (object)$menu;
        $dado->api = $dado->api == 'sim';
        $dado->administrado = $dado->administrado == 'sim';
        $dado->chat = $dado->chat == 'sim';
        $dado->tela_login = $dado->tela_login == 'sim';
        return $dado;
    }

    private function montarDefine()
    {
        $clube = sessao('CLUBE');
        define('CLUBE_LOGO_PRINCIPAL', $clube->logo_principal);
        define('CLUBE_LOGO_SECUNDARIA', !empty($clube->logo_secundaria) ? $clube->logo_secundaria : $clube->logo_principal);
        define('CLUBE_LOGO_CLASSE', empty($clube->logo_secundaria) ? 'cor_fundo' : '');
        define('CLUBE_FAVICON', $clube->favicon);
        define('CLUBE_LOGO_FOOTER', $clube->logo_footer);
        define('CLUBE_TITULO', $clube->titulo);
        define('CLUBE_ID', $clube->id);
        define('CLUBE_COR_PRINCIPAL', $clube->cor_principal);
        define('CLUBE_COR_SECUNDARIA', $clube->cor_secundaria);
        define('CLUBE_EMPRESA', $clube->empresa);

        define('HEADER_TAG', is_array($clube->header_tag) ? implode(', ', $clube->header_tag) : '');
        define('HEADER_DESCRICAO', $clube->header_descricao);

        define('CLUBE_FINALIDADE', 1);

        define('CONTATO_TELEFONE', strTelefone($clube->contato_telefone));
        define('CONTATO_WHATSAPP', strTelefone($clube->contato_whatsapp));
        define('CONTATO_EMAIL', $clube->contato_email);
        define('CONTATO_HORARIO', $clube->contato_horario);
        define('CONTATO_ENDERECO', $clube->contato_endereco);

        $redesSociais = $clube->redes_sociais;
        define('LINK_FACEBOOK', $redesSociais->link_facebook);
        define('LINK_INSTAGRAM', $redesSociais->link_instagram);
        define('LINK_TWITTER', $redesSociais->link_twitter);
        define('LINK_LINKEDIN', $redesSociais->link_linkedin);
        define('LINK_YOUTUBE', $redesSociais->link_youtube);
        define('LINK_TIKTOK', $redesSociais->link_tiktok);

        define('API', $clube->api);
        define('CHAT', $clube->chat);
        define('ADMINISTRADO', $clube->administrado);
        define('TIPO_ATIVACAO', $clube->tipo_ativacao);
        $pagina = $clube->menu;
        define('MENU_ACESSO_RAPIDO', $pagina->acesso_rapido);
        define('MENU_PREMIUM', $pagina->premium);
        define('MENU_LOJA', $pagina->loja);
        define('MENU_MAPA', $pagina->mapa);
        define('MENU_AUTOMOVEL', $pagina->automovel);
        define('MENU_CUPOM', $pagina->cupom);
        define('MENU_CASHBACK', $pagina->cashback);
        define('MENU_CINEMA', $pagina->cinema);
        define('MENU_TURISMO', $pagina->turismo);
        define('MENU_SALAVIP', $pagina->salavip);
        define('MENU_CREDITO_SICOOB', $pagina->credito_sicoob);
        define('MENU_FARMACIA', $pagina->farmacia);
        define('MENU_SAUDE', $pagina->saude_vitoria || $pagina->saude_amil || $pagina->saude_seguro || $pagina->saude_cnu || $pagina->saude_florianopolis);
        define('MENU_SAUDE_VITORIA', $pagina->saude_vitoria);
        define('MENU_SAUDE_AMIL', $pagina->saude_amil);
        define('MENU_SAUDE_SEGURO', $pagina->saude_seguro);
        define('MENU_SAUDE_CNU', $pagina->saude_cnu);
        define('MENU_SAUDE_FLORIANOPOLIS', $pagina->saude_florianopolis);
        define('MENU_ODONTOLOGICO', $pagina->odontologico);
        define('MENU_INDICAR_LOJA', $pagina->indicar_loja);
        define('MENU_INDICAR_USUARIO', $pagina->indicar_usuario);
        define('MENU_HISTORICO', $pagina->historico);
        define('MENU_DEPENDENTE', $pagina->dependente);
        define('MENU_FUNCIONARIO', $pagina->funcionario);
        define('MENU_CARTEIRA', $pagina->carteira);
        define('MENU_SAMSUNG', $pagina->samsung);
        define('MENU_CORRIDA', $pagina->corrida);
        define('MENU_SHOW_NACIONAL', $pagina->show_nacional);
        define('MENU_SHOW_INTERNACIONAL', $pagina->show_internacional);
        define('MENU_PRIMEIRO_ACESSO', $pagina->primeiro_acesso);
        define('MENU_FAQ', $pagina->faq);
        define('MENU_TEMA', $pagina->tema);
        define('MENU_COMO_FUNCIONA', $pagina->como_funciona);
        define('MENU_MEU_PARCEIRO', $pagina->meu_parceiro);
        define('MENU_SAIR', $pagina->sair);
        define('MENU_PERFIL', !API || MENU_DEPENDENTE || MENU_CASHBACK || MENU_INDICAR_USUARIO);
        define('MENU_PONTO_MAIS_ACAO', $pagina->ponto_mais_acao);

        define('TELA_LOGIN', $clube->tela_login);

        define('CAMPOS_PRIMEIRO_ACESSO', $clube->campos_primeiro_acesso ?? []);
        define('INPUT_GRUPO', $clube->input_grupo ?? []);

        define(
            'BOTAO_LOGIN_USUARIO',
            !empty($clube->texto_login_usuario) ? $clube->texto_login_usuario : 'Sou associado'
        );
        define(
            'BOTAO_LOGIN_DEPENDENTE',
            !empty($clube->texto_login_dependente) ? $clube->texto_login_dependente : 'Sou dependente'
        );
        define(
            'BOTAO_LOGIN_FUNCIONARIO',
            !empty($clube->texto_login_funcionario) ? $clube->texto_login_funcionario : 'Sou funcionário'
        );

        define('LINK_APP_ANDROID', $clube->link_app_android);
        define('LINK_APP_IOS', $clube->link_app_ios);
        define('LINK_BOTAO_SAIR', $clube->link_botao_sair);
        define('LINK_LOGIN', preg_replace('/\/$/', '', $clube->link_login));
        define('LINK_FUNCIONARIO', preg_replace('/\/$/', '', $clube->link_funcionario));
        define('LINK_CADASTRO', $clube->link_cadastro);
        define('LINK_ODONTOLOGICO', $clube->link_odontologico);
        define('MENU_BAIXAR_APP', !empty(LINK_APP_ANDROID) || !empty(LINK_APP_IOS));

        define('LOGIN_ESCOLHA', API || MENU_FUNCIONARIO);
    }
}
