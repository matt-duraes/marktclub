<?php

namespace App\Models\Api\SolicitacaoChequeBonus;

use App\Classes\Solicitacao\Status;
use App\Classes\SolicitacaoChequeBonus\Ordem;
use App\Classes\UsuarioCliente\TipoUsuario;
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

final class ChequeBonusModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_SOLICITACAO_CHEQUE_BONUS;

    /**
     * @param Pagina      $pagina
     * @param Quantidade  $quantidade
     * @param Data        $dataCriacaoDe
     * @param Data        $dataCriacaoAte
     * @param Status      $status
     * @param string|null $empresa
     * @param Ordem       $ordem
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly Pagina $pagina = new Pagina(null),
        private readonly Quantidade $quantidade = new Quantidade(null),
        private readonly Data $dataCriacaoDe = new Data(null),
        private readonly Data $dataCriacaoAte = new Data(null),
        private readonly Status $status = new Status(null),
        private readonly ?string $empresa = null,
        private readonly Ordem $ordem = new Ordem(null)
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo([
                'uuid', 'tipo_usuario', 'nome',
                'dependente_nome', 'data_criacao', 'status'
            ])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order($this->pegarOrdem())
            ->read();

        $dado->lista = $this->montarDado($dado->lista ?? []);
        return $dado;
    }

    /**
     * @return array
     */
    private function pegarWhere(): array
    {
        return $this->ormWherePadrao;
    }

    /**
     * @param $dado
     *
     * @return array
     */
    private function montarDado($dado): array
    {
        $retorno = [];
        $TipoUsuario = new TipoUsuario();
        $Status = new Status();
        foreach ($dado as $r) {
            $tipo = $TipoUsuario->indice($r->tipo_usuario);
            $nome = $tipo == $TipoUsuario::TITULAR ? $r->nome : $r->dependente_nome;
            $retorno[] = (object)[
                'id'           => $r->uuid,
                'nome'         => $nome,
                'tipo_usuario' => $tipo,
                'data_criacao' => $r->data_criacao,
                'status'       => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }
}
