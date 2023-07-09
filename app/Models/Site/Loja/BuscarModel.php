<?php

namespace App\Models\Site\Loja;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Status;

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
            ->get('/parceiro-loja/' . $this->url)
            ->object();

        if ($dado->dado->status != Status::CONCLUIDO) {
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
            'texto_desconto'     => $r->texto_desconto,
            'texto_procedimento' => $r->texto_procedimento,
            'texto_descricao'    => $r->texto_descricao,
            'procedimento'       => $r->procedimento,
            'capa_desktop'       => $r->link_capa_desktop,
            'capa_mobile'        => $r->link_capa_mobile,
            'url'                => $r->url,
            'endereco'           => '',
        ];
    }
}
