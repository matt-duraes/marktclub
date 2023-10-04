<?php

namespace App\Models\Api\ComunicacaoContato;

use App\Classes\ComunicacaoContato\Ordem;
use App\Classes\ComunicacaoContato\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
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

class ComunicacaoContatoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMUNICACAO_CONTATO;
    protected ?int $idEmpresa;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Ordem       $ordem
     * @param string|null $nome
     * @param string|null $empresa
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
        private readonly ?string $nome = null,
        private readonly ?string $empresa = null,
        private readonly Data $dataInicio = new Data(),
        private readonly Data $dataFinal = new Data(),
        private readonly Status $status = new Status()
    ) {
        $this->validarEmpresa();
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if (!$this->dataInicio->vazio() && !$this->dataInicio->valido()) {
            mensagemErro('Campo inválido!', 'A Data contato de início não está no formato válido.');
        }
        if (!$this->dataFinal->vazio() && !$this->dataFinal->valido()) {
            mensagemErro('Campo inválido!', 'A Data contato final não está no formato válido.');
        }
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
                'uuid', 'nome', 'telefone', 'email',
                'mensagem', 'status', 'data_criacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'uuid', 'nome_fantasia'
            ], 'empresa')
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

        if (!empty($this->nome)) {
            $where[] = ['nome', 'LIKE', '%' . $this->nome . '%'];
        }

        if ($this->dataInicio->valido() && $this->dataFinal->valido()) {
            $where[] = [
                'data_criacao', 'between', [$this->dataInicio->date(), $this->dataFinal->date() . ' 23:59:59']
            ];
        } elseif ($this->dataInicio->valido()) {
            $where[] = ['data_criacao', '>=', $this->dataInicio->date()];
        } elseif ($this->dataFinal->valido()) {
            $where[] = ['data_criacao', '<=', $this->dataFinal->date() . ' 23:59:59'];
        }

        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }

        return $where;
    }

    /**
     * @param array $contatos
     *
     * @return array
     */
    protected function montarRetorno(array $contatos): array
    {
        if (empty($contatos)) {
            return $contatos;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($contatos as $contato) {
            $retorno[] = [
                'id'           => $contato->uuid,
                'empresa'      => [
                    'id'   => $contato->empresa_uuid,
                    'nome' => $contato->empresa_nome_fantasia
                ],
                'nome'         => $contato->nome,
                'telefone'     => $contato->telefone,
                'email'        => $contato->email,
                'mensagem'     => $contato->mensagem,
                'status'       => $Status->indice($contato->status),
                'data_criacao' => $contato->data_criacao
            ];
        }
        return $retorno;
    }
}
