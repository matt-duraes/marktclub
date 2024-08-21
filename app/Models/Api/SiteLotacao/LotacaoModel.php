<?php

namespace App\Models\Api\SiteLotacao;

use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class LotacaoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $ormTabela = TABELA_SITE_LOTACAO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param string|null $empresa
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly ?string $empresa = null,
        private readonly Status $status = new Status()
    ) {
        $this->validarRequest();
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $lotacao = $this
            ->campo([
                'uuid', 'slug', 'titulo', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'uuid', 'nome_fantasia'
            ], 'empresa')
            ->read();
        $lotacao->lista = $this->montarRetorno($lotacao->lista);
        return $lotacao;
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
     * @param array $lotacoes
     *
     * @return array
     */
    private function montarRetorno(array $lotacoes): array
    {
        if (empty($lotacoes)) {
            return $lotacoes;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($lotacoes as $lotacao) {
            $retorno[] = [
                'id'               => $lotacao->uuid,
                'empresa'          => [
                    'id'   => $lotacao->empresa_uuid,
                    'nome' => $lotacao->empresa_nome_fantasia
                ],
                'slug'             => $lotacao->slug,
                'titulo'           => $lotacao->titulo,
                'status'           => $Status->indice($lotacao->status),
                'data_criacao'     => $lotacao->data_criacao,
                'data_atualizacao' => $lotacao->data_atualizacao
            ];
        }
        return $retorno;
    }
}
