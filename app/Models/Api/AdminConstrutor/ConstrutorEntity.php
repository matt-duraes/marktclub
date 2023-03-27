<?php

namespace App\Models\Api\AdminConstrutor;

use ORM\Entity;
use Modules\Email;
use Modules\Telefone;

final class ConstrutorEntity extends Entity
{
    protected string $_tabela = TABELA_CONSTRUTOR_NOVO;
    protected array $_buscar = [
        'id_admin_empresa' => 'empresa',
        'link_clube' => 'link_site',
        'logo', 'titulo', 'cor', 'classe_login', 'contato_telefone', 'contato_email', 'contato_whatsapp'
    ];
    protected array $_retornoPadrao = ['id', 'link_logo', 'link_logo_marktclub'];

    public string $titulo;
    public string $link_clube;
    public string $link_logo;
    public string $link_logo_marktclub;
    public string $cor;
    public int $id_admin_empresa;
    public string $logo;
    public Telefone $contato_telefone;
    public Telefone $contato_whatsapp;
    public Email $contato_email;

    protected function regraPosBuscar()
    {
        $this->link_logo = LINK_ARQUIVO . '/construtor/' . $this->logo;
        $this->link_logo_marktclub = LINK_ARQUIVO . '/construtor/a2ca966d45780803f2497bd2a77b0e3b.png';
    }
}
