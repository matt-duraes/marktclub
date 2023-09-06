<?php

namespace App\Models\Site\Automovel;

use stdClass;
use Modules\Dinheiro;
use Helpers\MarkdownHelper;
use App\Classes\Geral\Status;
use App\Helpers\ClubeApiHelper;
use App\Classes\ParceiroLoja\Procedimento;

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
        $Texto = new MarkdownHelper();
        return (object)[
            'id'                        => $r->id,
            'titulo'                    => $r->titulo,
            'parceiro'                  => (object)[
                'id' => $r->parceiro->id,
            ],
            'versao'                    => $this->montarVersao($r->versao, $r->imagem),
            'texto_procedimento'        => $Texto->texto($r->texto_procedimento),
            'procedimento'              => $r->procedimento,
            'procedimento_cheque_bonus' => $r->procedimento == Procedimento::CHEQUE_BONUS,
            'procedimento_declaracao'   => $r->procedimento == Procedimento::DECLARACAO,
            'procedimento_voucher'      => $r->procedimento == Procedimento::VOUCHER,
            'endereco'                  => '',
        ];
    }

    private function montarVersao($dado, $imagem)
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
                'cor'         => $r->cor,
                'imagem'      => $imagem,
                'tipo'        => 'automovel-versao'
            ];
        }
        return $retorno;
    }
}
