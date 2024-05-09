<?php

namespace Modules;

use Modules\Trait\ValidarTrait;
use Modules\Trait\ValorRealTrait;

final class ArquivoPrivadoLista implements ModuleInterface
{
    use ValidarTrait;
    use ValorRealTrait;

    private array $id = [];
    private array $link = [];

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
     * @param null|string|array $valor Valor podendo ser string sendo sim ou nao ou um int com
     *                                 valor 1 para sim ou vazio para nao
     */
    public function __construct(
        private null|string|array $valor = null,
    ) {
        $this->valor_real = $valor;
        $valorInicial = $valor;
        $this->valor = jsonDecode($this->valor, true, true);
        if (empty($this->valor)) {
            $this->vazio = true;
            $this->valido = false;
            $this->id = [];
            $this->link = [];
            return;
        } elseif (!empty($valorInicial) && empty($this->valor)) {
            $this->valido = false;
            $this->id = [];
            $this->link = [];
        }

        $this->setarValor();
    }

    private function setarValor(): void
    {
        $id = [];
        $link = [];
        foreach ($this->valor as $item) {
            $id[] = arquivoPrivadoId($item);
            $link[] = arquivoPrivado($item);
        }
        $this->id = $id;
        $this->link = $link;
    }

    // doc
    /**
     * Pegar a lista de id
     *
     * @return array
     */
    public function id(): array
    {
        return $this->id;
    }

    // doc
    /**
     * Pegar a lista de link
     *
     * @return array
     */
    public function link(): array
    {
        return $this->link;
    }

    // doc
    /**
     * Pegar o valor do botão
     *
     * @return array
     */
    public function valor(): array
    {
        return $this->link;
    }
}
