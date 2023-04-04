<?php

namespace Painel\Relatorio\Models;

use stdClass;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use PHPUnit\Framework\Constraint\IsInfinite;

final class MontarRelatorioModel
{
    private array $cor = [

        'verde' => '#4bc0c0',
        'azul' => '#36a2eb',
        'vermelho' => '#fa1142',
        'roxo' => '#9966ff',
        'laranja' => '#ff9f40',
        'amarelo' => '#ffce56',
        'rosa' => '#f4999a',
        'ciano' => '#8dd3c8',
        'marrom' => '#a5771b',
        'verde_escuro' => '#056e2c',
        'azul_escuro' => '#086eb3',
        'vermelho_escuro' => '#c70505',
        'roxo_escuro' => '#5500ff',
        'verde_claro' => '#b2e089',
        'azul_claro' => '#80ccff',
        'vermelho_claro' => '#ff5959',
        'roxo_claro' => '#b793ff',
    ];

    public function montarPizza(array $dado, string $label): array
    {
        $retorno = [
            'label' => [],
            'data' => [
                'dado' => [],
                'cor' => []
            ]
        ];
        $i = 0;

        $cor = array_values($this->cor);
        foreach ($dado as $r) {
            $retorno['label'][] = $r->$label;
            $retorno['data']['dado'][] = $r->total;
            $retorno['data']['cor'][] = $cor[$i];
            $i++;
        }

        return $retorno;
    }

    public function montarLinha(array $dado, string $label, array $campo): array
    {
        return $this->montarGraficoGeral($dado, $label, $campo);
    }

    public function montarBarra(array $dado, string $label, array $campo): array
    {
        return $this->montarGraficoGeral($dado, $label, $campo);
    }

    private function montarGraficoGeral(array $dado, string $label, array $campo): array
    {
        $retorno = [
            'label' => [],
            'data' => []
        ];

        $campoValor = array_values($campo);
        $campoIndice = array_keys($campo);
        $i = 0;
        $cor = array_values($this->cor);
        foreach ($campoValor as $val) {
            $retorno['data'][$i] = [
                'dado' => [],
                'cor' => $cor[$i],
                'label' => $val
            ];
            $i++;
        }

        foreach ($dado as $linha) {
            $retorno['label'][] = $linha->$label;
            $i = 0;
            foreach ($campoIndice as $val) {
                $retorno['data'][$i]['dado'][] = $linha->$val;
                $i++;
            }
        }

        return $retorno;
    }

    public function montarHeaderUsuarioAcesso($dado, $relatorio)
    {
        $total = 0;
        $unico = 0;
        $maior = 0;
        $menor = 9999999999;
        $diaMaior = '';
        $diaMenor = '';


        foreach ($dado as $r) {
            $totalAtual = $r->total;
            $total += $totalAtual;
            $unico += $r->unico;
            if ($totalAtual >= $maior) {
                $maior = $totalAtual;
                $diaMaior = $r->data;
            }
            if ($totalAtual <= $menor) {
                $menor = $totalAtual;
                $diaMenor = $r->data;
            }
        }

        $header = [
            ['Acesso Total', $total],
            ['Acesso Único', $unico],
            ['Dia com mais acesso', $diaMaior],
            ['Dia com menos acesso', $diaMenor],
        ];
        $relatorio['header'] = $header;
        return $relatorio;
    }

    public function montarRelatorioStatus($dado)
    {
        $relatorio = [
            'total' => [
                'numero' => $dado->total
            ],
            'Ativo' => [
                'numero' => 0,
                'porcentagem' => 0
            ],
            'Inativo' => [
                'numero' => 0,
                'porcentagem' => 0
            ],
            'Bloqueado' => [
                'numero' => 0,
                'porcentagem' => 0
            ],
        ];

        foreach ($dado->lista as $r) {
            if ($r->status == 'Ativo') {
                $relatorio['ativo']['numero'] = $r->total;
                $relatorio['ativo']['porcentagem'] = $r->porcentagem;
            } elseif ($r->status == 'Inativo') {
                $relatorio['inativo']['numero'] = $r->total;
                $relatorio['inativo']['porcentagem'] = $r->porcentagem;
            } elseif ($r->status == 'Bloqueado') {
                $relatorio['bloqueado']['numero'] = $r->total;
                $relatorio['bloqueado']['porcentagem'] = $r->porcentagem;
            }
        }

        return $relatorio;
    }

    public function montarRelatorioEstado($dado)
    {
        $lista = $dado->lista;
        if (!$lista) {
            return [];
        }

        $outro = [];
        if ($lista[0]->uf == 'OUTRO') {
            $outro = $lista[0];
            unset($lista[0]);
        }

        $relatorio = $this->montarBarra(
            $lista,
            'uf',
            ['total' => 'Total', 'ativo' => 'Ativo', 'inativo' => 'Inativo', 'bloqueado' => 'Bloqueado']
        );

        $relatorio['header'] = [];
        if ($outro) {
            $relatorio['header'] = [
                ['Usuários sem Estado', $outro->total],
                ['Ativos sem Estado', $outro->ativo],
                ['Inativos sem Estado', $outro->inativo],
                ['Bloqueados sem Estado', $outro->bloqueado],
            ];
        }

        return $relatorio;
    }

    public function montarUsuarioComMaisAcesso($dado)
    {
        if (!$dado) {
            return [];
        }

        $chave = (new ApiHelper(token: true))->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $Crypt = new CryptHelper(chavePrivada: $chave);
        $retorno = [];
        foreach ($dado as $r) {
            $r->usuario = $Crypt->decode($r->usuario);
            $retorno[] = $r;
        }
        return $retorno;
    }
}
