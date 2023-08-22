<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Email implements ModuleInterface
{
    use ValidarTrait;

    public function __toString()
    {
        return $this->email;
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->email;
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->email;
    }

    // doc
    /**
     * Modulo para e-mail
     *
     * @param null|string $email Valor para o modulo
     */
    public function __construct(
        private ?string $email = null,
    ) {
        if (empty($this->email)) {
            $this->vazio = true;
            $this->valido = false;
            $this->email = '';
            return;
        } elseif (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            $this->valido = false;
            $this->email = '';
            return;
        }
        $this->email = mb_strtolower($this->email, 'UTF-8');
    }

    // doc
    /**
     * Pegar o email
     *
     * @return string Email do modulo
     */
    public function email(): string
    {
        return $this->email;
    }
}
