<?php

namespace Status;

interface StatusInterface
{
    /**
     * Pega um array com a lista de valores válidos no formato indice => nome
     *
     * @return array
     */
    public function select(?string $titulo): array;

    /**
     * Pega a lista de cores
     *
     * @return array
     */
    public function cor(): array;

    /**
     * Pega a lista de números
     *
     * @return array
     */
    public function listarNumero(): array;

    /**
     * Pega o número que deve ser salvo no banco
     *
     * @return int
     */
    public function numero(null|string|int $valor = null): ?int;

    /**
     * Pega o indice que deve ser mostrado para o usuário
     *
     * @return string
     */
    public function nome(null|string|int $valor = null): ?string;

    /**
     * Pega o nome que deve ser mostrado para o usuário
     *
     * @return string
     */
    public function indice(null|string|int $valor = null): ?string;

    /**
     * Valida se o indice/valor é valido
     *
     * @return bool
     */
    public function valido(null|string|int $valor = null): bool;

    /**
     * Valida se o valor está vazio
     *
     * @return bool
     */
    public function vazio(): bool;
}
