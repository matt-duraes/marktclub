<?php

namespace App\Models\Site\Loja;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Helpers\Site\TextoHelper;
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
            ->validar(mensagem: 'Página não encontrada', status: 404, login: true)
            ->get('/parceiro-loja/' . $this->url)
            ->object();
        if ($dado->dado->status != Status::CONCLUIDO) {
            mensagemStatus(404);
        }
        return $this->montarRetorno($dado->dado);
    }

    private function montarRetorno($r): stdClass
    {
        $Texto = new TextoHelper();
        return (object)[
            'id'                 => $r->id,
            'titulo'             => $r->titulo,
            'logo'               => $r->link_logo,
            'texto_desconto'     => $Texto->destaque($r->texto_desconto),
            'texto_procedimento' => $Texto->destaque($r->texto_procedimento),
            'texto_descricao'    => nl2br($r->texto_descricao),
            'procedimento'       => $r->procedimento,
            'capa_desktop'       => $r->link_capa_desktop,
            'capa_mobile'        => $r->link_capa_mobile,
            'link'               => $r->link_site,
            'url'                => $r->url,
            'endereco'           => '',
        ];
    }
}
