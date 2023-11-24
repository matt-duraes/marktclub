<?php

namespace App\Models\Api\ComunicacaoLogin;

use App\Classes\Geral\Publicado;
use Modules\Botao;
use Modules\Data;
use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Helpers\OrmHelper;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;
use System\Interface\ModelListarInterface;

class BannerModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_COMUNICACAO_LOGIN;

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private Data $dataInicio = new Data(null),
        private Data $dataFinal = new Data(null),
        private Status $status = new Status(null),
        private readonly Botao $publicado = new Botao(null),
        private readonly null|string $empresa = '',
        private readonly null|string $titulo = null,
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dados = $this
            ->campo([
                'id_admin_empresa', 'titulo', 'arquivo_1', 'arquivo_2', 'arquivo_3', 'uuid',
                'data_inicio', 'data_fim', 'status'
            ])
            ->pagina($this->pegarPagina())
            ->where($this->pegarWhere(), false)
            ->order('padrao', !$this->publicado->vazio() ? 'asc' : 'desc')
            ->read();

        $dados->lista = $this->montarRetorno($dados->lista);
        return $dados;
    }

    private function pegarWhere()
    {
        $where = [];
        $publicadoVazio = $this->publicado->vazio();
        if (!$publicadoVazio && $this->publicado->valor() == 'sim') {
            $where[] = [
                ['data_inicio', '<=', hoje()],
                ['data_fim', '>=', hoje()],
                ['status', 1]
            ];
        } elseif (!$publicadoVazio && $this->publicado->valor() == 'nao') {
            $where[] = [
                'OR',
                ['data_inicio', '>', hoje()],
                ['data_fim', '<', hoje()],
                ['status', '!=', 1]
            ];
        }
        if (!empty($this->empresa)) {
            $ormEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
            $id = $ormEmpresa->pegarIdPeloUuid($this->empresa);

            if ($id && !empty($this->publicado)) {
                $where[] = [
                    'OR',
                    ['id_admin_empresa', 'json', $id],
                    ['padrao', '1']
                ];
            } else {
                $where[] = ['id_admin_empresa', 'json', $id];
            }
        }
        if (!empty($this->titulo)) {
            $where[] = ['titulo', 'like', $this->titulo . '%'];
        }
        if ($this->dataInicio->valido() && $publicadoVazio) {
            $where[] = ['data_inicio', '<=', $this->dataInicio->date()];
        }
        if ($this->dataFinal->valido() && $publicadoVazio) {
            $where[] = ['data_final', '>=', $this->dataFinal->date()];
        }
        if ($this->status->valido() && $publicadoVazio) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function montarRetorno(array $banner): array
    {
        if (empty($banner)) {
            return $banner;
        }

        $Status = new Status();

        $retorno = [];
        foreach ($banner as $r) {
            $statusAtual = $Status->indice($r->status);
            $publicado = (
                new Publicado(
                    new Data($r->data_inicio),
                    new Data($r->data_fim),
                    $statusAtual == Status::ATIVO
                )
            )->indice();

            $retorno[] = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'url'    => [
                    arquivoPrivado($r->arquivo_1),
                    arquivoPrivado($r->arquivo_2),
                    arquivoPrivado($r->arquivo_3)
                ],
                'publicado' => $publicado,
                'status'    => $statusAtual
            ];
        }
        return $retorno;
    }
}
