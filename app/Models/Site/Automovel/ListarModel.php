<?php

namespace App\Models\Site\Automovel;

use stdClass;
use App\Classes\Geral\Status;
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
        $dado = $this->json([
            'pagina'   => 1,
            'parceiro' => $this->url,
            'status'   => Status::ATIVO
        ])->get('/automovel-modelo')->object();

        return (object)[
            'tipo'      => 'automovel-modelo',
            'lista'     => $this->montarDado($dado->dado->lista ?? []),
            'paginacao' => $dado->dado->pagina,
        ];
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'       => $r->id,
                'titulo'   => $r->titulo,
                'texto'    => '',
                'link'     => route('automovel.versao') . '/' . $r->url,
                'imagem'   => $r->imagem,
                'tipo'     => 'automovel-modelo'
            ];
        }
        return $retorno;
    }
}
