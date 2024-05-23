<?php

namespace App\Models\Api\SiteConfig;

use ORM\Entity;
use Modules\Botao;
use Modules\Email;
use Modules\Telefone;
use Modules\ArquivoPrivado;
use App\Classes\Geral\Status;
use App\Classes\SiteConfig\DiretoriaTipo;
use App\Classes\SiteConfig\TemplateFooter;
use App\Classes\SiteConfig\TemplateHeader;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ConfigEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SITE_CONFIG;
    protected array $ormBuscar = [
        'titulo_painel', 'titulo', 'descricao', 'contato_telefone', 'contato_celular', 'home_galeria', 'home_video',
        'contato_whatsapp', 'contato_email', 'contato_endereco', 'mapa_imagem', 'mapa_link', 'noticia_imagem',
        'cor_principal', 'rede_youtube', 'rede_facebook', 'rede_instagram', 'rede_twitter_x', 'logo_principal',
        'favicon', 'link_site', 'home_banner', 'home_noticia_principal', 'home_noticia_lista', 'contato_chat',
        'cor_texto', 'cor_header', 'cor_footer', 'rede_header', 'rede_footer', 'login_texto', 'login_link',
        'clube_link', 'home_parceiro', 'template_header', 'template_footer', 'rss', 'imagem_social', 'status',
        'imagem_header', 'altura_header', 'rede_spotify', 'rede_linkedin', 'diretoria_tipo', 'mensagem_topo'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo_painel', 'titulo', 'descricao', 'contato_telefone', 'contato_celular', 'home_galeria', 'home_video',
        'contato_whatsapp', 'contato_email', 'contato_endereco', 'mapa_imagem', 'mapa_link', 'noticia_imagem',
        'cor_principal', 'rede_youtube', 'rede_facebook', 'rede_instagram', 'rede_twitter_x', 'logo_principal',
        'favicon', 'link_site', 'home_banner', 'home_noticia_principal', 'home_noticia_lista', 'contato_chat',
        'cor_texto', 'cor_header', 'cor_footer', 'rede_header', 'rede_footer', 'login_texto', 'login_link',
        'clube_link', 'home_parceiro', 'template_header', 'template_footer', 'rss', 'imagem_social', 'status',
        'imagem_header', 'altura_header', 'rede_spotify', 'rede_linkedin', 'diretoria_tipo', 'mensagem_topo'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        titulo_painel|Título do painel|obrigatorio|vazio
        descricao|Descrição|obrigatorio|vazio
        template_header|Template do header|obrigatorio|vazio
        template_footer|Template do footer|obrigatorio|vazio
        contato_telefone|Telefone de contato|valido
        contato_celular|Celular de contato|valido
        contato_whatsapp|WhatsApp de contato|valido
        contato_email|E-mail de contato|valido
        cor_principal|Cor principal|obrigatorio|vazio
        logo_principal|Logo principal|obrigatorio|vazio
        favicon|Favicon|obrigatorio|vazio
        diretoria_tipo|Tipo da diretoria|valido
        status|Status|obrigatorio|vazio|valido
    ';
    private int $idEmpresa;
    public string $empresa;
    public string $titulo_painel;
    public string $titulo;
    public string $descricao;
    public TemplateHeader $template_header;
    public TemplateFooter $template_footer;
    public Telefone $contato_telefone;
    public Telefone $contato_celular;
    public Telefone $contato_whatsapp;
    public Email $contato_email;
    public Botao $contato_chat;
    public string $contato_endereco;
    public ArquivoPrivado $imagem_social;
    public ArquivoPrivado $mapa_imagem;
    public string $mapa_link;
    public string $cor_principal;
    public string $cor_texto;
    public string $cor_header;
    public ArquivoPrivado $imagem_header;
    public int $altura_header;
    public string $cor_footer;
    public string $rede_youtube;
    public string $rede_facebook;
    public string $rede_linkedin;
    public string $rede_instagram;
    public string $rede_twitter_x;
    public string $rede_spotify;
    public Botao $rede_header;
    public Botao $rede_footer;
    public Botao $rss;
    public ArquivoPrivado $noticia_imagem;
    public ArquivoPrivado $logo_principal;
    public array $link_site;
    public ArquivoPrivado $favicon;
    public string $login_texto;
    public string $login_link;
    public string $clube_link;
    public Botao $home_noticia_principal;
    public int $home_noticia_lista;
    public Botao $home_banner;
    public Botao $home_parceiro;
    public Botao $home_galeria;
    public Botao $home_video;
    public DiretoriaTipo $diretoria_tipo;
    public string $mensagem_topo;
    public Status $status;

    protected function regraPosBuscar()
    {
        $this->link_site = strColocarHttpsDominio($this->link_site);
    }

    protected function regraInsert()
    {
        if (!$this->propriedadeExiste('empresa') || empty($this->empresa)) {
            return;
        }
        $this->validarCampoDuplicado(campo: 'id_admin_empresa', valor: $this->idEmpresa, mensagem: 'Empresa');
    }

    protected function regraSalvar()
    {
        $this->link_site = strRemoverHttpsDominio($this->link_site);
        $this->validarCampoDuplicado('link_site', 'Link do site');
    }
}
