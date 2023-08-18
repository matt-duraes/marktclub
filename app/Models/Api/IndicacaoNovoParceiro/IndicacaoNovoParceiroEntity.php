<?php

namespace App\Models\Api\IndicacaoNovoParceiro;

use App\Classes\IndicacaoNovoParceiro\Status;
use Modules\Email;
use Modules\Nome;
use Modules\Telefone;
use ORM\Entity;

class IndicacaoNovoParceiroEntity extends Entity
{
    protected string $ormTabela = TABELA_INDICACAO_NOVO_PARCEIRO;
    public Nome $nome_indicado;
    public Email $email_indicado;
    public Telefone $telefone_indicado;
    public Status $status;
    public string $mensagem;
    protected array $ormSalvar = ['nome_indicado', 'telefone_indicado', 'email_indicado', 'mensagem', 'status'];
    protected array $ormBuscar = ['uuid', 'nome_indicado', 'telefone_indicado', 'email_indicado', 'mensagem', 'status'];
    protected string $ormValidarSalvar = '
        nome_indicado|Nome|obrigatorio|vazio|valido
        telefone_indicado|Telefone|obrigatorio|vazio|valido
        email_indicado|Email|obrigatorio|vazio|valido
        mensagem|Mensagem|obrigatorio|vazio
        status|Status|obrigatorio|vazio|valido
    ';

    public function __construct()
    {
        parent::__construct();
    }
}
