<?php

namespace App\Models\Painel\AppGeral;

use ORM\ORM;
use stdClass;

abstract class AppGeralModel extends ORM implements PainelModelInterface
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function listarIndex(
        ?string $pesquisa = null,
        ?array $filtro = null,
        ?string $ordem = null,
        ?int $pagina = 1,
        ?stdClass $config = null
    ) {
        $where = $this->pegarWhere($pesquisa, $filtro);
        $order = $this->pegarOrdem($ordem, $config->ordem->padrao, $config->ordem->lista);
        $order = is_string($order[0]) ? [$order] : $order;
        $pagina = $this->pegarPagina($pagina);
        $dado = $this->campo($config->index->campo)->where(
            where: $where,
            obrigatorio: false
        )->order($order)->pagina(
            pagina: $pagina,
            quantidade: $this->pegarQuantidade()
        )->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }
    protected function pegarWhere(?string $pesquisa, ?array $filtro)
    {
        if (!empty($pesquisa)) {
            return $this->montarWhereDaPesquisa($pesquisa);
        }
        if (!empty($filtro)) {
            return $this->montarWhereDoFiltro($filtro);
        }
        return $this->montarWherePadrao();
    }
    protected function montarWhereDaPesquisa(string $pesquisa): array
    {
        return [];
    }
    protected function montarWhereDoFiltro(array $pesquisa): array
    {
        return [];
    }
    protected function montarWherePadrao(): array
    {
        return [];
    }
    protected function montarRetorno(array $lista)
    {
        return [];
    }

    /*
    |--------------------------------------------------------------------------
    | ORDER
    |--------------------------------------------------------------------------
    */
    protected function pegarOrdem(?string $ordem, string $padrao, array $lista)
    {
        $indice = $ordem ? $ordem : $padrao;
        $indice = base64Encode($indice, 'ordem');
        if (isset($lista[$indice])) {
            return [$lista[$indice][1], $lista[$indice][2]];
        }
        return ['id', 'DESC'];
    }
    public function ordenarLista(?array $lista, int $pagina)
    {
        if (empty($lista)) {
            return;
        }

        $ordem = (($pagina - 1) * 50) + 1;
        foreach ($lista as $id) {
            $this->where(['uuid', $id])->dado([
                'ordem' => $ordem
            ])->update();
            $ordem++;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINA
    |--------------------------------------------------------------------------
    */
    protected function pegarPagina(?int $pagina = 1): int
    {
        if (is_null($pagina)) {
            return 1;
        }
        return preg_match('/^[1-9]{1}[0-9]{0,}$/', $pagina) ? (int)$pagina : 1;
    }
    protected function pegarQuantidade(): int
    {
        return 50;
    }

    /*
    |--------------------------------------------------------------------------
    | DELETAR
    |--------------------------------------------------------------------------
    */
    public function deletarLista(string | array $id, ?string $campo, ?int $status)
    {
        if (empty($id)) {
            return;
        } elseif (is_string($id)) {
            $id = [$id];
        } elseif (!is_array($id)) {
            return;
        }
        if ($status == null) {
            return $this->deletarRealmenteOsItens($id, $campo);
        }
        return $this->deletarOsItensMudandoStatus($id, $status);
    }

    private function deletarRealmenteOsItens($lista, $campo)
    {
        $campo = $campo == null ? 'uuid' : $campo;
        foreach ($lista as $id) {
            $this->where([$campo, $id])->delete();
        }
    }

    private function deletarOsItensMudandoStatus($lista, $status)
    {
        foreach ($lista as $id) {
            $this->dado(['status' => $status])->where(['uuid', $id])->update();
        }
    }
}
