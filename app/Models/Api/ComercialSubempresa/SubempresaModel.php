<?php

namespace App\Models\Api\ComercialSubempresa;

use App\Classes\ComercialSubempresa\Ordem;
use App\Classes\ComercialSubempresa\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Cnpj;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class SubempresaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMERCIAL_SUBEMPRESA;
    protected ?int $idEmpresa;

    /**
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $titulo = null,
        private readonly ?string $empresa = null,
        private readonly Status $status = new Status()
    ) {
        $this->validarDados();
        $this->validarEmpresa();
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
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
        if (!empty($this->empresa) && validarUuid($this->empresa, false)) {
            mensagemErro('Campo inválido!', 'A Empresa informada não é válida.');
        }
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $subempresas = $this
            ->campo([
                'uuid', 'nome', 'documento_cnpj', 'status',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod', 'nome_fantasia'
            ], 'empresa')
            ->read();

        $subempresas->lista = $this->montarRetorno($subempresas->lista);
        return $subempresas;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if (!empty($this->titulo)) {
            $where[] = ['nome', 'LIKE', "%$this->titulo%"];
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

    private function montarRetorno(array $subempresas): array
    {
        if (empty($subempresas)) {
            return $subempresas;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($subempresas as $subempresa) {
            $retorno[] = [
                'id'               => $subempresa->uuid,
                'empresa_matriz'   => [
                    'id'   => $subempresa->empresa_cod,
                    'nome' => $subempresa->empresa_nome_fantasia
                ],
                'nome'             => $subempresa->nome,
                'documento_cnpj'   => (new Cnpj($subempresa->documento_cnpj))->cnpj(),
                'status'           => $Status->indice($subempresa->status),
                'data_criacao'     => $subempresa->data_criacao,
                'data_atualizacao' => $subempresa->data_atualizacao
            ];
        }
        return $retorno;
    }
}
