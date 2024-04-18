<?php

namespace App\Models\Api\ConstrutorClube;

use App\Classes\ConstrutorClube\Ordem;
use App\Classes\Geral\Status;
use Erro\Excecao;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class ConstrutorModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_CONSTRUTOR_CLUBE;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $empresa
     * @param string|null $pesquisa
     * @param string|null $titulo
     * @param Data        $dataInicio
     * @param Data        $dataFinal
     * @param Status      $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $empresa = null,
        private readonly ?string $pesquisa = null,
        private readonly ?string $titulo = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->pagina->vazio() && !$this->pagina->valido()) {
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início informada não é válida.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de final informada não é válida.');
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
        $clubes = $this
            ->campo([
                'uuid', 'titulo', 'status', 'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'uuid', 'titulo', 'nome_fantasia'
            ], 'empresa')
            ->read();

        $clubes->lista = $this->montarRetorno($clubes->lista);
        return $clubes;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'LIKE', "%$this->pesquisa%"],
                ['link_clube', 'LIKE', "%$this->pesquisa%"]
            ];
        }

        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'LIKE', "%$this->titulo%"];
        }

        if ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        }
        if ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date() . ' 23:59:59'];
        }
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
     * @param array $clubes
     *
     * @return array
     */
    private function montarRetorno(array $clubes): array
    {
        $Status = new Status();
        $retorno = [];
        foreach ($clubes as $clube) {
            $empresaTitulo = !empty($clube->empresa_titulo) ? $clube->empresa_titulo : $clube->empresa_nome_fantasia;
            $retorno[] = [
                'id'               => $clube->uuid,
                'empresa'          => [
                    'id'     => $clube->empresa_uuid,
                    'titulo' => $empresaTitulo
                ],
                'titulo'           => $clube->titulo,
                'status'           => $Status->indice($clube->status),
                'data_criacao'     => $clube->data_criacao,
                'data_atualizacao' => $clube->data_atualizacao
            ];
        }
        return $retorno;
    }
}
