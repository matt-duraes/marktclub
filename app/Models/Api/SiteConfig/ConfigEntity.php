<?php

namespace App\Models\Api\SiteConfig;

use ORM\Entity;
use Modules\Botao;
use Modules\Email;
use Modules\Telefone;
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
        'clube_link', 'home_parceiro', 'template_header', 'template_footer', 'rss', 'imagem_social',
        'diretoria_tipo', 'status'
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
        'clube_link', 'home_parceiro', 'template_header', 'template_footer', 'rss', 'imagem_social',
        'diretoria_tipo', 'status'
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
    public string $imagem_social;
    public string $mapa_imagem;
    public string $mapa_link;
    public string $cor_principal;
    public string $cor_texto;
    public string $cor_header;
    public string $cor_footer;
    public string $rede_youtube;
    public string $rede_facebook;
    public string $rede_instagram;
    public string $rede_twitter_x;
    public Botao $rede_header;
    public Botao $rede_footer;
    public Botao $rss;
    public string $noticia_imagem;
    public string $logo_principal;
    public string $link_site;
    public string $favicon;
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
    public Status $status;

    protected function regraPosBuscar()
    {
        $this->imagem_social = arquivoPrivado($this->imagem_social);
        $this->noticia_imagem = arquivoPrivado($this->noticia_imagem);
        $this->mapa_imagem = arquivoPrivado($this->mapa_imagem);
        $this->logo_principal = arquivoPrivado($this->logo_principal);
        $this->favicon = arquivoPrivado($this->favicon);
        $this->link_site = 'https://' . strDominio($this->link_site);
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
        $this->mapa_imagem = arquivoPrivadoId($this->mapa_imagem);
        $this->logo_principal = arquivoPrivadoId($this->logo_principal);
        $this->favicon = arquivoPrivadoId($this->favicon);
        $this->link_site = strDominio($this->link_site);

        $this->validarCampoDuplicado('link_site', 'Link do site');
    }
}
