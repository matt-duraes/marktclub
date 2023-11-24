<?php

namespace App\Models\Api\ComunicacaoLogin;

use ORM\ORM;
use stdClass;
use Modules\Pagina;
use Helpers\OrmHelper;
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
        private readonly null|string $empresa = '',
        private readonly null|string $publicado = ''
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dados = $this
            ->campo([
                'id_admin_empresa', 'titulo', 'arquivo_1', 'arquivo_2', 'arquivo_3',
                'uuid', 'data_inicio', 'data_fim'
            ])
            ->pagina($this->pegarPagina())
            ->where($this->pegarWhere(), false)
            ->order('padrao', !empty($this->publicado) ? 'asc' : 'desc')
            ->read();

        $dados->lista = $this->montarRetorno($dados->lista);
        return $dados;
    }

    private function pegarWhere()
    {
        if (!empty($this->publicado)) {
            return $this->pegarWherePublicado();
        }

        return [];
    }

    private function montarRetorno(array $banners): array
    {
        if (empty($banners)) {
            return $banners;
        }

        $retorno = [];
        foreach ($banners as $r) {
            if (!$this->validarData($r->data_inicio, $r->data_fim) && !empty($this->publicado)) {
                continue;
            }

            $retorno[] = [
                'id'     => $r->uuid,
                'titulo' => $r->titulo,
                'url'    => [
                    arquivoPrivado($r->arquivo_1),
                    arquivoPrivado($r->arquivo_2),
                    arquivoPrivado($r->arquivo_3)
                ],
            ];
        }
        return $retorno;
    }

    private function validarData($data_inicio, $data_fim)
    {
        if (empty($data_inicio) || empty($data_fim)) {
            return true;
        }

        $data_inicio = strtotime($data_inicio);
        $data_fim = strtotime($data_fim);
        $data_atual = strtotime(date('Y-m-d H:i:s'));

        return $data_atual >= $data_inicio && $data_atual <= $data_fim;
    }

    private function pegarWherePublicado(): array
    {
        $ormEmpresa = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        $id = $ormEmpresa->pegarIdPeloUuid($this->empresa);

        $where = [];
        if (!empty($id)) {
            $where[] = ['id_admin_empresa', 'json', $id];
        }
        $where[] = ['padrao', '1'];

        return array_merge(['OR'], $where);
    }
}
