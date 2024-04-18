<?php

namespace ApiModel\Endereco;

use ORM\ORM;
use stdClass;
use Where\Where;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use Modules\EnderecoEstado;
use System\Classes\Endereco\Ordem;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\WhereTrait;
use System\Trait\Model\PaginaTrait;
use ApiModel\Endereco\Trait\CampoTrait;
use System\Trait\Model\QuantidadeTrait;
use ApiModel\Endereco\Trait\RetornoTrait;

final class EnderecoModel extends ORM
{
    use OrdemTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use WhereTrait;
    use CampoTrait;
    use RetornoTrait;

    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    public string $vinculo;
    public string $local_principal;
    public string $local_secundario;
    public string $pais;
    public string $cidade;
    public EnderecoEstado $estado;
    public Ordem $ordem;
    public string $titulo;
    public Pagina $pagina;
    public Quantidade $quantidade;

    /**
     * @throws Excecao
     */
    public function listarDados(): array|stdClass
    {
        $dado = $this
            ->campo($this->pegarCampos())
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order($this->pegarOrdem());

        if ($this->pExiste('pagina')) {
            return $this->pegarListaComPaginacao($dado);
        }

        $dado = $dado->read();
        return $this->montarRetorno($dado);
    }

    private function pegarListaComPaginacao($dado)
    {
        $dado = $dado->pagina($this->pegarPagina(), $this->pegarQuantidade())->read();
        if (!chaveExiste('lista', $dado)) {
            return $this->paginacaoZero();
        }
        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this);
        $Where
            ->linha('local_principal')
            ->linha('local_secundario')
            ->linha('vinculo', campo: 'id_vinculo')
            ->linha('titulo', 'like%%')
            ->linha('pais')
            ->linha('cidade')
            ->linha('estado');
        return $Where;
    }
}
