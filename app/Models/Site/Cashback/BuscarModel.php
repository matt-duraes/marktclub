<?php

namespace App\Models\Site\Cashback;

use stdClass;
use App\Classes\Geral\Status;
use App\Helpers\ClubeApiHelper;
use App\Helpers\Site\TextoHelper;

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
            ->get('/parceiro-cashback/' . $this->url)
            ->object();

        if ($dado->dado->status != Status::ATIVO) {
            mensagemStatus(404);
        }
        return $this->montarRetorno($dado->dado);
    }

    private function montarRetorno($r): stdClass
    {
        $Texto = new TextoHelper();
        return (object)[
            'id'              => $r->id,
            'titulo'          => $r->titulo,
            'logo'            => $r->logo,
            'texto_descricao' => $r->texto_descricao,
            'texto_outro'     => $Texto->destaque($r->texto_outro),
            'texto_restricao' => $r->texto_restricao,
            'desconto'        => $r->comissao_minima,
            'link'            => $r->link_site . '&clickref=' . sessao('USUARIO.id'),
            'url'             => $r->url
        ];
    }
}
