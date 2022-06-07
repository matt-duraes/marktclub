<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Telefone implements ModuleInterface
{
    use ValidarTrait;
    public function __toString()
    {
        return $this->telefone();
    }

    // doc
    /**
     * Modulo para telefone
     *
     * @param null|string $telefone Telefone pra o modulo
     */
    public function __construct(
        private ?string $telefone
    ) {
        if (empty($this->telefone)) {
            $this->telefone = '';
            $this->vazio = true;
            $this->valido = false;
            return;
        }

        $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
        $quantidade = mb_strlen($telefone, 'UTF-8');
        $validar = ($quantidade == 8 && in_array(substr($telefone, 0, 4), ['4004', '4003', '3003'])) ||
            ($quantidade == 11 && (in_array(substr($telefone, 0, 4), ['0800', '0300']) || substr($telefone, 2, 1) == 9)) ||
            ($quantidade == 10 && substr($telefone, 2, 1) != 9);

        if (!$validar) {
            $this->telefone = '';
            $this->valido = false;
            return;
        }

        $this->telefone = preg_replace('/[^0-9]/', '', $telefone);
    }

    // doc
    /**
     * Pega o telefone formatado
     *
     * @return string Telefone formatado
     */
    public function telefone(): string
    {
        $telefone = preg_replace('/[^0-9]/', '', $this->telefone);
        $quantidade = strlen($telefone);

        if ($quantidade == 8 && (substr($telefone, 0, 4) == '3003' || substr($telefone, 0, 4) == '4004')) {
            return substr($telefone, 0, 4) . '-' . substr($telefone, 4, 4);
        } elseif ($quantidade == 10) {
            return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 4) . '-' . substr($telefone, 6, 4);
        } elseif ($quantidade == 11 && (substr($telefone, 2, 1) == '9')) {
            return '(' . substr($telefone, 0, 2) . ') ' . substr($telefone, 2, 5) . '-' . substr($telefone, 7, 4);
        } elseif (substr($telefone, 0, 4) == '0800') {
            return substr($telefone, 0, 4) . ' ' . substr($telefone, 4, 3) . ' ' . substr($telefone, 7, 4);
        }
        return $this->telefone;
    }

    // doc
    /**
     * Pega o telefone com apenas números
     *
     * @return int Número inteiro do telefone
     */
    public function numero(): int
    {
        return $this->telefone;
    }
}
