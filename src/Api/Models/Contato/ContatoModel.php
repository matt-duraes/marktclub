<?php

namespace ApiModel\Contato;

use ORM\ORM;
use stdClass;
use Where\Where;
use Erro\Excecao;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use System\Classes\Contato\Tipo;
use System\Classes\Contato\Ordem;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\WhereTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class ContatoModel extends ORM
{
    use OrdemTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use WhereTrait;

    protected string $ormTabela = TABELA_SISTEMA_CONTATO;
    public string $vinculo;
    public string $local_principal;
    public string $local_secundario;
    public string $pesquisa;
    public Ordem $ordem;
    public Tipo $tipo;
    public Pagina $pagina;
    public Quantidade $quantidade;

    /**
     * @throws Excecao
     */
    public function listarDados(): array|stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'nome', 'cpf', 'tipo', 'valor', 'whatsapp', 'principal'
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
            ->linha('tipo')
            ->naoVazio('pesquisa', function () use ($Where) {
                $pesquisa = $this->pesquisa;
                $Where
                    ->manual([
                        'OR',
                        ['titulo', 'like', '%' . $pesquisa . '%'],
                        ['nome', 'like', '%' . $pesquisa . '%'],
                        ['cpf', 'like', '%' . $pesquisa . '%'],
                        ['valor', 'like', '%' . $pesquisa . '%'],
                    ]);
            });
        return $Where;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        $Tipo = new Tipo();
        foreach ($dado as $r) {
            $tipo = $Tipo->indice($r->tipo);
            $retorno[] = [
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'nome'      => $r->nome,
                'cpf'       => $r->cpf,
                'tipo'      => $tipo,
                'valor'     => $tipo == $Tipo::TELEFONE ? $this->montarTelefone($r->valor) : $r->valor,
                'whatsapp'  => (new Botao($r->whatsapp))->valor(),
                'principal' => (new Botao($r->principal))->valor()
            ];
        }
        return $retorno;
    }

    private function montarTelefone($telefone)
    {
        return $telefone;
    }
}
