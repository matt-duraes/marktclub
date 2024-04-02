<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class ArquivoPrivado implements ModuleInterface
{
    use ValidarTrait;

    private string $id = '';
    private string $link = '';

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->id;
    }

    // doc
    /**
     * Gera um modulo de botão
     *
     * @param null|string $valor Valor podendo ser string sendo sim ou nao ou um int com
     *                           valor 1 para sim ou vazio para nao
     */
    public function __construct(
        private null|string $valor = null,
    ) {
        if (empty($valor)) {
            $this->vazio = true;
            $this->valido = false;
            $this->id = '';
            $this->link = '';
            return;
        } elseif (!validarUuid($valor, false) && !validarUrl($valor)) {
            $this->valido = false;
            $this->id = '';
            $this->link = '';
            return;
        }

        $this->setarValor();
    }

    private function setarValor(): void
    {
        $this->id = arquivoPrivadoId($this->valor);
        $this->link = arquivoPrivado($this->valor);
    }

    // doc
    /**
     * Pegar a lista de id
     *
     * @return string
     */
    public function id(): string
    {
        return $this->id;
    }

    // doc
    /**
     * Pegar a lista de link
     *
     * @return string
     */
    public function link(): string
    {
        return $this->link;
    }

    // doc
    /**
     * Pegar o valor do botão
     *
     * @return string
     */
    public function valor(): string
    {
        return $this->link;
    }
}
