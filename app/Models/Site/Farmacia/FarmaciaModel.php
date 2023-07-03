<?php

namespace App\Models\Site\Farmacia;

use stdClass;
use Helpers\ApiHelper;
use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Status;
use App\Models\Site\ListarInterface;
use App\Classes\ParceiroLoja\Estabelecimento;

final class FarmaciaModel extends ApiHelper implements ListarInterface
{
    public function __construct()
    {
        parent::__construct(scope: 'parceiro_loja:listar parceiro_loja:buscar');
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function listarDados(int $quantidade = 20): stdClass
    {
        $dado = $this
            ->json([
                'pagina' => 1,
                'quantidade' => $quantidade,
                'estabelecimento' => Estabelecimento::FISICO,
                'tipo' => Tipo::FARMACIA,
                'status' => Status::CONCLUIDO,
            ])
            ->get('/parceiro-loja')
            ->object();

        return (object)[
            'tipo' => 'farmacia',
            'lista' => $this->montarLista($dado->dado->lista),
            'paginacao' => $dado->dado->pagina
        ];
    }

    private function montarLista(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'       => uuid(),
                'titulo'   => $r->titulo,
                'link'     => route('farmacia.detalhe') . '/' . $r->url,
                'imagem'   => $r->imagem,
                'desconto' => $r->desconto
            ];
        }
        return $retorno;
    }

    /*
    |--------------------------------------------------------------------------
    | BUSCAR
    |--------------------------------------------------------------------------
    */
    public function buscarDados($url)
    {
        $dado = $this
            ->get('/parceiro-loja/' . $url)
            ->object();
        if ($dado->dado->status != Status::CONCLUIDO) {
            mensagemStatus(404);
        }
        return $this->montarRetorno($dado->dado);
    }
    private function montarRetorno($dado)
    {
        return (object)[
            'id' => $dado->id ?? '',
            'titulo' => $dado->titulo ?? '',
            'logo' => $dado->link_logo ?? '',
            'texto_desconto' => $dado->texto_desconto ?? '',
            'texto_procedimento' => $dado->texto_procedimento ?? '',
            'texto_descricao' => 'Texto de descrição padrão',
            'capa_desktop' => 'https://clube.marktclub.com.br/images/tem_mais_saude_carteirinha.png',
            'capa_mobile' => 'https://clube.marktclub.com.br/images/tem_mais_saude_carteirinha.png',
            'procedimento' => 'voucher',
            'endereco' => [],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELACIONADO
    |--------------------------------------------------------------------------
    */
    public function relacionado($id)
    {
        return $this->listarDados(3);
    }
}
