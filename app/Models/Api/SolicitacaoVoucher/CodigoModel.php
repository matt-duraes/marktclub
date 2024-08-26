<?php

namespace App\Models\Api\SolicitacaoVoucher;

use App\Classes\SolicitacaoCodigo\Ordem;
use App\Classes\SolicitacaoCodigo\Status;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class CodigoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_CODIGO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $empresa
     * @param string|null $usuario
     * @param string|null $parceiro
     * @param Data        $dataEmissao
     * @param Data        $dataVencimento
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $empresa = null,
        private readonly ?string $usuario = null,
        private readonly ?string $parceiro = null,
        private readonly Data $dataEmissao = new Data(),
        private readonly Data $dataVencimento = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página não está no formato válido.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade não está no formato válido.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem não está no formato válido.');
        }
        if (!$this->dataEmissao->vazio() && !$this->dataEmissao->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de Emissão não está no formato válido.');
        }
        if (!$this->dataVencimento->vazio() && !$this->dataVencimento->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de Vencimento não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return object
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $solicitacoes = $this
            ->campo([
                'uuid', 'id_admin_empresa', 'id_usuario_cliente', 'id_parceiro_loja',
                'codigo', 'data_emissao', 'data_vencimento', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->where($this->pegarWhere(), false)
            ->read();
        $solicitacoes->lista = $this->montarRetorno($solicitacoes->lista);
        return $solicitacoes;
    }

    /**
     * @return array
     * @throws Excecao
     */
    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        if (!empty($this->empresa)) {
            $where[] = ['id_admin_empresa', $this->pegarEmpresa($this->empresa)['id']];
        }

        if (!empty($this->parceiro)) {
            $where[] = ['id_parceiro_loja', $this->pegarParceiro($this->parceiro)['id']];
        }

        if ($this->dataEmissao->valido()) {
            $where[] = ['data_emissao', $this->dataEmissao->date()];
        }

        if ($this->dataVencimento->valido()) {
            $where[] = ['data_vencimento', $this->dataVencimento->date()];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        return $where;
    }

    /**
     * @param string|int|null $id
     *
     * @return array
     * @throws Excecao
     */
    private function pegarEmpresa(string|int|null $id): array
    {
        $empty = [
            'id'            => '',
            'nome_fantasia' => ''
        ];
        if (empty($id)) {
            return $empty;
        }

        $OrmHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $where = validarUuid($id, false) ? ['uuid', $id] : ['id', $id];
        $empresa = $OrmHelper->pegarUltimoRegistro($where, ['id', 'nome_fantasia']);

        if (empty($empresa)) {
            return $empty;
        }
        return $empresa;
    }

    /**
     * @param string|int|null $id
     *
     * @return array
     * @throws Excecao
     */
    private function pegarParceiro(string|int|null $id): array
    {
        $empty = [
            'id'     => '',
            'titulo' => ''
        ];
        if (empty($id)) {
            return $empty;
        }

        $OrmHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
        $where = validarUuid($id, false) ? ['uuid', $id] : ['id', $id];
        $parceiro = $OrmHelper->pegarUltimoRegistro($where, ['id', 'titulo']);

        if (empty($parceiro)) {
            return $empty;
        }
        return $parceiro;
    }

    /**
     * @param array $solicitacoes
     *
     * @return array
     * @throws Excecao
     */
    protected function montarRetorno(array $solicitacoes): array
    {
        if (empty($solicitacoes)) {
            return $solicitacoes;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($solicitacoes as $solicitacao) {
            $retorno[] = [
                'id'               => $solicitacao->uuid,
                'empresa'          => [
                    'nome' => $this->pegarEmpresa($solicitacao->id_admin_empresa)['nome_fantasia']
                ],
                'usuario'          => [
                    'nome' => $this->pegarUsuario($solicitacao->id_usuario_cliente)['nome']
                ],
                'parceiro'         => [
                    'nome' => $this->pegarParceiro($solicitacao->id_parceiro_loja)['titulo']
                ],
                'codigo'           => $solicitacao->codigo,
                'data_emissao'     => $solicitacao->data_emissao,
                'data_vencimento'  => $solicitacao->data_vencimento,
                'status'           => $Status->indice($solicitacao->status),
                'data_criacao'     => $solicitacao->data_criacao,
                'data_atualizacao' => $solicitacao->data_atualizacao
            ];
        }
        return $retorno;
    }

    /**
     * @param string|int|null $id
     *
     * @return array
     * @throws Excecao
     */
    private function pegarUsuario(string|int|null $id): array
    {
        $empty = [
            'id'   => '',
            'nome' => ''
        ];
        if (empty($id)) {
            return $empty;
        }

        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $where = validarUuid($id, false) ? ['uuid', $id] : ['id', $id];
        $usuario = $OrmHelper->pegarUltimoRegistro($where, ['id', 'nome']);

        if (empty($usuario)) {
            return $empty;
        }
        return $usuario;
    }
}
