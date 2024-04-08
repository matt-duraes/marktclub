<?php

namespace App\Models\Site\Loja;

use stdClass;
use Helpers\MarkdownHelper;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;

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
        $Texto = new MarkdownHelper();
        return (object)[
            'id'                 => $r->id,
            'titulo'             => $r->titulo,
            'logo'               => $r->imagem_logo,
            'texto_desconto'     => $r->texto_desconto,
            'texto_procedimento' => $r->texto_procedimento,
            'texto_desconto'     => $Texto->texto($r->texto_desconto),
            'texto_procedimento' => $Texto->texto($r->texto_procedimento),
            'texto_descricao'    => nl2br($r->texto_descricao),
            'procedimento'       => $r->tipo_procedimento,
            'capa_desktop'       => $r->imagem_capa_desktop,
            'capa_mobile'        => $r->imagem_capa_mobile,
            'link'               => $r->link_site,
            'url'                => $r->url,
            'arquivo'            => $r->arquivo_clube,
            'tipo'               => (new TipoLoja($r->tipo_loja))->indice(),
        ];
    }
}
