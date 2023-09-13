<?php

namespace App\Models\Api\ChatbotPerguntas;

use App\Classes\Geral\Status;
use Helpers\OrmHelper;
use ORM\Entity;

class PerguntasEntity extends Entity
{
    protected string $ormTabela = TABELA_CHATBOT_PERGUNTAS;
    public array $ormSalvar = ['categoria', 'pergunta', 'resposta', 'status'];
    public array $ormBuscar = ['categoria', 'pergunta', 'resposta', 'status'];
    public string $categoria;
    public string $pergunta;
    public string $resposta;
    public Status $status;
}
