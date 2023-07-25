<?php

namespace App\Models\Api\Automovel\Automovel;

use Erro\Excecao;
use Http\Request;
use ORM\ORM;
use stdClass;
use App\Classes\Automovel\Automovel\Ordem;
use App\Classes\Automovel\Automovel\TipoProcedimento;
use App\Classes\Automovel\Automovel\Status;
use App\Models\Api\Automovel\Montadora\MontadoraModel;
use App\Models\Api\Automovel\Versao\VersaoModel;
use App\Models\Api\GeralEndereco\EnderecoModel;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class AutomovelModel extends ORM
{
    use QuantidadeTrait;
    use OrdemTrait;
    use PaginaTrait;

    protected string $ormTabela = TABELA_CARRO;
    protected int $idEmpresa;
    protected string $link_arquivo;

    /**
     * @param  Request|null $request
     * @throws Excecao
     */
    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();

        if (defined('TOKEN')) {
            $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
            $this->link_arquivo = LINK_ARQUIVO;
            $this->link_site = LINK_SITE;
        }

        if (is_null($request)) {
            return;
        }
        $this->validarRequest();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        $pagina = $this->request->pagina;
        $quantidade = $this->request->quantidade;
        $ordem = new Ordem($this->request->ordem);
        $status = new Status($this->request->status);

        if (!validarPagina($pagina)) {
            mensagemErro('Dado inválido!', 'O campo página não é um valor válido.');
        } elseif (!empty($quantidade) && !validarPagina($quantidade)) {
            mensagemErro('Dado inválido!', 'O campo quantidade não é um valor válido.');
        } elseif (!empty($quantidade) && $quantidade > 50) {
            mensagemErro('Dado inválido!', 'O campo quantidade deve ser menor ou igual a 50.');
        } elseif (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Dado inválido!', 'O campo ordem não é um valor válido.');
        } elseif (!$status->vazio() && !$status->valido()) {
            mensagemErro('Dado inválido!', 'O campo status não é um valor válido.');
        }
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        if ($this->request->url == 'honda-email') :
            return mensagemStatus(404);
        endif;

        $dado = $this
            ->campo(['uuid', 'titulo', 'url', 'texto', 'montadora', 'imagem'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order(new Ordem($this->request->ordem))
            ->tabela(TABELA_CARRO_MENU)
            ->join('link', 'montadora')
            ->campo(['documento', 'procedimento', 'link_concessionaria', 'link'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        if (!$dado) :
            return [];
        endif;

        if (!(new MontadoraModel())->validarPermissao($dado->lista[0]->montadora)) :
            return mensagemStatus(404);
        endif;

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = [];

        $url = $this->request->url;
        if (!empty($url)) {
            $where[] = ['url', $url];
        }

        $status = new Status($this->request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        return $where;
    }

    /**
     * @param  array $dado
     * @return array
     */
    protected function montarRetorno(array $dado): array
    {
        $retorno = [];

        $Status = new Status();
        $TipoProcedimento = new TipoProcedimento();

        foreach ($dado as $r) {
            $uuid_montadora = (new MontadoraModel())->pegarUuidPelaUrl($r->link);

            $pagamento_tipo = 'de-por';
            if ($r->link == 'amigos-chevrolet' || $r->montadora == 'amigos-chevrolet' || $uuid_montadora == 'a3093c4cc552204314a9cd13c7a9c66b') :
                $pagamento_tipo = 'carta-bonus';
            endif;

            $listaEndereco = (new EnderecoModel(
                uuid: $uuid_montadora,
                tabela: TABELA_CARRO_MENU,
                local: 2
            ))->listarDados();

            $versao = (new VersaoModel())->pegarVersaoPeloVinculo($r->uuid);

            $retorno[] = [
                'id'           => $r->uuid,
                'titulo'       => $r->titulo,
                'procedimento' => (object)[
                    'texto'      => $r->procedimento,
                    'geral'      => $r->procedimento,
                    'individual' => !empty($r->texto) ? $r->texto : '',
                    'tipo'       => $TipoProcedimento->indice($r->documento),
                ],
                'desconto' => (object)[
                    'tipo' => $pagamento_tipo,
                ],
                'imagem'   => $this->link_arquivo . '/carro/' . $r->imagem,
                'endereco' => (object)[
                    'concessionaria' => !empty($r->link_concessionaria) ? $r->link_concessionaria : '',
                    'lista'          => $listaEndereco ?? [],
                ],
                'versao' => (object)$versao
            ];
        }

        return $retorno;
    }
}
