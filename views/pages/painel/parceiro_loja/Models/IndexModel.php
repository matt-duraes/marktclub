<?php

namespace Painel\ParceiroLoja\Models;

use stdClass;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\Status;
use System\Interface\PainelIndexFiltroInterface;
use System\Interface\PainelIndexRetornoInterface;

final class IndexModel implements
    PainelIndexFiltroInterface,
    PainelIndexRetornoInterface
{
    public function filtro(array $filtro, string $pesquisa, string $ordem, int $pagina): array
    {
        $vazio = empty($filtro) && empty($pesquisa);
        if (!$vazio && (!array_key_exists('status', $filtro) || empty($filtro['status']))) {
            $filtro['status'] = 'todos';
        }
        if ($vazio && sessao('USUARIO.gerente') != 'sim') {
            $filtro['equipe'] = sessao('USUARIO.id');
        }
        if (empty($ordem)) {
            $filtro['ordem'] = Ordem::PAINEL_ASC;
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
            } elseif ($r->status == Status::PROSPECCAO) {
                $dataAtencao = !empty($r->data_prospeccao)
                ? dataBr($r->data_prospeccao) . ' - ' . dataDiferencaDia($r->data_prospeccao, hoje()) . ' dias'
                : 'Sem data';
            } elseif ($r->status == Status::PROBLEMA) {
                $dataAtencao = !empty($r->data_problema)
                ? dataBr($r->data_problema) . ' - ' . dataDiferencaDia($r->data_problema, hoje()) . ' dias'
                : 'Sem data';
            }
            $r->data_atencao = $dataAtencao;
            $retorno[] = $r;
        }
        $dado->dado->lista = $retorno;
        return $dado;
    }
}
