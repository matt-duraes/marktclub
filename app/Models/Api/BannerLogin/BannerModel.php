<?php

namespace App\Models\Api\BannerLogin;

use App\Classes\BannerLogin\Ordem;
use App\Classes\Geral\Status;
use Helpers\OrmHelper;
use Modules\Pagina;
use Modules\Quantidade;
use ORM\ORM;
use stdClass;
use System\Interface\ModelListarInterface;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use System\Trait\Model\QuantidadeTrait;

class BannerModel extends ORM implements
    ModelListarInterface
{
    use PaginaTrait;
    use QuantidadeTrait;
    use OrdemTrait;

    protected string $ormTabela = TABELA_BANNER_LOGIN;

    public function __construct(
        private readonly Pagina $pagina = new Pagina(),
        private readonly Quantidade $quantidade = new Quantidade(),
        private readonly Ordem $ordem = new Ordem(),
        private readonly Status $status = new Status()
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dados = $this
            ->campo([
                'id_admin_empresa', 'titulo', 'url_1', 'url_2', 'url_3', 'uuid', 'status'
            ])
            ->pagina($this->pegarPagina())
            ->where($this->pegarWhere(), false)
            ->order($this->pegarOrdem(new Ordem()))
            ->read();

        $dados->lista = $this->montarRetorno($dados->lista);
        return $dados;
    }

    private function pegarWhere(): array
    {
        $where = $this->ormWherePadrao;
        if ($this->status->valido()) {
            $where[] = ['status', $this->status->numero()];
        }
        return $where;
    }

    private function montarRetorno(array $carteirinhas): array
    {
        if (empty($carteirinhas)) {
            return $carteirinhas;
        }

        $Status = new Status();
        $retorno = [];
        foreach ($carteirinhas as $r) {
            $retorno[] = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'url'    => [
                    $r->url_1,
                    $r->url_2,
                    $r->url_3
                ],
                'status' => $Status->indice($r->status),
            ];
        }
        return $retorno;
    }

    public function buscarBanner($empresa)
    {
        if (empty($empresa)) {
            return mensagemErro('Erro!', 'Empresa não informada!');
        }

        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $id = $ormHelper->pegarIdPeloUuid($empresa);

        $dados = $this
            ->campo([
                'id_admin_empresa', 'titulo', 'url_1', 'url_2', 'url_3', 'uuid', 'status'
            ])
            ->where([
                array_merge(['OR'], $this->pegarWhereIdEmpresa($id)),
                ['status', 1]
            ])
            ->order('marktclub', 'asc')
            ->read();

        if (empty($dados)) {
            return mensagemErro('Erro!', 'Banner não encontrado!');
        }

        return [
            'id'     => $dados[0]->uuid,
            'titulo' => $dados[0]->titulo,
            'url'    => [
                arquivoPrivado($dados[0]->url_1),
                arquivoPrivado($dados[0]->url_2),
                arquivoPrivado($dados[0]->url_3)
            ],
        ];
    }

    private function pegarWhereIdEmpresa($id): array
    {
        $where = [];
        if (!empty($id)) {
            $where[] = ['id_admin_empresa', 'json', $id];
        }
        $where[] = ['id_admin_empresa', 'json', '[1]'];
        return $where;
    }
}
