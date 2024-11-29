<?php

namespace ORM;

use stdClass;
use Where\Where;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

abstract class Paginacao extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected array $ormBuscar;
    private array $ormBuscarFinal;
    protected stdClass $ormResultado;
    protected bool $ormWhereObrigatorio = true;
    public stdClass $retorno;

    public function __construct()
    {
        parent::__construct();
        $this->iniciarModel();
        $this->ormMontarCampoBusca();
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
        $this->ormResultado = (object)[];
    }

    private function ormMontarCampoBusca()
    {
        if (!$this->pExiste('ormBuscar')) {
            return;
        }
        $novo = [];
        foreach ($this->ormBuscar as $ind => $val) {
            $ind = is_int($ind) ? $val : $ind;
            $novo[$ind] = $val;
        }
        $this->ormBuscarFinal = $novo;
    }

    protected function validarWhere(): void
    {
        return;
    }

    protected function pegarWhere(): Where
    {
        return new Where($this);
    }

    protected function listarDado(): void
    {
        $campo = $this->ormBuscarFinal;
        if (empty($campo)) {
            return;
        }
        $this->ormResultado = $this
            ->campo(array_keys($campo))
            ->where($this->pegarWhere(), obrigatorio: $this->ormWhereObrigatorio)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();
    }

    private function validarBusca(): bool
    {
        return !vazio($this->ormResultado) && !existeErro($this->ormResultado, 'lista') && !empty($this->busca->lista);
    }

    protected function montarRetorno(): void
    {
        $campo = $this->ormBuscarFinal;
        if (empty($campo)) {
            return;
        }
        $resultado = $this->ormResultado;
        $retorno = [];
        foreach ($resultado->lista as $r) {
            $item = [];
            foreach ($campo as $banco => $real) {
                $item[$real] = $this->valor($r->$banco, false, false);
            }
            $retorno[] = $item;
        }
        $resultado->lista = $real;
        $this->retorno = $resultado;
    }
}
