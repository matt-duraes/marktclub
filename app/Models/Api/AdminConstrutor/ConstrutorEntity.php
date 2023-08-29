<?php

namespace App\Models\Api\AdminConstrutor;

use ORM\Entity;
use Modules\Botao;
use Modules\Email;
use Modules\Telefone;
use App\Classes\Geral\Status;

final class ConstrutorEntity extends Entity
{
    protected string $ormTabela = TABELA_CONSTRUTOR_CLUBE;
    protected array $ormBuscar = [
        'id_admin_empresa'    => 'empresa',
        'link_clube'          => 'link_site',
        'menu_turismo'        => 'menu_turismo_clube',
        'menu_sicoob_credito' => 'menu_credito',
        'logo', 'titulo', 'cor', 'classe_login', 'contato_telefone', 'contato_email', 'contato_whatsapp',
        'link_app_android', 'link_app_ios', 'favicon', 'header_tag', 'header_descricao', 'menu_convenio',
        'menu_convenio_mapa', 'menu_cinema', 'menu_historico', 'menu_acesso_rapido', 'contato_endereco',
        'menu_medicamento', 'menu_automovel', 'menu_saude_vitoria', 'menu_saude_amil', 'menu_saude_seguros',
        'menu_cashback', 'menu_indicacao', 'menu_cupom', 'menu_odontologia', 'menu_premium', 'menu_dependente',
        'menu_carteiria', 'menu_salavip', 'menu_faq', 'menu_como_funciona', 'api_status', 'link_login',
        'horario_atendimento', 'menu_sair', 'status'
    ];
    protected array $ormRetornoPadrao = ['id', 'link_logo', 'link_logo_marktclub'];
    protected string $favicon;
    protected string $logo;
    public string $titulo;
    public string $link_clube;
    public string $link_favicon;
    public string $link_logo;
    public string $link_logo_marktclub;
    public string $link_app_android;
    public string $link_app_ios;
    public string $link_login;
    public string $header_tag;
    public string $header_descricao;
    public string $cor;
    public int $id_admin_empresa;
    public string $horario_atendimento;
    public string $contato_endereco;
    public Telefone $contato_telefone;
    public Telefone $contato_whatsapp;
    public Email $contato_email;
    public Botao $menu_faq;
    public Botao $menu_como_funciona;
    public Botao $menu_acesso_rapido;
    public Botao $menu_convenio;
    public Botao $menu_convenio_mapa;
    public Botao $menu_cinema;
    public Botao $menu_turismo;
    public Botao $menu_historico;
    public Botao $menu_sicoob_credito;
    public Botao $menu_medicamento;
    public Botao $menu_automovel;
    public Botao $menu_saude_vitoria;
    public Botao $menu_saude_amil;
    public Botao $menu_saude_seguros;
    public Botao $menu_cashback;
    public Botao $menu_indicacao;
    public Botao $menu_cupom;
    public Botao $menu_odontologia;
    public Botao $menu_premium;
    public Botao $menu_dependente;
    public Botao $menu_carteiria;
    public Botao $menu_salavip;
    public Botao $menu_sair;
    public Botao $api_status;
    public Status $status;

    protected function regraPosBuscar()
    {
        $this->link_favicon = LINK_ARQUIVO . '/construtor/' . $this->favicon;
        $this->link_logo = LINK_ARQUIVO . '/construtor/' . $this->logo;
        $this->link_logo_marktclub = LINK_ARQUIVO . '/construtor/a2ca966d45780803f2497bd2a77b0e3b.png';
    }
}
