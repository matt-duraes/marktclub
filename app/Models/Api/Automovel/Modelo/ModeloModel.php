<?php

namespace App\Models\Api\Automovel\Modelo;

use Erro\Excecao;
use Http\Request;
use ORM\ORM;
use stdClass;
use App\Classes\Automovel\Modelo\Ordem;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class ModeloModel extends ORM
{
    use QuantidadeTrait;
    use OrdemTrait;
    use PaginaTrait;

    protected string $ormTabela = TABELA_CARRO;
    protected int $idEmpresa;
    protected string $link_arquivo;

    /**
     * @param Request|null $request
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
        $dado = $this
            ->campo(['uuid', 'montadora', 'titulo', 'imagem', 'url', 'data_criacao'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order(new Ordem($this->request->ordem))
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

        $montadora = $this->request->montadora;
        if (!empty($montadora)) {
            $where[] = ['montadora', $montadora];
        }

        $titulo = $this->request->titulo;
        if (!empty($titulo)) {
            $where[] = ['titulo', $titulo];
        }

        $status = new Status($this->request->status);
        if ($status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        return $where;
    }

    /**
     * @param array $dado
     * @return array
     */
    protected function montarRetorno(array $dado): array
    {
        $retorno = [];

        $Status = new Status();
        $Tipo = new Tipo();

        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'titulo' => $r->titulo,
                'imagem' => [
                    'logo' => $this->link_arquivo . '/carro/' . $r->imagem,
                    'bg' => $this->link_arquivo . '/carro/' . $r->bg,
                ],
                'url' => [
                    'link' => $this->link_site . '/automoveis/' . $r->url,
                    'valor' => $r->url,
                ],
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
