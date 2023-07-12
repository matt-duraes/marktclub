<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Nome implements ModuleInterface
{
    use ValidarTrait;

    private string $primeiro_nome;
    private string $ultimo_sobrenome;
    private string $sobrenome;

    public function __toString()
    {
        return $this->nome;
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->nome;
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->nome;
    }

    // doc
    /**
     * Gera um modulo de nome
     *
     * @param null|string $nome Valor do nome para o modulo
     */
    public function __construct(
        private ?string $nome
    ) {
        if (empty($this->nome)) {
            $this->nome = '';
            $this->primeiro_nome = '';
            $this->ultimo_sobrenome = '';
            $this->sobrenome = '';
            $this->vazio = true;
            $this->valido = false;
            return;
        } elseif (count(explode(' ', $this->nome)) < 2) {
            $this->nome = '';
            $this->primeiro_nome = '';
            $this->ultimo_sobrenome = '';
            $this->sobrenome = '';
            $this->valido = false;
            return;
        }

        $nome = explode(' ', $this->nome);
        $primeiroNome = array_shift($nome);
        $ultimoNome = count($nome) > 0 ? end($nome) : '';

        $this->primeiro_nome = $primeiroNome;
        $this->ultimo_sobrenome = $ultimoNome;
        $this->sobrenome = count($nome) > 0 ? implode(' ', $nome) : '';
        $this->nome = $primeiroNome;
        if (!empty($this->sobrenome)) {
            $this->nome .= ' ' . $this->sobrenome;
        }
    }

    // doc
    /**
     * Pegar o valor completo do nome
     *
     * @return string Valor completo
     */
    public function nome(): string
    {
        return $this->nome;
    }

    // doc
    /**
     * Pegar o valor do primeiro nome
     *
     * @return string Valor do primeiro nome
     */
    public function primeiroNome(): string
    {
        return $this->primeiro_nome;
    }

    // doc
    /**
     * Pegar o valor do ultimo sobrenome
     *
     * @return string Valor do último sobrenome
     */
    public function ultimoSobrenome(): string
    {
        return $this->ultimo_sobrenome;
    }

    // doc
    /**
     * Pegar o valor do sobrenome
     *
     * @return string Valor do sobrenome
     */
    public function sobrenome(): string
    {
        return $this->sobrenome;
    }
}
