<?php

namespace App\Models\Site\Turismo;

use Modules\Botao;
use App\Helpers\ClubeApiHelper;

final class PromocaoModel extends ClubeApiHelper
{
    public array $retorno = [];

    public function __construct(
        private string $parceiro
    ) {
        parent::__construct();
        $this->listarDados();
    }

    private function listarDados(): void
    {
        $dado = $this
            ->json([
                'pagina'     => 1,
                'quantidade' => 6,
                'parceiro'   => $this->parceiro,
                'publicado'  => Botao::SIM
            ])
            ->get('/parceiro-campanha')
            ->object();

        if (existeErro($dado, 'dado')) {
            return;
        }
        $this->retorno = $this->montarDado($dado->dado->lista);
        return;
    }

    private function montarDado($dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = (object)[
                'id'             => $r->id,
                'titulo'         => $r->titulo,
                'texto'          => $r->texto,
                'imagem_desktop' => $r->imagem_desktop,
                'imagem_mobile'  => !empty($r->imagem_mobile) ? $r->imagem_mobile : $r->imagem_desktop,
                'link'           => LINK . '/turismo/redirecionar-campanha/' . $r->id
            ];
        }
        return $retorno;
    }
}
