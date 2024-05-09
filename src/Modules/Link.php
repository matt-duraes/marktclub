<?php

namespace Modules;

use Modules\Trait\ValidarTrait;
use Modules\Trait\ValorRealTrait;

class Link implements ModuleInterface
{
    use ValidarTrait;
    use ValorRealTrait;

    /**
     * Modúlo de Links
     *
     * @param string|null $link String do link
     */
    public function __construct(
        private ?string $link = null
    ) {
        $this->valor_real = $link;
        if (empty($this->link)) {
            $this->vazio = true;
            $this->valido = false;
            $this->link = '';
            return;
        } elseif (!filter_var($this->link, FILTER_VALIDATE_URL)) {
            $this->valido = false;
            $this->link = '';
            return;
        }
        $this->link = mb_strtolower($this->link, 'UTF-8');
    }

    /**
     * @return string|null Link
     */
    public function valor(): ?string
    {
        return $this->link;
    }

    /**
     * Link para o Banco de Dados
     *
     * @return string Link
     */
    public function banco(): string
    {
        return ($this->link === null) ? '' : $this->link;
    }

    /**
     * @return string Link
     */
    public function __toString(): string
    {
        return $this->link;
    }
}
