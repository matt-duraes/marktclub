<?php

namespace Painel\SolicitacaoPremium\Models;

use stdClass;
use Helpers\ApiHelper;
use System\Interface\PainelIndexBuscarInterface;

final class IndexModel implements PainelIndexBuscarInterface
{
    public function buscar(
        ?int $pagina = null,
        ?string $pesquisa = null,
        ?array $filtro = [],
        ?string $ordem = null
    ): stdClass {
        $dado = (new ApiHelper(token:true))
            ->validar(mensagem: 'Erro ao buscar', status: 400)
            ->json($this->pegarWhere($pagina, $pesquisa, $filtro, $ordem))
            ->get('/solicitacao-premium')->object()->dado;
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($dado)
    {
        $total = 0;
        $ativo = 0;
        $disponivel = 0;
        $validado = 0;
        $cancelado = 0;
        $limite = 0;
        $lista = ['total', 'ativo', 'disponivel', 'validado', 'cancelado', 'limite'];
        foreach ($dado as $r) {
            foreach ($r as $ind => $val) {
                if (!in_array($ind, $lista) || !is_numeric($val)) {
                    continue;
                }
                $$ind += $val;
            }
        }
        if ($dado) {
            $dado[] = [
                'parceiro'   => 'Total',
                'total'      => $total,
                'ativo'      => $ativo,
                'disponivel' => !empty($disponivel) ? $disponivel : '-',
                'validado'   => $validado,
                'cancelado'  => $cancelado,
                'limite'     => !empty($limite) ? $limite : '-',
                'status'     => '-',
            ];
        }
        return retornarPaginacao($dado);
    }

    private function pegarWhere($pagina, $pesquisa, $filtro, $ordem): array
    {
        $parametro = [
            'pagina' => $pagina
        ];
        if (!empty($pesquisa)) {
            $parametro['pesquisa'] = $pesquisa;
        }
        if (!empty($ordem)) {
            $parametro['ordem'] = $ordem;
        }
        if (is_array($filtro) && array_key_exists('data_de', $filtro) && validarDataDate($filtro['data_de'])) {
            $parametro['data_de'] = dataBanco($filtro['data_de']);
        } else {
            $parametro['data_de'] = dataPrimeiroDiaMes(hoje());
        }
        if (is_array($filtro) && array_key_exists('data_ate', $filtro) && validarDataDate($filtro['data_ate'])) {
            $parametro['data_ate'] = dataBanco($filtro['data_ate']);
        } else {
            $parametro['data_ate'] = dataUltimoDiaMes(hoje());
        }
        if (is_array($filtro) && array_key_exists('empresa', $filtro) && !empty($filtro['empresa'])) {
            $parametro['empresa'] = $filtro['empresa'];
        } else {
            $parametro['empresa'] = sessao('USUARIO.empresa')->id;
        }
        return $parametro;
    }
}
