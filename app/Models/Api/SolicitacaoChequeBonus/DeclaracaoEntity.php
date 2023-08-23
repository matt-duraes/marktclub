<?php

namespace App\Models\Api\SolicitacaoChequeBonus;

use ORM\Entity;
use App\Classes\Solicitacao\Status;

final class DeclaracaoEntity extends Entity
{
    protected string $ormTabela = TABELA_SOLICITACAO_CHEQUE_BONUS;
    public Status $status;
}
