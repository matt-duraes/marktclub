<?php

namespace Painel\ParceiroLoja\Models;

use stdClass;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use System\Interface\PainelIndexFiltroInterface;
use System\Interface\PainelIndexRetornoInterface;

final class IndexModel implements
    PainelIndexFiltroInterface,
    PainelIndexRetornoInterface
{
    public function filtro(array $filtro, bool $pesquisa): array
    {
        if (empty($filtro) && !$pesquisa && !array_key_exists('equipe', $filtro) && sessao('USUARIO.gerente') != 'sim') {
            $filtro['equipe'] = sessao('USUARIO.id');
        }
        if (!array_key_exists('tipo_loja', $filtro)) {
            $filtro['tipo_loja'] = TipoLoja::LOJA;
        }
        if (!array_key_exists('ordem', $filtro)) {
            $filtro['ordem'] = Ordem::PAINEL;
        }
        return $filtro;
    }

    public function retorno(stdClass $dado): stdClass
    {
        $retorno = [];
        foreach ($dado->dado->lista as $r) {
            $dataAtencao = '-';
            if ($r->status == Status::CONCLUIDO) {
                $dataAtencao = !empty($r->data_auditoria)
                ? dataBr($r->data_auditoria) . ' - ' . dataDiferencaDia($r->data_auditoria, hoje()) . ' dias'
                : 'Sem auditoria';
            } elseif($r->status == Status::PROSPECCAO) {
                $dataAtencao = !empty($r->data_criacao)
                ? dataBr($r->data_criacao) . ' - ' . dataDiferencaDia($r->data_criacao, hoje()) . ' dias'
                : 'Sem data';
            }
            $r->data_atencao = $dataAtencao;
            $retorno[] = $r;
        }
        $dado->dado->lista = $retorno;
        return $dado;
    }
}
