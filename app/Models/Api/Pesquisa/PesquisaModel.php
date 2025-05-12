<?php

namespace App\Models\Api\Pesquisa;

use App\Classes\Pesquisa\Experiencia;
use App\Classes\Pesquisa\Fidelidade;
use App\Classes\Pesquisa\Frequencia;
use App\Classes\Pesquisa\Gasto;
use App\Classes\Pesquisa\Importancia;
use App\Classes\Pesquisa\Ordem;
use App\Classes\Pesquisa\Padrao;
use App\Classes\Pesquisa\Produtos;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class PesquisaModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PESQUISA;

    /**
     * @param Pagina     $pagina
     * @param Quantidade $quantidade
     * @param Ordem      $ordem
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem()
    ) {
        $this->validarRequest();
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return void
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
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $pesquisa = $this
            ->campo([
                'uuid', 'fidelidade', 'produtos', 'gasto', 'importancia', 'cashback',
                'frequencia', 'resgate', 'desconto', 'experiencia', 'indicaria',
                'data_criacao', 'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'titulo', 'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_USUARIO_CLIENTE)
            ->join('id', 'id_usuario_cliente')
            ->campo([
                'nome'
            ], 'usuario')
            ->read();

        $pesquisa->lista = $this->montarRetorno($pesquisa->lista);
        return $pesquisa;
    }

    protected function pegarWhere(): array
    {
        return $this->ormWherePadrao;
    }

    protected function montarRetorno(array $pesquisa): array
    {
        if (empty($pesquisa)) {
            return $pesquisa;
        }

        $retorno = [];
        foreach ($pesquisa as $resposta) {
            $retorno[] = [
                'id'               => $resposta->uuid,
                'empresa_nome'     => empty($resposta->empresa_titulo) ? $resposta->empresa_nome_fantasia : $resposta->empresa_titulo,
                'usuario_nome'     => $resposta->usuario_nome,
                'fidelidade'       => (new Fidelidade())->indice($resposta->fidelidade),
                'produtos'         => (new Produtos())->indice($resposta->produtos),
                'gasto'            => (new Gasto())->indice($resposta->gasto),
                'importancia'      => (new Importancia())->indice($resposta->importancia),
                'cashback'         => (new Padrao())->indice($resposta->cashback),
                'frequencia'       => (new Frequencia())->indice($resposta->frequencia),
                'resgate'          => (new Padrao())->indice($resposta->resgate),
                'desconto'         => (new Padrao())->indice($resposta->desconto),
                'experiencia'      => (new Experiencia())->indice($resposta->experiencia),
                'indicaria'        => (new Padrao())->indice($resposta->indicaria),
                'data_criacao'     => $resposta->data_criacao,
                'data_atualizacao' => $resposta->data_atualizacao
            ];
        }
        return $retorno;
    }
}
