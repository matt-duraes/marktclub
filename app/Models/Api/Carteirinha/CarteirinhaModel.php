<?php

namespace App\Models\Api\Carteirinha;

use App\Classes\Carteirinha\Ordem;
use App\Classes\Carteirinha\Status;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CarteirinhaModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_CARTEIRINHA;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $empresa
     * @param Status      $status
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $empresa = null,
        private readonly Status $status = new Status()
    ) {
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dados = $this
            ->campo([
                'uuid', 'bg_frente', 'bg_fundo', 'titulo', 'nome', 'cpf', 'matricula', 'data_nascimento',
                'status', 'data_criacao', 'data_atualizacao', 'estado'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'uuid'
            ], 'empresa')
            ->read();

        $dados->lista = $this->montarRetorno($dados->lista);
        return $dados;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        if (!empty($this->empresa)) {
            $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $where[] = ['id_admin_empresa', $ormHelper->pegarIdPeloUuid($this->empresa)];
        }
        return $where;
    }

    /**
     * @param array $carteirinhas
     *
     * @return array
     */
    private function montarRetorno(array $carteirinhas): array
    {
        if (empty($carteirinhas)) {
            return $carteirinhas;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($carteirinhas as $r) {
            $retorno[] = [
                'id'               => $r->uuid,
                'titulo'           => $r->titulo,
                'empresa'          => $r->empresa_uuid,
                'bg_frente'        => arquivoPrivado($r->bg_frente),
                'bg_fundo'         => arquivoPrivado($r->bg_fundo),
                'nome'             => $this->pegarValorBotao($r->nome),
                'cpf'              => $this->pegarValorBotao($r->cpf),
                'matricula'        => $this->pegarValorBotao($r->matricula),
                'data_nascimento'  => $this->pegarValorBotao($r->data_nascimento),
                'estado'           => $this->pegarValorBotao($r->estado),
                'data_validade'    => dataAdicionar(date('Y-m-d'), 30, 'dias'),
                'data_criacao'     => $r->data_criacao,
                'data_atualizacao' => $r->data_atualizacao,
                'status'           => $Status->indice($r->status),
            ];
        }
        return $retorno;
    }

    private function pegarValorBotao($valor)
    {
        return (new Botao($valor ? 'sim' : 'nao'))->valor();
    }
}
