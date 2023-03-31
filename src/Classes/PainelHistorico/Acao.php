<?php

namespace System\Classes\PainelHistorico;

use Status\Status;

final class Acao extends Status
{
    public const TELEFONE = 'telefone';
    public const EMAIL = 'email';
    public const WHATSAPP = 'whatsapp';
    public const OUTRO = 'outro';
    public const SALVAR = 'salvar';
    public const EDITAR = 'editar';
    public const DELETAR = 'deletar';
    public const MENSAGEM = 'mensagem';
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
