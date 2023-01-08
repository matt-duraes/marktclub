<?php

namespace App\Classes\UploadArquivo;

use Status\Status as StatusStatus;

final class Status extends StatusStatus
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                'ativo' => 'Ativo',
                'inativo' => 'Inativo'
            ],
            cor: [
                'ativo' => 'verde',
                'inativo' => 'vermelho'
            ]
        );
    }
}
