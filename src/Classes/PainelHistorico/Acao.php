<?php

namespace System\Classes\PainelHistorico;

use Status\Status;

final class Acao extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            'telefone' => 'Telefone',
            'email' => 'E-mail',
            'whatsapp' => 'whatsapp',
            'outro' => 'Outro',
            'salvar' => 'Salvar',
            'editar' => 'Editar',
            'deletar' => 'Deletar',
            'mensagem' => 'Mensagem'
        ]);
    }
}
