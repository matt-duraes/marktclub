<?php

namespace ApiModel\Endereco;

use ORM\ORM;
use stdClass;
use Where\Where;
use Erro\Excecao;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use Modules\EnderecoEstado;
use System\Classes\Endereco\Ordem;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\WhereTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class EnderecoModel extends ORM
{
    use OrdemTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use WhereTrait;

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

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): array|stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'cep', 'logradouro', 'complemento', 'referencia',
                'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
            ])
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

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'completo'    => $this->formataEnderecoCompleto($r),
                'cep'         => $r->cep,
                'logradouro'  => $r->logradouro,
                'numero'      => $r->numero,
                'complemento' => $r->complemento,
                'referencia'  => $r->referencia,
                'bairro'      => $r->bairro,
                'cidade'      => $r->cidade,
                'estado'      => $r->estado,
                'pais'        => $r->pais,
                'latitude'    => $r->latitude,
                'longitude'   => $r->longitude,
                'principal'   => (new Botao($r->principal))->valor()
            ];
        }
        return $retorno;
    }

    private function formataEnderecoCompleto($endereco)
    {
        $completo = $endereco->logradouro;

        if (!empty($endereco->numero)) {
            $completo .= ' ' . $endereco->numero;
        }
        if (!empty($endereco->complemento)) {
            $completo .= ' ' . $endereco->complemento;
        }
        if (!empty($endereco->referencia)) {
            $completo .= ', ' . $endereco->referencia;
        }
        if (!empty($endereco->bairro)) {
            $completo .= ', ' . $endereco->bairro;
        }
        if (!empty($endereco->cidade)) {
            $completo .= ', ' . $endereco->cidade;
        }
        if (!empty($endereco->estado)) {
            $completo .= !empty($endereco->cidade) ? '/' : ' - ';
            $completo .= $endereco->estado;
        }
        if (!empty($endereco->cep)) {
            $completo .= ' - CEP: ' . strCep($endereco->cep);
        }

        return $completo;
    }
}
