<?php

namespace App\Models\Api\ComunicacaoLogin;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Modules\Quantidade;
use App\Classes\Geral\Status;
use System\Trait\Model\OrdemTrait;
use System\Trait\Model\PaginaTrait;
use App\Classes\ComunicacaoLogin\Ordem;
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
