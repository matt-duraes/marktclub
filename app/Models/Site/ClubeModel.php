<?php

namespace App\Models\Site;

use Helpers\ApiHelper;

final class ClubeModel extends ApiHelper
{
    private string $id;

    public function __construct()
    {
        parent::__construct('construtor_clube:buscar');
        $this->id = env('CONSTRUTOR_VERSAO', '');
        $this->buscarClube();
        $this->montarDefine();
    }

    private function buscarClube()
    {
        if (sessaoExiste('CLUBE_' . $this->id) && sessaoExiste('CLUBE')) {
            // return;
        }
        $host = eLocalhost() ? 'clube.marktclub.com.br' : str_replace(['http://', 'https://', '/'], '', LINK);
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
        return $dado;
    }

    private function montarDefine()
    {
        $clube = sessao('CLUBE');
        define('CLUBE_LOGO', $clube->logo);
        define('CLUBE_FAVICON', $clube->favicon);
        define('CLUBE_TITULO', $clube->titulo);
        define('CLUBE_ID', $clube->id);
        define('CLUBE_COR', $clube->cor);

        define('HEADER_TAG', is_array($clube->header_tag) ? implode(', ', $clube->header_tag) : '');
        define('HEADER_DESCRICAO', $clube->header_descricao);

        define('CLUBE_FINALIDADE', 1);

        define('CONTATO_TELEFONE', strTelefone($clube->contato_telefone));
        define('CONTATO_WHATSAPP', strTelefone($clube->contato_whatsapp));
        define('CONTATO_EMAIL', $clube->contato_email);
        define('CONTATO_HORARIO', $clube->contato_horario);
        define('CONTATO_ENDERECO', $clube->contato_endereco);

        define('API', $clube->api);

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
        define('MENU_ODONTOLOGICO', $pagina->odontologico);
        define('MENU_INDICACAO', $pagina->indicacao);
        define('MENU_HISTORICO', $pagina->historico);
        define('MENU_DEPENDENTE', $pagina->dependente);
        define('MENU_CARTEIRA', $pagina->carteira);
        define('MENU_PRIMEIRO_ACESSO', $pagina->primeiro_acesso);
        define('MENU_FAQ', $pagina->faq);
        define('MENU_COMO_FUNCIONA', $pagina->como_funciona);
        define('MENU_SAIR', $pagina->sair);
        define('MENU_PERFIL', !API || MENU_DEPENDENTE || MENU_CASHBACK || MENU_INDICACAO);

        define('LINK_APP_ANDROID', $clube->link_app_android);
        define('LINK_APP_IOS', $clube->link_app_ios);
        define('MENU_BAIXAR_APP', !empty(LINK_APP_ANDROID) || !empty(LINK_APP_IOS));
    }
}
