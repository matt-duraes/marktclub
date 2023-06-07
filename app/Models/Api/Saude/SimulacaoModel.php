<?php

namespace App\Models\Api\Saude;

use App\Classes\Saude\Localizacao;
use App\Classes\Saude\Operadora;
use App\Classes\Saude\Status;
use App\Classes\Saude\Tipo;
use Erro\Excecao;
use Http\Request;
use Modules\Data;
use ORM\ORM;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SimulacaoModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;

    /**
     * @param  ?Request  $request
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?Request $request = null
    ) {
        parent::__construct();
        $this->validarRequest();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $dataCriacaoDe = new Data($this->request->getGet('data_criacao_de'));
        if (!$dataCriacaoDe->vazio() && (!$dataCriacaoDe->valido() || !$dataCriacaoDe->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação de início não está no formato válido.');
        }
        $dataCriacaoAte = new Data($this->request->getGet('data_criacao_ate'));
        if (!$dataCriacaoAte->vazio() && (!$dataCriacaoAte->valido() || !$dataCriacaoAte->eDate())) {
            mensagemErro('Campo inválido!', 'A data de criação final não está no formato válido.');
        }
        $Operadora = new Operadora($this->request->getGet('operadora'));
        if (!$Operadora->vazio() && !$Operadora->valido()) {
            mensagemErro('Campo inválido!', 'A Operadora informada não é válida.');
        }
        $Localizacao = new Localizacao($this->request->getGet('regiao'));
        if (!$Localizacao->vazio() && !$Localizacao->valido()) {
            mensagemErro('Campo inválido!', 'A Região informada não é válida.');
        }
        $Tipo = new Tipo($this->request->getGet('tipo'));
        if (!$Tipo->vazio() && !$Tipo->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo informado não é válido.');
        }
        $Status = new Status($this->request->getGet('status'));
        if (!$Status->vazio() && !$Status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    /**
     * @return object
     * @throws Excecao
     */
    public function listarDados(): object
    {
        $dado = $this
            ->campo([
                'uuid', 'id_admin_empresa', 'id_usuario_equipe', 'data_nascimento',
                'quantidade_dependentes', 'operadora', 'acomodacao', 'regiao',
                'valor', 'tipo', 'status', 'data_criacao'
            ])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->where($this->pegarWhere(), false)
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

        $Operadora = new Operadora($this->request->getGet('operadora'));
        if ($Operadora->valido()) {
            $where[] = ['operadora', $Operadora->numero()];
        }

        $Localizacao = new Localizacao($this->request->getGet('regiao'));
        if ($Localizacao->valido()) {
            $where[] = ['regiao', $Localizacao->numero()];
        }

        $Tipo = new Tipo($this->request->getGet('tipo'));
        if ($Tipo->valido()) {
            $where[] = ['tipo', $Tipo->numero()];
        }

        $Status = new Status($this->request->getGet('status'));
        if ($Status->valido()) {
            $where[] = ['status', $Status->numero()];
        }

        $dataCriacaoDe = $this->request->getGet('data_criacao_de');
        $dataCriacaoAte = $this->request->getGet('data_criacao_ate');

        if (validarDataDate($dataCriacaoDe) && validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', 'between', [$dataCriacaoDe, $dataCriacaoAte]];
        } elseif (validarDataDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        } elseif (validarDataDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
        }

        return $where;
    }

    /**
     * @param  array  $simulacoes
     *
     * @return array
     */
    protected function montarRetorno(array $simulacoes): array
    {
        if (empty($simulacoes)) {
            return $simulacoes;
        }

        $Operadora = new Operadora();
        $Localizacao = new Localizacao();
        $Tipo = new Tipo();
        $Status = new Status();

        $retorno = [];
        foreach ($simulacoes as $simulacao) {
            $retorno[] = [
                'uuid'                   => $simulacao->uuid,
                'data_nascimento'        => $simulacao->data_nascimento,
                'quantidade_dependentes' => $simulacao->quantidade_dependentes,
                'operadora'              => $Operadora->indice($simulacao->operadora),
                'acomodacao'             => $simulacao->acomodacao,
                'regiao'                 => $Localizacao->indice($simulacao->regiao),
                'valor'                  => $simulacao->valor,
                'tipo'                   => $Tipo->indice($simulacao->tipo),
                'status'                 => $Status->indice($simulacao->status),
                'data_criacao'           => dataHoraBr($simulacao->data_criacao)
            ];
        }
        return $retorno;
    }

    /**
     * @param  string   $uuid
     * @param  ?string  $idUsuario
     * @param  ?string  $idEmpresa
     *
     * @return ?object Caso exista uma simulação retorna um OBJECT. Do contrário NULL.
     * @throws Excecao
     */
    public function validarSimulacao(string $uuid, string $idUsuario = null, string $idEmpresa = null): ?array
    {
        $where = [
            ['uuid', $uuid]
        ];

        if ($idUsuario !== null) {
            $where[] = ['usuario', $idUsuario];
        }

        if ($idEmpresa !== null) {
            $where[] = ['OR', ['empresa', $idEmpresa], ['tipo', 3]];
        }

        $simulacao = $this
            ->tabela($this->ormTabela)
            ->campo(['status'])
            ->where($where)
            ->read();
        return empty($simulacao) ? null : $simulacao;
    }

    /**
     * @param  string  $uuid
     * @param  Status  $status
     *
     * @return ?array Array com os dados que foram atualizados
     * @throws Excecao
     */
    public function alterarStatusDaSimulacao(string $uuid, Status $status): ?array
    {
        $simulacao = $this
            ->dado([
                'status' => $status->numero()
            ])
            ->where(['uuid', $uuid])
            ->update();
        return empty($simulacao) ? null : $simulacao;
    }

    /**
     * @param  string   $uuid
     * @param  ?string  $idUsuario
     * @param  ?string  $idEmpresa
     *
     * @return ?object
     * @throws Excecao
     */
    public function pegarSimulacaoDocumento(string $uuid, string $idUsuario = null, string $idEmpresa = null): ?object
    {
        $where = [
            ['uuid', $uuid]
        ];

        if ($idUsuario !== null) {
            $where[] = ['usuario', $idUsuario];
        }

        if ($idEmpresa !== null) {
            $where[] = ['OR', ['empresa', $idEmpresa], ['tipo', 3]];
        }

        $simulacao = $this
            ->tabela($this->ormTabela)
            ->campo(['operadora', 'quantidade_dependentes'])
            ->where($where)
            ->order('id', 'DESC')
            ->limit(0, 1)
            ->read();
        return empty($simulacao) ? null : $simulacao;
    }
}
