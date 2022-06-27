<?php

namespace App\Models\Api\AdminConstrutor;

use ORM\Entity;

final class ConstrutorEntity extends Entity
{
    protected string $_tabela = TABELA_CONSTRUTOR_NOVO;
    protected array $_buscar = [
        'id_admin_empresa' => 'empresa',
        'link_clube' => 'link_site',
        'logo', 'titulo', 'cor'
    ];
}
