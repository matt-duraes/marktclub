<?php

namespace App\Models\Api\Automovel\Versao;

use App\Classes\Automovel\Versao\Ordem;
use App\Classes\Geral\Status;
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
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_AUTOMOVEL_VERSAO;

    /**
     * @param Pagina          $pagina
     * @param Quantidade      $quantidade
     * @param Ordem           $ordem
     *
     * @param string|int|null $modelo
     * @param Status          $status
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private string|int|null $modelo = null,
        private readonly Status $status = new Status(),
    ) {
        $this->validarDados();
        $this->pegarModelo();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        if (!$this->ordem->vazio() && !$this->ordem->valido()) {
            mensagemErro('Campo inválido!', 'A Ordem informada não é válida.');
        }
        if (!$this->status->vazio() && !$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é válido.');
        }
    }

    private function pegarModelo(): void
    {
        if (!empty($this->modelo) && is_int($this->modelo)) {
            return;
        }
        $this->modelo = (new OrmHelper(TABELA_AUTOMOVEL_MODELO))
            ->pegarIdPeloUuid($this->modelo);
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $versoes = $this
            ->campo([
                'uuid', 'titulo', 'cor', 'valor_de', 'valor_por', 'status'
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
        $where = [
            ['id_automovel_modelo', $this->modelo]
        ];
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
                'uuid', 'vinculo', 'titulo', 'detalhe', 'cor', 'valor',
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
