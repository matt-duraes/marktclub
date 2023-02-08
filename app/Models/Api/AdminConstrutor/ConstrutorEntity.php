<?php

namespace App\Models\Api\AdminConstrutor;

use ORM\Entity;

final class ConstrutorEntity extends Entity
{
    protected string $_tabela = TABELA_CONSTRUTOR_NOVO;
    protected array $_buscar = [
        'id_admin_empresa' => 'empresa',
        'link_clube' => 'link_site',
        'logo', 'titulo', 'cor', 'classe_login'
    ];

    public string $titulo;
    public string $link_clube;
    public string $link_logo;
    public string $cor;
    public int $id_admin_empresa;
    public string $logo;

    protected function regraPosBuscar()
    {
        $this->link_logo = LINK_ARQUIVO . '/construtor/' . $this->logo;
    }
}
