<?php

namespace App\Models\Api\Automovel\Montadora;

use Erro\Excecao;
use Http\Request;
use ORM\ORM;
use stdClass;
use App\Classes\Automovel\Montadora\Ordem;
use App\Classes\Automovel\Montadora\Tipo;
use App\Classes\ParceiroLoja\Status;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class MontadoraModel extends ORM
{
    use QuantidadeTrait;
    use OrdemTrait;
    use PaginaTrait;

    protected string $ormTabela = TABELA_CARRO_MENU;
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
        $tipo = new Tipo($this->request->tipo);
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
        } elseif (!$tipo->vazio() && !$tipo->valido()) {
            mensagemErro('Dado inválido!', 'O campo tipo não é um valor válido.');
        } elseif (!$status->vazio() && !$status->valido()) {
            mensagemErro('Dado inválido!', 'O campo status não é um valor válido.');
        }
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'cod_parceiro', 'imagem', 'bg', 'titulo', 'tipo', 'data_criacao'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order(new Ordem($this->request->ordem))
            ->tabela(TABELA_PARCEIRO_LOJA)->join('cod', 'cod_parceiro')
            ->where([
                ['status', 4],
                ['empresa', 'like', '%"' . $this->idEmpresa . '"%']
            ])
            ->campo(['url', 'status'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);

        return $dado;
    }

    /**
     * @return array
     */
    protected function pegarWhere(): array
    {
        $where = [];

        $pesquisa = $this->request->pesquisa;
        if (!empty($pesquisa)) {
            $where[] = ['titulo', 'like', '%' . $pesquisa . '%'];
        }

        $tipo = new Tipo($this->request->tipo);
        if ($tipo->valido()) {
            $where[] = ['tipo', $tipo->numero()];
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
        $Tipo = new Tipo();

        foreach ($dado as $r) {
            $retorno[] = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'tipo'   => $Tipo->indice($r->tipo),
                'imagem' => [
                    'link'  => $this->link_arquivo . '/carro/' . $r->imagem,
                    'valor' => $r->imagem,
                ],
                'imagem_background' => [
                    'link'  => $this->link_arquivo . '/carro/' . $r->bg,
                    'valor' => $r->bg,
                ],
                'url' => [
                    'link'  => $this->link_site . '/automoveis/' . $r->url,
                    'valor' => $r->url,
                ],
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    public function validarPermissao($url)
    {
        $busca = $this->campo(['uuid'])->where(['link', $url])
            ->tabela(TABELA_PARCEIRO_LOJA)->join('cod', 'cod_parceiro')->where([
                ['status', 4],
                ['empresa', 'like', '%"' . $this->idEmpresa . '"%'],
            ])->read()[0] ?? [];

        if ($busca) {
            return true;
        }

        return false;
    }

    public function pegarUuidPelaUrl(String $url = null)
    {
        return $this->campo(['uuid'])->where(['link',  $url])->read()[0]->uuid ?? '';
    }
}
