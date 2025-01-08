<?php

namespace App\Classes\ComercialEmpresa;

use Status\Status as StatusStatus;

final class ProspeccaoStatus extends StatusStatus
{
    public const INDICADO = 'indicado';
    public const PESQUISA = 'pesquisa';
    public const APRESENTACAO = 'apresentacao';
    public const NEGOCIACAO = 'negociacao';
    public const AVALIACAO = 'avaliacao';
    public const MINUTA = 'minuta';
    public const CONCLUIDO = 'concluido';
    public const SEM_RESULTADO = 'sem-resultado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::INDICADO      => 'Indicado',
            self::PESQUISA      => 'Pesquisa',
            self::APRESENTACAO  => 'Apresentação',
            self::NEGOCIACAO    => 'Nogociação',
            self::AVALIACAO     => 'Em avaliação',
            self::MINUTA        => 'Minuta enviada',
            self::CONCLUIDO     => 'Concluido',
            self::SEM_RESULTADO => 'Sem resultado',
        ], cor: [
            self::INDICADO      => 'vermelho',
            self::PESQUISA      => 'azul',
            self::APRESENTACAO  => 'azul',
            self::NEGOCIACAO    => 'azul',
            self::AVALIACAO     => 'azul',
            self::MINUTA        => 'azul',
            self::CONCLUIDO     => 'verde',
            self::SEM_RESULTADO => 'preto',
        ]);
    }
}
