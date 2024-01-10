<?php

namespace App\Models\Api\SiteConfig;

use ORM\Entity;
use Modules\Email;
use Modules\Telefone;
use App\Classes\Geral\Status;
use App\Classes\SiteConfig\Template;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class ConfigEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SITE_CONFIG;
    protected array $ormBuscar = [
        'titulo_painel', 'titulo', 'descricao', 'template', 'contato_telefone', 'contato_celular',
        'contato_whatsapp', 'contato_email', 'contato_endereco', 'mapa_imagem', 'mapa_link',
        'cor_principal', 'rede_youtube', 'rede_facebook', 'rede_instagram', 'rede_x', 'logo_principal',
        'favicon', 'link_site', 'status'
    ];
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa'
    ];
    protected array $ormSalvar = [
        'titulo_painel', 'titulo', 'descricao', 'template', 'contato_telefone',
        'contato_celular', 'contato_whatsapp', 'contato_email', 'contato_endereco', 'mapa_imagem', 'mapa_link',
        'cor_principal', 'rede_youtube', 'rede_facebook', 'rede_instagram', 'rede_x', 'logo_principal',
        'favicon', 'link_site', 'status'
    ];
    protected string $ormValidarSalvar = '
        titulo|Título|obrigatorio|vazio
        titulo_painel|Título do painel|obrigatorio|vazio
        descricao|Descrição|obrigatorio|vazio
        template|Template|obrigatorio|vazio
        contato_telefone|Telefone de contato|valido
        contato_celular|Celular de contato|valido
        contato_whatsapp|WhatsApp de contato|valido
        contato_email|E-mail de contato|valido
        cor_principal|Cor principal|obrigatorio|vazio
        logo_principal|Logo principal|obrigatorio|vazio
        favicon|Favicon|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';
    private int $idEmpresa;
    public string $empresa;
    public string $titulo_painel;
    public string $titulo;
    public string $descricao;
    public Template $template;
    public Telefone $contato_telefone;
    public Telefone $contato_celular;
    public Telefone $contato_whatsapp;
    public Email $contato_email;
    public string $contato_endereco;
    public string $mapa_imagem;
    public string $mapa_link;
    public string $cor_principal;
    public string $rede_youtube;
    public string $rede_facebook;
    public string $rede_instagram;
    public string $rede_x;
    public string $logo_principal;
    public string $link_site;
    public string $favicon;
    public Status $status;

    protected function regraPosBuscar()
    {
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
