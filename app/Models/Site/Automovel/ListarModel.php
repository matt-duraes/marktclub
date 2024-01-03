<?php

namespace App\Models\Site\Automovel;

use stdClass;
use Modules\Botao;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\ListarInterface;

final class ListarModel extends ClubeApiHelper implements ListarInterface
{
    public string $tipo = 'modelo';

    public function __construct(
        protected ?string $url = null
    ) {
        parent::__construct();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->json([
                'pagina'    => 1,
                'parceiro'  => $this->url,
                'publicado' => Botao::SIM
            ])
            ->get('/automovel-modelo')
            ->array()['dado'] ?? [];

        return (object)[
            'tipo'      => 'automovel-modelo',
            'lista'     => $this->montarDado($dado['lista'] ?? []),
            'paginacao' => $dado['pagina'] ?? 0,
        ];
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'       => $r['id'],
                'titulo'   => $r['titulo'],
                'texto'    => '',
                'link'     => route('automovel.versao') . '/' . $this->url . '/' . $r['url'],
                'imagem'   => $r['imagem'],
                'tipo'     => 'automovel-modelo'
            ];
        }
        return $retorno;
    }
}
