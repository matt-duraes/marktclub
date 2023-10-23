<?php

namespace App\Models\Api\Carteirinha;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Botao;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Carteirinha\Ordem;
use System\Trait\Model\OrdemTrait;
use App\Classes\Carteirinha\Status;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;
use App\Models\Api\Trait\ValidarEmpresaTrait;

class CarteirinhaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
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
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $empresa = null,
        private readonly Status $status = new Status()
    ) {
        $this->validarEmpresa();
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
                'uuid', 'bg_frente', 'bg_fundo', 'nome', 'cpf', 'matricula', 'data_nascimento',
                'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'uuid', 'nome_fantasia'
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
                'empresa'          => $r->empresa_uuid,
                'bg_frente'        => arquivoPrivado($r->bg_frente),
                'bg_fundo'         => arquivoPrivado($r->bg_fundo),
                'nome'             => (new Botao($r->nome))->valor(),
                'cpf'              => (new Botao($r->nome))->valor(),
                'matricula'        => (new Botao($r->nome))->valor(),
                'data_nascimento'  => (new Botao($r->nome))->valor(),
                'data_criacao'     => $r->data_criacao,
                'data_atualizacao' => $r->data_atualizacao,
                'status'           => $Status->indice($r->status),
            ];
        }
        return $retorno;
    }
}
