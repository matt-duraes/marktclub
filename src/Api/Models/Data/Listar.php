<?php

namespace ApiModel\Data;

use ORM\ORM;
use stdClass;
use Where\Where;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\WhereTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class Listar extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use WhereTrait;

    protected string $ormTabela = TABELA_SISTEMA_DATA;
    public string $vinculo;
    public string $local_principal;
    public string $titulo;
    public Pagina $pagina;
    public Quantidade $quantidade;

    /**
     * @throws Excecao
     */
    public function listarDados(): array|stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'titulo', 'data_criacao'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order('id', 'DESC')
            ->read();

        if (!chaveExiste('lista', $dado)) {
            return $this->paginacaoZero();
        }

        $dado->lista = $this->montarRetorno($dado->lista);
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this);
        $Where
            ->linha('local_principal')
            ->linha('vinculo', campo: 'id_vinculo')
            ->linha('titulo', 'like%%');
        return $Where;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'data'      => $r->data_criacao,
            ];
        }
        return $retorno;
    }
}
