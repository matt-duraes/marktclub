<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\OrdemComissao;
use App\Classes\Silium\StatusComissao;
use Modules\Data;
use Modules\Dinheiro;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class SiliumComissaoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_SILIUM_COMISSAO;

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly OrdemComissao $ordem = new OrdemComissao(),
        private readonly ?string $empresa = null,
        private readonly ?string $usuario = null,
        private readonly ?string $parceiro = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly StatusComissao $status = new StatusComissao()
    ) {
        $this->validarRequest();
        parent::__construct();
    }

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

    public function listarDados(): stdClass
    {
        $comissoes = $this
            ->campo([
                'uuid', 'parceiro', 'valor_compra', 'comissao_usuario',
                'pontuacao', 'data_compra', 'status', 'data_criacao',
                'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new OrdemComissao()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->where($this->pegarWhereEmpresa(), false)
            ->campo([
                'uuid', 'titulo'
            ], 'empresa')
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->where($this->pegarWhereUsuario(), false)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'uuid', 'nome'
            ], 'usuario')
            ->read();

        $comissoes->lista = $this->montarRetorno($comissoes->lista);
        return $comissoes;
    }

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if (!empty($this->parceiro)) {
            $where[] = ['parceiro', 'LIKE', "%$this->parceiro%"];
        }
        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_compra', 'between', [
                    $this->dataInicio->date(), $this->dataFinal->date()
                ]
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_compra', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_compra', '<=', $this->dataFinal->date()];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function pegarWhereEmpresa(): array
    {
        $where = [];
        if (!empty($this->empresa)) {
            $where[] = ['cod', $this->empresa];
        }
        return $where;
    }

    private function pegarWhereUsuario(): array
    {
        $where = [];
        if (!empty($this->usuario) && !validarUuid($this->usuario)) {
            $where[] = ['nome', 'LIKE', "%$this->usuario%"];
        } elseif (!empty($this->usuario) && validarUuid($this->usuario)) {
            $where[] = ['uuid', $this->usuario];
        }
        return $where;
    }

    private function montarRetorno(array $comissoes): array
    {
        if (empty($comissoes)) {
            return $comissoes;
        }

        $Status = new StatusComissao();
        $retorno = [];
        foreach ($comissoes as $comissao) {
            $retorno[] = [
                'id'               => $comissao->uuid,
                'empresa'          => [
                    'id'     => $comissao->empresa_uuid,
                    'titulo' => $comissao->empresa_titulo
                ],
                'usuario' => [
                    'id'     => $comissao->usuario_uuid,
                    'nome'   => $comissao->usuario_nome
                ],
                'parceiro'         => $comissao->parceiro,
                'valor_compra'     => (new Dinheiro($comissao->valor_compra))->banco(),
                'comissao_usuario' => (new Dinheiro($comissao->comissao_usuario))->banco(),
                'pontuacao'        => $comissao->pontuacao,
                'data_compra'      => $comissao->data_compra,
                'status'           => $Status->indice($comissao->status),
                'data_criacao'     => $comissao->data_criacao,
                'data_atualizacao' => $comissao->data_atualizacao
            ];
        }
        return $retorno;
    }
}
