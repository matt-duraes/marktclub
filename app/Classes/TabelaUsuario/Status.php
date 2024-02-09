<?php

namespace App\Classes\TabelaUsuario;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public const NOVO = 'novo';
    public const PROCESSANDO = 'processando';
    public const ERRO = 'erro';
    public const CONCLUIDO = 'concluido';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO        => 'Novo',
            self::PROCESSANDO => 'Processando',
            self::ERRO        => 'Erro',
            self::CONCLUIDO   => 'Concluido'
        ], [
            self::NOVO        => 'azul',
            self::PROCESSANDO => 'cinza',
            self::ERRO        => 'vermelho',
            self::CONCLUIDO   => 'verde'
        ]);
    }
}
