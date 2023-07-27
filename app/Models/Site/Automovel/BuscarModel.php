<?php

namespace App\Models\Site\Automovel;

use stdClass;
use Modules\Dinheiro;
use App\Helpers\ClubeApiHelper;
use App\Classes\Geral\Status;

final class BuscarModel extends ClubeApiHelper
{
    public function __construct(
        private string $url
    ) {
        parent::__construct();
    }

    public function buscarDados(): stdClass
    {
        $dado = $this
            ->validar(mensagem: 'Página não encontrada', status: 404)
            ->get('/automovel-modelo/' . $this->url)
            ->object();

        if ($dado->dado->status != Status::ATIVO) {
            mensagemStatus(404);
        }
        return $this->montarRetorno($dado->dado);
    }

    private function montarRetorno($r): stdClass
    {
        return (object)[
            'id'                 => $r->id,
            'titulo'             => $r->titulo,
            'logo'               => $r->link_logo,
            'versao'             => $this->montarVersao($r->versao),
            'endereco'           => '',
            'texto_procedimento' => '',
            'endereco'           => '',
        ];
    }

    private function montarVersao($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            if ($r->status != Status::ATIVO) {
                continue;
            }
            $retorno[] = (object)[
                'id'          => $r->id,
                'titulo'      => $r->titulo,
                'valor_de'    => (new Dinheiro($r->valor_de))->dinheiro(),
                'valor_por'   => (new Dinheiro($r->valor_por))->dinheiro(),
                'imagem'      => $r->link_logo,
                'tipo'        => 'automovel-versao'
            ];
        }
        return $retorno;
    }
}
