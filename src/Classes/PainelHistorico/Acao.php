<?php

namespace System\Classes\PainelHistorico;

use Status\Status;

final class Acao extends Status
{
    const TELEFONE = 'telefone';
    const EMAIL = 'email';
    const WHATSAPP = 'whatsapp';
    const OUTRO = 'outro';
    const SALVAR = 'salvar';
    const EDITAR = 'editar';
    const DELETAR = 'deletar';
    const MENSAGEM = 'mensagem';
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct([
            self::TELEFONE => 'Telefone',
            self::EMAIL => 'E-mail',
            self::WHATSAPP => 'whatsapp',
            self::OUTRO => 'Outro',
            self::SALVAR => 'Salvar',
            self::EDITAR => 'Editar',
            self::DELETAR => 'Deletar',
            self::MENSAGEM => 'Mensagem'
        ]);
    }
}
