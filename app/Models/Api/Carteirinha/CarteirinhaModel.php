<?php

namespace App\Models\Api\Carteirinha;

use App\Classes\Carteirinha\Ordem;
use App\Classes\Carteirinha\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
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
                'uuid', 'bg_frente', 'bg_fundo',
                'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod', 'nome_fantasia'
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
     * @return array
     */
    private function pegarWhereEmpresa(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = ['cod', $this->empresa];
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
        foreach ($carteirinhas as $carteirinha) {
            $retorno[] = [
                'id'               => $carteirinha->uuid,
                'empresa'          => [
                    'id'   => $carteirinha->empresa_cod,
                    'nome' => $carteirinha->empresa_nome_fantasia,
                ],
                'bg_frente'        => arquivoPublico(LINK_ARQUIVO . '/construtor', $carteirinha->bg_frente ?? ''),
                'bg_fundo'         => arquivoPublico(LINK_ARQUIVO . '/construtor', $carteirinha->bg_fundo ?? ''),
                'status'           => $Status->indice($carteirinha->status),
                'data_criacao'     => $carteirinha->data_criacao,
                'data_atualizacao' => $carteirinha->data_atualizacao
            ];
        }
        return $retorno;
    }
}
