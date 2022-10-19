<?php

namespace Painel\FaleConosco\Models;

final class FaleConoscoHelper
{
    /**
     * Padrão valor => Texto: 'novo' => 'Novo'
     */
    const STATUS_VALOR_TEXTO = ['novo' => 'Novo', 'andamento' => 'Em andamento', 'finalizado' => 'Finalizado'];
    /**
     * Padrão valor => int: 'novo' => 1
     */
    const STATUS_VALOR_INT = ['novo' => 1, 'andamento' => 2, 'finalizado' => 3];
    /**
     * Padrão int => valor: 1 => 'novo'
     */
    const STATUS_INT_VALOR = [1 => 'novo', 2 => 'andamento', 3 => 'finalizado'];
    /**
     * Padrão int => Texto: 1 => 'Novo'
     */
    const STATUS_INT_TEXTO = [1 => 'Novo', 2 => 'Em andamento', 3 => 'Finalizado'];
}
