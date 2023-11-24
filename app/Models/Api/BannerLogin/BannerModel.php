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
                'id_admin_empresa', 'titulo', 'url_1', 'url_2', 'url_3', 'uuid'
            ])
            ->pagina($this->pegarPagina())
            ->where($this->ormWherePadrao, false)
            ->order('padrao', 'desc')
            ->read();

        $dados->lista = $this->montarRetorno($dados->lista);
        return $dados;
    }

    private function montarRetorno(array $carteirinhas): array
    {
        if (empty($carteirinhas)) {
            return $carteirinhas;
        }

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
                'id_admin_empresa', 'titulo', 'url_1', 'url_2', 'url_3', 'uuid'
            ])
            ->where([
                array_merge(['OR'], $this->pegarWhereEmpresa($id))
            ])
            ->order('padrao', 'asc')
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

    private function pegarWhereEmpresa($id): array
    {
        $where = [];
        if (!empty($id)) {
            $where[] = ['id_admin_empresa', 'json', $id];
        }
        $where[] = ['padrao', '1'];
        return $where;
    }
}
