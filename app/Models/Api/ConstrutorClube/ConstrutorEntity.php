<?php

namespace App\Models\Api\ConstrutorClube;

use ORM\Entity;
use Modules\Botao;
use Modules\Email;
use Modules\Telefone;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Classes\ConstrutorClube\TipoAtivacao;

final class ConstrutorEntity extends Entity
{
    protected string $ormTabela = TABELA_CONSTRUTOR_CLUBE;
    protected array $ormBuscar = [
        'id_admin_empresa', 'link_clube', 'link_cadastro', 'link_salavip', 'link_odontologico',
        'menu_turismo', 'menu_credito_sicoob', 'logo_principal', 'logo_secundaria', 'favicon', 'logo_footer',
        'titulo', 'contato_telefone', 'contato_email', 'contato_whatsapp', 'contato_endereco', 'contato_horario',
        'link_app_android', 'link_app_ios', 'header_tag', 'header_descricao', 'menu_loja', 'menu_saude_cnu',
        'menu_mapa', 'menu_cinema', 'menu_historico', 'menu_acesso_rapido', 'menu_saude_florianopolis', 'tela_login',
        'menu_farmacia', 'menu_automovel', 'menu_saude_vitoria', 'menu_saude_amil', 'menu_saude_seguro',
        'menu_cashback', 'menu_indicar_usuario', 'menu_indicar_loja', 'menu_ponto_mais_acao', 'menu_cupom', 'menu_odontologico',
        'menu_premium', 'menu_dependente', 'menu_carteira', 'menu_salavip', 'menu_faq', 'menu_como_funciona',
        'menu_meu_parceiro', 'api_status','link_botao_sair','link_login', 'menu_sair', 'menu_ponto_mais_acao', 'menu_primeiro_acesso', 'menu_tema',
        'menu_corrida', 'menu_show_nacional', 'menu_show_internacional', 'administrado_status', 'chat_status',
        'menu_samsung', 'cor_principal', 'cor_secundaria', 'tipo_ativacao', 'status', 'campos_primeiro_acesso', 'grupo_label',
        'grupo_placeholder', 'link_facebook', 'link_instagram', 'link_twitter', 'link_linkedin', 'link_youtube', 'link_tiktok',
    ];
    protected array $ormSalvar = [
        'id_admin_empresa', 'link_clube', 'link_cadastro', 'link_salavip', 'link_odontologico',
        'menu_turismo', 'menu_credito_sicoob', 'logo_principal', 'logo_secundaria', 'favicon', 'logo_footer',
        'titulo', 'contato_telefone', 'contato_email', 'contato_whatsapp', 'contato_endereco', 'contato_horario', 'tela_login',
        'link_app_android', 'link_app_ios', 'header_tag', 'header_descricao', 'menu_loja', 'menu_saude_cnu',
        'menu_mapa', 'menu_cinema', 'menu_historico', 'menu_acesso_rapido', 'menu_ponto_mais_acao', 'menu_saude_florianopolis',
        'menu_farmacia', 'menu_automovel', 'menu_saude_vitoria', 'menu_saude_amil', 'menu_saude_seguro',
        'menu_cashback', 'menu_indicar_usuario', 'menu_indicar_loja', 'menu_ponto_mais_acao', 'menu_cupom', 'menu_odontologico',
        'menu_carteira', 'menu_salavip', 'menu_faq', 'menu_como_funciona', 'api_status','link_botao_sair','link_login',
        'menu_premium', 'menu_dependente', 'menu_sair', 'menu_primeiro_acesso', 'menu_meu_parceiro',
        'cor_principal', 'cor_secundaria', 'menu_tema', 'administrado_status', 'chat_status',
        'menu_samsung', 'tipo_ativacao', 'status', 'campos_primeiro_acesso', 'grupo_label', 'grupo_placeholder',
        'link_facebook', 'link_instagram', 'link_twitter', 'link_linkedin', 'link_youtube', 'link_tiktok'
    ];
    protected array $ormRetornoPadrao = ['id', 'logo_principal', 'logo_marktclub'];
    private OrmHelper $ormEmpresa;
    public int $id_admin_empresa;
    public string $favicon;
    public string $logo_principal;
    public string $logo_secundaria;
    public string $logo_marktclub;
    public string $logo_footer;
    public string $titulo;
    public string $link_clube;
    public string $link_app_android;
    public string $link_app_ios;
    public string $link_botao_sair;
    public string $link_login;
    public string $link_cadastro;
    public string $link_salavip;
    public string $link_odontologico;
    public array $header_tag;
    public string $header_descricao;
    public string $cor_principal;
    public string $cor_secundaria;
    public string $contato_horario;
    public string $contato_endereco;
    public Telefone $contato_telefone;
    public Telefone $contato_whatsapp;
    public Email $contato_email;
    public Botao $tela_login;
    public Botao $menu_faq;
    public Botao $menu_como_funciona;
    public Botao $menu_acesso_rapido;
    public Botao $menu_primeiro_acesso;
    public Botao $menu_loja;
    public Botao $menu_mapa;
    public Botao $menu_cinema;
    public Botao $menu_turismo;
    public Botao $menu_historico;
    public Botao $menu_credito_sicoob;
    public Botao $menu_farmacia;
    public Botao $menu_automovel;
    public Botao $menu_saude_vitoria;
    public Botao $menu_saude_amil;
    public Botao $menu_saude_seguro;
    public Botao $menu_saude_cnu;
    public Botao $menu_saude_florianopolis;
    public Botao $menu_cashback;
    public Botao $menu_indicar_usuario;
    public Botao $menu_indicar_loja;
    public Botao $menu_meu_parceiro;
    public Botao $menu_ponto_mais_acao;
    public Botao $menu_cupom;
    public Botao $menu_odontologico;
    public Botao $menu_premium;
    public Botao $menu_dependente;
    public Botao $menu_carteira;
    public Botao $menu_salavip;
    public Botao $menu_show_internacional;
    public Botao $menu_show_nacional;
    public Botao $menu_corrida;
    public Botao $menu_tema;
    public Botao $menu_samsung;
    public Botao $menu_sair;
    public Botao $api_status;
    public Botao $chat_status;
    public Botao $administrado_status;
    public TipoAtivacao $tipo_ativacao;
    public Status $status;
    public string $empresa;
    public array $campos_primeiro_acesso;
    public string $grupo_label;
    public string $grupo_placeholder;
    public Botao $copiar_padrao;
    public string $link_facebook;
    public string $link_instagram;
    public string $link_twitter;
    public string $link_linkedin;
    public string $link_youtube;
    public string $link_tiktok;

