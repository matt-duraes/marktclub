<?php

namespace ApiModel\Endereco;

use ORM\ORM;
use Helpers\ListaHelper;

final class EstruturaModel extends ORM
{
    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    public array $estrutura = [];
    private array $listaPais = [];
    private array $listaEstado = [];
    private array $listaCidade = [];
    private array $where = [];

    public function __construct(
        public string $vinculo,
        public string $local_principal,
        public string $local_secundario,
        private ?string $pais = null,
        private ?string $estado = null
    ) {
        parent::__construct();
        $this->montarWhere();
        if (empty($pais)) {
            $this->buscarListaPais();
        } elseif (empty($estado)) {
            $this->buscarListaEstado($pais);
        } else {
            $this->buscarListaCidade($pais, $estado);
        }
        $this->montarRetorno();
    }

    private function montarWhere()
    {
        $this->where = [
            ['id_vinculo', $this->vinculo],
            ['local_principal', $this->local_principal],
            ['local_secundario', $this->local_secundario],
        ];
    }

    public function buscarListaPais()
    {
        $dado = $this
            ->campo(['pais'])
            ->where($this->where)
            ->group('pais')
            ->order('pais', 'ASC')
            ->read();

        $paisNome = (new ListaHelper())->pais()->r();
        $pais = [];
        foreach ($dado as $r) {
            $pais[$r->pais] = $paisNome[$r->pais] ?? $r->pais;
        }
        $quantidade = count($pais);
        if ($quantidade <= 1) {
            $this->buscarListaEstado($quantidade == 1 ? array_keys($pais)[0] : '');
        }
        $this->listaPais = $pais;
    }

    private function buscarListaEstado(string $pais)
    {
        $where = $this->where;
        if (!empty($pais)) {
            $where[] = ['pais', $pais];
        }

        $dado = $this
            ->campo(['estado'])
            ->where($where)
            ->group('estado')
            ->order('estado', 'ASC')
            ->read();

        $estadoNome = (new ListaHelper())->estado()->r();
        $estado = [];
        foreach ($dado as $r) {
            $estado[$r->estado] = $estadoNome[$r->estado] ?? $r->estado;
        }
        $quantidade = count($estado);
        if ($quantidade <= 1) {
            $this->buscarListaCidade($pais, $quantidade == 1 ? $estado[0] : '');
        }
        $this->listaEstado = $estado;
    }

    private function buscarListaCidade(string $pais, string $estado)
    {
        $where = $this->where;
        if (!empty($pais)) {
            $where[] = ['pais', $pais];
        }
        if (!empty($estado)) {
            $where[] = ['estado', $estado];
        }

        $dado = $this
            ->campo(['cidade'])
            ->where($where)
            ->group('cidade')
            ->order('cidade', 'ASC')
            ->read();

        $cidade = [];
        foreach ($dado as $r) {
            $cidade[$r->cidade] = $r->cidade;
        }
        $this->listaCidade = $cidade;
    }

    private function montarRetorno()
    {
        $estrutura = [];
        if (!empty($this->listaPais)) {
            $estrutura['pais'] = $this->listaPais;
        }
        if (!empty($this->listaEstado)) {
            $estrutura['estado'] = $this->listaEstado;
        }
        if (!empty($this->listaCidade)) {
            $estrutura['cidade'] = $this->listaCidade;
        }
        $this->estrutura = $estrutura;
    }
}
