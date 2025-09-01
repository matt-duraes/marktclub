<?php

namespace App\Models\Api\ParceiroLoja\Trait;

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;

trait MontarRetornoTrait
{
    private function montarRetorno($lista): array
    {
        if (!$lista) {
            return [];
        }

        $retorno = [];
        $Status = new Status();
        $Tipo = new TipoLoja();

        foreach ($lista as $r) {
            $tipo = $Tipo->indice($r->tipo_loja);
            $desconto = $r->desconto;
            if ($tipo == TipoLoja::CASHBACK) {
                $desconto = !empty($r->comissao_minima) ? number_format($r->comissao_minima, 2, '.') : '';
            }
            $retorno[$r->id] = [
                'id'              => $r->uuid,
                'titulo'          => $r->titulo,
                'titulo_interno'  => $r->titulo_interno,
                'razao_social'    => $r->razao_social,
                'desconto'        => $desconto,
                'imagem_logo'     => imagemPrivada($r->imagem_logo),
                'url'             => $r->url,
                'tipo_loja'       => $tipo,
                'data_criacao'    => $r->data_criacao,
                'data_publicacao' => $r->data_publicacao,
                'data_prospeccao' => $r->data_prospeccao,
                'data_auditoria'  => $r->data_auditoria,
                'data_problema'   => $r->data_problema,
                'endereco_estado' => $this->tratarEstados($r->endereco_estado),
                'status'          => $Status->indice($r->status)
            ];
        }
        if (object_key_exists('latitude', $lista[0])) {
            $retorno = $this->montarListaGeolocalizacao($retorno, $lista);
        }
        return array_values($retorno);
    }

    private function tratarEstados($estadosParceiro): string
    {
        $estados = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA',
            'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN',
            'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];
        $estadosParceiro = jsonDecode($estadosParceiro, true, true);

        if (!is_array($estadosParceiro)) {
            return '';
        }

        $estadosFaltantes = array_diff($estados, $estadosParceiro);
        $quantidadeFaltante = count($estadosFaltantes);

        if ($quantidadeFaltante === 0) {
            return 'Todos os estados';
        }

        if ($quantidadeFaltante <= 5) {
            return 'Todos exceto ' . implode(', ', $estadosFaltantes);
        }

        return implode(', ', $estadosParceiro);
    }
}
