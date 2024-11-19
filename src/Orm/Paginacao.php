<?php

namespace ORM;

use stdClass;

abstract class Paginacao extends ORM
{
    protected stdClass $busca;
    public stdClass $retorno;

    public function __construct()
    {
        parent::__construct();
        $this->iniciarModel();
        $this->validarWhere();
        $this->listarDado();
        if (!$this->validarBusca()) {
            return;
        }
        $this->montarRetorno();
    }

    private function iniciarModel()
    {
        $this->retorno = $this->paginacaoZero();
        $this->busca = (object)[];
    }

    protected function validarWhere(): void
    {
    }

    abstract protected function listarDado(): void;

    private function validarBusca(): bool
    {
        return !vazio($this->busca) && !existeErro($this->busca, 'lista') && !empty($this->busca->lista);
    }

    abstract protected function montarRetorno(): void;
}
