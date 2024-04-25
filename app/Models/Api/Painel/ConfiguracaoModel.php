<?php

namespace App\Models\Api\Painel;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Pagina;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use App\Classes\PainelConfiguracoes\Ordem;
use System\Interface\ModelListarInterface;

class ConfiguracaoModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_PAINEL_CONFIG;

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
        private readonly Ordem $ordem = new Ordem(),
        private readonly ?string $empresa = null,
        private readonly ?string $titulo = null
    ) {
        $this->validarRequest();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
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
        $configuracoes = $this
            ->campo([
                'uuid', 'titulo', 'permissao', 'configuracao', 'campo_obrigatorio',
                'campo_permitido', 'upload_grupo', 'data_criacao',
                'data_atualizacao'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->where($this->pegarWhereEmpresa(), false)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod', 'titulo'
            ], 'empresa')
            ->read();

        $configuracoes->lista = $this->montarRetorno($configuracoes->lista);
        return $configuracoes;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'LIKE', "%$this->titulo%"];
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
     * @param array $configuracoes
     *
     * @return array
     */
    private function montarRetorno(array $configuracoes): array
    {
        $retorno = [];
        foreach ($configuracoes as $configuracao) {
            $retorno[] = [
                'id'                => $configuracao->uuid,
                'empresa'           => [
                    'id'   => $configuracao->empresa_cod,
                    'nome' => $configuracao->empresa_titulo,
                ],
                'titulo'            => $configuracao->titulo,
                'permissao'         => jsonDecode($configuracao->permissao, true, true),
                'configuracao'      => jsonDecode($configuracao->configuracao, true, true),
                'campo_obrigatorio' => jsonDecode($configuracao->campo_obrigatorio, true, true),
                'campo_permitido'   => jsonDecode($configuracao->campo_permitido, true, true),
                'upload_grupo'      => jsonDecode($configuracao->upload_grupo, true, true),
                'data_criacao'      => $configuracao->data_criacao,
                'data_atualizacao'  => $configuracao->data_atualizacao
            ];
        }
        return $retorno;
    }
}
