<?php

namespace App\Models\Api\EnqueteSatisfacao;

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Ordem;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Status;
use App\Classes\EnqueteSatisfacao\Suporte;
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

class EnqueteModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_ENQUETE_SATISFACAO;

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
        private readonly Status $status = new Status(),
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFim = new Data(),
    ) {
        $this->validarDados();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
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
        $dado = $this
            ->campo([
                'uuid', 'navegar', 'procura', 'suporte', 'atendimento',
                'sistemas_clube', 'comentario', 'status', 'data_criacao',
                'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'nome'
            ], 'usuario')
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        if (!empty($this->empresa)) {
            $empresa = (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->empresa);
            $where[] = ['id_admin_empresa', $empresa];
        }
        if ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->banco() . ' 00:00:00'];
        }
        if ($this->dataFim->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFim->banco() . ' 23:59:59'];
        }
        return $where;
    }

    /**
     * @return array
     */
    protected function pegarWhereEmpresa(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = ['cod', $this->empresa];
        }
        return $where;
    }

    /**
     * @param array $respostas
     *
     * @return array
     */
    protected function montarRetorno(array $respostas): array
    {
        if (empty($respostas)) {
            return $respostas;
        }

        $Navegar = new Navegar();
        $Procura = new Procura();
        $Suporte = new Suporte();
        $Atendimento = new Atendimento();
        $Status = new Status();
        $retorno = [];
        foreach ($respostas as $resposta) {
            $retorno[] = [
                'id'               => $resposta->uuid,
                'empresa'          => [
                    'nome' => $resposta->empresa_nome_fantasia
                ],
                'usuario'          => [
                    'nome' => $resposta->usuario_nome
                ],
                'navegar'          => $Navegar->indice($resposta->navegar),
                'procura'          => $Procura->indice($resposta->procura),
                'suporte'          => $Suporte->indice($resposta->suporte),
                'atendimento'      => $Atendimento->indice($resposta->atendimento),
                'sistemas_clube'   => $resposta->sistemas_clube,
                'comentario'       => $resposta->comentario,
                'status'           => $Status->indice($resposta->status),
                'data_criacao'     => $resposta->data_criacao,
                'data_atualizacao' => $resposta->data_atualizacao
            ];
        }
        return $retorno;
    }
}
