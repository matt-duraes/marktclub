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
        if (is_array($filtro) && array_key_exists('data', $filtro) && validarDate($filtro['data'])) {
            $parametro['data'] = $filtro['data'];
        } else {
            $parametro['data'] = hoje();
        }
        return $parametro;
    }
}
