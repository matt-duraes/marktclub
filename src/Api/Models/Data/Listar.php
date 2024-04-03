<?php

namespace ApiModel\Data;

use ORM\ORM;
use stdClass;
use Where\Where;
use Erro\Excecao;
use Modules\Pagina;
use Helpers\OrmHelper;
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
                'uuid', 'id_usuario_equipe', 'indice', 'mensagem', 'data_criacao'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order('id', 'DESC')
            ->read();

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
            ->linha('vinculo', campo: 'id_vinculo')
            ->linha('indice')
            ->linha('mensagem', 'like%%');
        return $Where;
    }

    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        $equipeLista = [];
        $Equipe = new OrmHelper(TABELA_USUARIO_EQUIPE);
        foreach ($dado as $r) {
            if (!array_key_exists($r->id_usuario_equipe, $equipeLista)) {
                $equipeLista[$r->id_usuario_equipe] = $Equipe->pegarUuidPeloId($r->id_usuario_equipe);
            }
            $retorno[] = [
                'id'       => $r->uuid,
                'equipe'   => $equipeLista[$r->id_usuario_equipe],
                'indice'   => $r->indice,
                'mensagem' => $r->mensagem,
                'data'     => $r->data_criacao,
            ];
        }
        return $retorno;
    }
}