    public function __construct()
    {
        parent::__construct();
        $this->ormEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
    }

    protected function regraSalvar()
    {
        if ($this->propriedadeExiste('link_clube') && !empty($this->link_clube)) {
            $this->link_clube = preg_replace('/^https?\:\/\//', '', $this->link_clube);
        }
        if ($this->propriedadeExiste('favicon') && !empty($this->favicon)) {
            $this->favicon = arquivoPrivadoId($this->favicon);
        }
        if ($this->propriedadeExiste('logo_principal') && !empty($this->logo_principal)) {
            $this->logo_principal = arquivoPrivadoId($this->logo_principal);
        }
        if ($this->propriedadeExiste('logo_secundaria') && !empty($this->logo_secundaria)) {
            $this->logo_secundaria = arquivoPrivadoId($this->logo_secundaria);
        }
        if ($this->propriedadeExiste('logo_footer') && !empty($this->logo_footer)) {
            $this->logo_footer = arquivoPrivadoId($this->logo_footer);
        }
        if ($this->propriedadeExiste('empresa') && !empty($this->empresa)) {
            $this->id_admin_empresa = $this->ormEmpresa->pegarIdPeloUuid($this->empresa);
        }
    }

    protected function regraPosBuscar()
    {
        $this->link_clube = 'https://' . $this->link_clube;
        $this->empresa = $this->ormEmpresa->pegarUuidPeloId($this->id_admin_empresa);
        $this->favicon = arquivoPrivado($this->favicon);
        $this->logo_principal = arquivoPrivado($this->logo_principal);
        $this->logo_secundaria = arquivoPrivado($this->logo_secundaria);
        $this->logo_footer = arquivoPrivado($this->logo_footer);
        $this->logo_marktclub = LINK_ARQUIVO . '/construtor/a2ca966d45780803f2497bd2a77b0e3b.png';
    }

    protected function regraPosInsert()
    {
        if ($this->copiar_padrao->valor() == Botao::SIM) {
            new CopiaClube(TOKEN['empresa']->id);
        }
    }
}
