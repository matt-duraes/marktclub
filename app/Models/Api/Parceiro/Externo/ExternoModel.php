<?php

namespace App\Models\Api\Parceiro\Externo;

use App\Classes\Parceiro\Externo\Ordem;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Indicador;
use App\Classes\ParceiroLoja\Status;
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

final class ExternoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PARCEIRO_LOJA;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $pesquisa
     * @param string|null $empresa
     * @param string|null $equipe
     * @param Indicador   $indicador
     * @param Categoria   $categoria
     * @param array|null  $estado
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
        private readonly ?string $pesquisa = null,
        private readonly ?string $empresa = null,
        private readonly ?string $equipe = null,
        private readonly Indicador $indicador = new Indicador(),
        private readonly Categoria $categoria = new Categoria(),
        private readonly ?array $estado = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
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
            mensagemErro('Campo inválido!', 'A Página informada não é válida.');
        }
        if (!$this->quantidade->vazio() && !$this->quantidade->valido()) {
            mensagemErro('Campo inválido!', 'A Quantidade informada não é válida.');
        }
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->indicador->vazio() && !$this->indicador->valido()) {
            mensagemErro('Campo inválido!', 'O Indicador informado não é válido.');
        }
        if (!$this->categoria->vazio() && !$this->categoria->valido()) {
            mensagemErro('Campo inválido!', 'A Categoria informada não é válida.');
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

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $externos = $this
            ->campo([
                'uuid', 'titulo_interno', 'categoria_principal', 'tipo_indicador',
                'data_criacao', 'data_atualizacao', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->where($this->pegarWhereEquipe(), false)
            ->join('id', 'id_dono_equipe')
            ->campo([
                'nome_real'
            ], 'equipe')
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_dono_empresa')
            ->campo([
                'nome_fantasia'
            ], 'empresa')
            ->read();
        $externos->lista = $this->montarRetorno($externos->lista);
        return $externos;
    }

    /**
     * @return array
     */
    public function pegarWhere(): array
    {
        $where = [];
        $isToken = defined('TOKEN');
        $isArray = is_array(TOKEN);
        $isUsuario = array_key_exists('usuario', TOKEN);
        $isEmpresa = array_key_exists('empresa', TOKEN);

        if ($isToken && $isArray && $isUsuario && $isEmpresa) {
            if (!in_array('parceiro_externo_empresa', TOKEN['usuario']->permissao)) {
                $where[] = ['id_dono_empresa', TOKEN['empresa']->id];
            }
            $idSubempresa = TOKEN['usuario']->id_admin_subempresa;
            if (!empty($idSubempresa)) {
                $where[] = ['id_dono_subempresa', $idSubempresa];
            }
        }
        if (!empty($this->pesquisa)) {
            $where[] = [
                'OR',
                ['titulo', 'like', '%' . $this->pesquisa . '%'],
                ['subcategoria_tag', 'like', '%' . $this->pesquisa . '%'],
                ['titulo_interno', 'like', '%' . $this->pesquisa . '%']
            ];
        }
        if ($this->categoria->valido()) {
            $where[] = ['categoria_principal', $this->categoria->numero()];
        }
        if ($this->indicador->valido()) {
            $where[] = ['tipo_indicador', $this->indicador->numero()];
        }
        if (!empty($this->estado)) {
            $where[] = ['endereco_estado', 'json', $this->estado];
        }
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
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
     * @return array
     */
    protected function pegarWhereEquipe(): array
    {
        $where = [];
        if (!empty($this->equipe)) {
            $where[] = ['uuid', $this->equipe];
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
     * @param array $externos
     *
     * @return array
     */
    private function montarRetorno(array $externos): array
    {
        $retorno = [];
        $Categoria = new Categoria();
        $Indicador = new Indicador();
        $Status = new Status();
        foreach ($externos as $externo) {
            $retorno[] = [
                'id'             => $externo->uuid,
                'empresa_nome'   => $externo->empresa_nome_fantasia ?? '',
                'equipe_nome'    => $externo->equipe_nome_real ?? '',
                'titulo_interno' => $externo->titulo_interno,
                'categoria'      => $Categoria->indice($externo->categoria_principal),
                'indicador'      => $Indicador->indice($externo->tipo_indicador),
                'status'         => $Status->indice($externo->status),
                'data_criacao'   => $externo->data_criacao
            ];
        }
        return $retorno;
    }
}
