<?php

namespace App\Models\Api\ChatbotCategoria;

use App\Classes\Geral\Status;
use ORM\Entity;

class CategoriaEntity extends Entity
{
    protected string $ormTabela = TABELA_CHATBOT_CATEGORIA;
    public array $ormSalvar = ['categoria', 'status'];
    public array $ormBuscar = ['categoria', 'status'];
    public string $categoria;
    public Status $status;
}
