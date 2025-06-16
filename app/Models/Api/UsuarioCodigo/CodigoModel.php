<?php

namespace App\Models\Api\UsuarioCodigo;

use App\Classes\Geral\Status;
use App\Classes\UsuarioClienteCodigo\Ordem;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class CodigoModel extends ORM
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_USUARIO_CLUBE_CODIGO;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $empresa
     * @param string|null $subempresa
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
        private readonly ?string $subempresa = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarRequest();
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $codigos = $this
            ->campo([
                'uuid', 'id_admin_subempresa', 'codigo', 'data_criacao',
                'data_atualizacao', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod', 'titulo', 'nome_fantasia'
            ], 'empresa')
            ->read();
        $codigos->lista = $this->montarRetorno($codigos->lista);
        return $codigos;
    }

    protected function pegarWhere(): array
    {
        $where = $this->pegarWhereEmpresa();

        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataInicio->date(), $this->dataFinal->date()]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date()];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $codigos
     *
     * @return array
     * @throws Excecao
     */
    protected function montarRetorno(array $codigos): array
    {
        if (empty($codigos)) {
            return $codigos;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($codigos as $codigo) {
            $empresa = $this->tratarEmpresa($codigo);
            $subempresa = $this->tratarSubempresa($codigo);
            $retorno[] = [
                'id'               => $codigo->uuid,
                'empresa_id'       => $codigo->empresa_cod,
                'empresa_nome'     => $empresa,
                'subempresa_id'    => $subempresa->id,
                'subempresa_nome'  => $subempresa->nome,
                'codigo'           => $codigo->codigo,
                'data_criacao'     => $codigo->data_criacao,
                'data_atualizacao' => $codigo->data_atualizacao,
                'status'           => $Status->indice($codigo->status)
            ];
        }
        return $retorno;
    }

    /**
     * @param stdClass $codigo
     *
     * @return string
     */
    private function tratarEmpresa(stdClass $codigo): string
    {
        $empresa = '';
        if (!empty($codigo->empresa_titulo)) {
            $empresa = $codigo->empresa_titulo;
        } elseif (!empty($codigo->empresa_nome_fantasia)) {
            $empresa = $codigo->empresa_nome_fantasia;
        }
        return $empresa;
    }

    /**
     * @param stdClass $codigo
     *
     * @return stdClass
     * @throws Excecao
     */
    private function tratarSubempresa(stdClass $codigo): stdClass
    {
        $subempresa = [
            'id'   => '',
            'nome' => ''
        ];
        if (!empty($codigo->id_admin_subempresa)) {
            $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $subempresaRetorno = $ormHelper
                ->campo(['cod', 'titulo', 'nome_fantasia'])
                ->where(['id', $codigo->id_admin_subempresa])
                ->read();

            if (!empty($subempresaRetorno[0]->cod)) {
                $subempresa['id'] = $subempresaRetorno[0]->cod;
                if (!empty($subempresaRetorno[0]->titulo)) {
                    $subempresa['nome'] = $subempresaRetorno[0]->titulo;
                } elseif (!empty($subempresaRetorno[0]->nome_fantasia)) {
                    $subempresa['nome'] = $subempresaRetorno[0]->nome_fantasia;
                }
            }
        }
        return object($subempresa);
    }

    /**
     * @return array
     */
    private function pegarWhereEmpresa(): array
    {
        $where = [];
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        if (!empty($this->empresa)) {
            $where[] = ['id_admin_empresa', $ormHelper->pegarIdPeloUuid($this->empresa)];
        } elseif (!empty($this->ormWherePadrao)) {
            $where[] = ['id_admin_empresa', $this->idEmpresa];
        }

        if (!empty($this->subempresa)) {
            $where[] = ['id_admin_subempresa', $ormHelper->pegarIdPeloUuid($this->subempresa)];
        } elseif (!empty($this->idSubempresa)) {
            $where[] = ['id_admin_subempresa', $this->idSubempresa];
        }
        return $where;
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
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->dataInicio->vazio() && !$this->dataInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data final não está no formato válido.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }
}
