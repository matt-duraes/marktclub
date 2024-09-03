<?php

namespace App\Models\Api\Automovel\Versao;

use App\Classes\Automovel\Versao\Ordem;
use App\Classes\Geral\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\Trait\ValidarRequestListar;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

final class VersaoModel extends ORM implements
    ModelListarInterface
{
    use ValidarEmpresaTrait;
    use ValidarRequestListar;
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_AUTOMOVEL_VERSAO;

    /**
     * @param Pagina          $pagina
     * @param Quantidade      $quantidade
     * @param Ordem           $ordem
     * @param string|int|null $parceiro
     * @param string|int|null $modelo
     * @param Status          $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private string|int|null $parceiro = null,
        private string|int|null $modelo = null,
        private readonly Status $status = new Status(),
    ) {
        $this->validarRequestListar();
        $this->validarEmpresa(json: true);
        $this->pegarParceiro();
        $this->pegarModelo();
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function pegarParceiro(): void
    {
        if (empty($this->parceiro)) {
            return;
        }

        $where = $this->ormWherePadrao;
        $ormHelper = new OrmHelper(TABELA_PARCEIRO_LOJA);
        if (validarUuid($this->parceiro, false)) {
            $where[] = ['uuid', $this->parceiro];
            $this->parceiro = $ormHelper->pegarCampoPor('id', $where);
            return;
        }
        $where[] = ['url', $this->parceiro];
        $this->parceiro = $ormHelper->pegarCampoPor('id', $where);
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function pegarModelo(): void
    {
        if (empty($this->modelo)) {
            return;
        }

        $ormHelper = new OrmHelper(TABELA_AUTOMOVEL_MODELO);
        if (validarUuid($this->modelo, false)) {
            $this->modelo = $ormHelper->pegarCampoPor('id', [
                ['id_parceiro_loja', $this->parceiro],
                ['uuid', $this->modelo]
            ]);
            return;
        }
        $this->modelo = $ormHelper->pegarCampoPor('id', [
            ['id_parceiro_loja', $this->parceiro],
            ['url', $this->modelo]
        ]);
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $versoes = $this
            ->campo([
                'uuid', 'titulo', 'imagem', 'cor', 'valor_de', 'valor_por', 'status'
            ])
            ->where($this->pegarWhere(), false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem(new Ordem()))
            ->read();
        $versoes->lista = $this->montarRetorno($versoes->lista);
        return $versoes;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        $where = [];
        if (!empty($this->modelo) && is_numeric($this->modelo)) {
            $where[] = ['id_automovel_modelo', $this->modelo];
        }
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    /**
     * @param array $versoes
     *
     * @return array
     */
    private function montarRetorno(array $versoes): array
    {
        $Status = new Status();
        $retorno = [];
        foreach ($versoes as $versao) {
            $retorno[] = [
                'id'        => $versao->uuid,
                'titulo'    => $versao->titulo,
                'imagem'    => arquivoPrivado($versao->imagem),
                'valor_de'  => $versao->valor_de,
                'valor_por' => $versao->valor_por,
                'cor'       => $versao->cor,
                'status'    => $Status->indice($versao->status)
            ];
        }
        return $retorno;
    }

    /**
     * @param string|null $vinculo
     *
     * @return array
     * @throws Excecao
     */
    public function pegarVersaoPeloVinculo(string $vinculo = null): array
    {
        $versoes = $this
            ->campo([
                'uuid', 'vinculo', 'titulo', 'imagem', 'detalhe', 'cor', 'valor',
                'valor_off', 'tipo', 'status', 'data_criacao'
            ])
            ->where([
                ['vinculo', $vinculo],
                ['status', (new Status(Status::ATIVO))->numero()]
            ])
            ->read();
        return $this->montarRetorno($versoes);
    }
}
