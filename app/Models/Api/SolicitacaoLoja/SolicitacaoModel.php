<?php

namespace App\Models\Api\SolicitacaoLoja;

use App\Classes\SolicitacaoLoja\Ordem;
use App\Classes\SolicitacaoLoja\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
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

class SolicitacaoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_LOJA;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $pesquisa
     * @param string|null $empresa
     * @param string|null $parceiro
     * @param string|null $usuario
     * @param Data        $dataIndicacaoInicio
     * @param Data        $dataIndicacaoFinal
     * @param Data        $dataProspeccaoInicio
     * @param Data        $dataProspeccaoFinal
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
        private readonly ?string $usuario = null,
        private readonly ?string $parceiro = null,
        private readonly Data $dataIndicacaoInicio = new Data(),
        private readonly Data $dataIndicacaoFinal = new Data(),
        private readonly Data $dataProspeccaoInicio = new Data(),
        private readonly Data $dataProspeccaoFinal = new Data(),
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
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->dataIndicacaoInicio->vazio() && !$this->dataIndicacaoInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de Indicação início não está no formato válido.');
        }
        if (!$this->dataIndicacaoFinal->vazio() && !$this->dataIndicacaoFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data Indicação final não está no formato válido.');
        }
        if (!$this->dataProspeccaoInicio->vazio() && !$this->dataProspeccaoInicio->eDate()) {
            mensagemErro('Campo inválido!', 'A Data de Prospecção início não está no formato válido.');
        }
        if (!$this->dataProspeccaoFinal->vazio() && !$this->dataProspeccaoFinal->eDate()) {
            mensagemErro('Campo inválido!', 'A Data Prospecção final não está no formato válido.');
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
        $indicacoes = $this
            ->campo([
                'uuid', 'id_admin_empresa', 'id_usuario_cliente',
                'id_parceiro_loja', 'nome', 'email', 'telefone', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'id', 'titulo', 'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->where($this->pegarWhereUsuario(), false)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'id', 'nome'
            ], 'usuario')
            ->read();

        $indicacoes->lista = $this->montarRetorno($indicacoes->lista);
        return $indicacoes;
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;

        if (!empty($this->parceiro) && !validarUuid($this->parceiro, false)) {
            $where[] = ['nome', 'like', '%' . $this->parceiro . '%'];
        } elseif (!empty($this->parceiro) && validarUuid($this->parceiro, false)) {
            $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
            $where[] = ['id_parceiro_loja', $ormHelper->pegarIdPeloUuid($this->parceiro)];
        }

        if ($this->dataIndicacaoInicio->valido() && $this->dataIndicacaoFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataIndicacaoInicio->date(), $this->dataIndicacaoFinal->date()]
            ];
        } elseif ($this->dataIndicacaoInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataIndicacaoInicio->date()];
        } elseif ($this->dataIndicacaoFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataIndicacaoFinal->date()];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        return $where;
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhereEmpresa(): array
    {
        $where = [];
        if (!empty($this->empresa) && !validarUuid($this->empresa, false)) {
            $where[] = [
                ['titulo', 'like', '%' . $this->empresa . '%'],
                ['nome_fantasia', 'like', '%' . $this->empresa . '%'],
                ['razao_social', 'like', '%' . $this->empresa . '%']
            ];
        } elseif (!empty($this->empresa) && validarUuid($this->empresa, false)) {
            $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $where[] = ['id', $ormHelper->pegarIdPeloUuid($this->empresa)];
        }
        return $where;
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhereUsuario(): array
    {
        $where = [];
        if (!empty($this->usuario) && !validarUuid($this->usuario, false)) {
            $where[] = ['nome', 'like', '%' . $this->usuario . '%'];
        } elseif (!empty($this->usuario) && validarUuid($this->usuario, false)) {
            $ormHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
            $where[] = ['id', $ormHelper->pegarIdPeloUuid($this->usuario)];
        }
        return $where;
    }

    /**
     * @param array $indicaoes
     *
     * @return array
     */
    private function montarRetorno(array $indicaoes): array
    {
        if (empty($indicaoes)) {
            return $indicaoes;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($indicaoes as $indicacao) {
            $empresa = empty($indicacao->empresa_titulo) ? $indicacao->empresa_nome_fantasia : $indicacao->empresa_titulo;
            $parceiro = 'Sem Parceiro';
            if (!empty($indicacao->id_parceiro_loja)) {
                $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
                $parceiro = $ormHelper->pegarCampoPor('titulo_interno', ['id', $indicacao->id_parceiro_loja]);
            }
            $retorno[] = [
                'id'                 => $indicacao->uuid,
                'empresa'            => $empresa,
                'usuario_indicacao'  => $indicacao->usuario_nome,
                'parceiro'           => $parceiro,
                'nome_indicacao'     => $indicacao->nome,
                'email_indicacao'    => $indicacao->email,
                'telefone_indicacao' => $indicacao->telefone,
                'status'             => $Status->indice($indicacao->status),
                'data_criacao'       => $indicacao->data_criacao,
                'data_atualizacao'   => $indicacao->data_atualizacao
            ];
        }
        return $retorno;
    }
}
