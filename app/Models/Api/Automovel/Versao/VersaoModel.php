<?php

namespace App\Models\Api\Automovel\Versao;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Http\Request;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use System\Trait\Model\OrdemTrait;
use App\Classes\StatusGeral\Status;
use System\Trait\Model\PaginaTrait;
use App\Classes\Automovel\Versao\Ordem;
use System\Trait\Model\QuantidadeTrait;

final class VersaoModel extends ORM
{
    use QuantidadeTrait;
    use OrdemTrait;
    use PaginaTrait;

    protected string $ormTabela = TABELA_AUTOMOVEL_VERSAO;
    protected int $idEmpresa;
    protected string $link_arquivo;
    private stdClass $dadoModelo;

    /**
     * @param  Request|null $request
     * @throws Excecao
     */
    public function __construct(
        private Pagina $pagina = new Pagina(null),
        private Quantidade $quantidade = new Quantidade(null),
        private ?string $modelo = null,
        private Status $status = new Status(null),
        private Ordem $ordem = new Ordem(null)
    ) {
        parent::__construct();
        $this->validarRequest();
        $this->pegarModelo();
    }

    /**
     * @throws Excecao
     */
    private function validarRequest(): void
    {
        if ($this->pagina->vazio()) {
            mensagemErroVazio('pagina');
        } elseif (!$this->pagina->valido()) {
            mensagemErroValido('pagina');
        }
    }

    private function pegarModelo()
    {
        $this->dadoModelo = (new OrmHelper(TABELA_AUTOMOVEL_MODELO))
            ->pegarUltimoRegistro(
                where: [
                    'OR',
                    ['url', $this->modelo],
                    ['uuid', $this->modelo]
                ],
                campo: ['url'],
                retorno: 'object'
            );
    }

    /**
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'titulo', 'valor_de', 'valor_por', 'imagem', 'status'])
            ->where($this->pegarWhere())
            ->order($this->pegarOrdem())
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
        $where = [
            ['modelo', $this->dadoModelo->url]
        ];
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
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
        foreach ($dado as $r) {
            $retorno[] = [
                'id'        => $r->uuid,
                'titulo'    => $r->titulo,
                'valor_de'  => $r->valor_de,
                'valor_por' => $r->valor_por,
                'link_logo' => arquivoPrivado($r->imagem),
                'status'    => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    public function pegarVersaoPeloVinculo(String $vinculo = null)
    {
        $dado = $this
            ->campo(['uuid',  'vinculo', 'titulo', 'detalhe', 'cor', 'valor', 'valor_off', 'tipo', 'status', 'data_criacao'])
            ->where([
                ['vinculo',  $vinculo],
                ['status',  1]
            ])
            ->read();

        $dado = $this->montarRetorno($dado);

        return $dado;
    }
}
