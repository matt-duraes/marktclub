<?php

namespace App\Models\Painel\AppGeral;

use stdClass;

interface PainelModelInterface
{
    /**
     * @param null|string   $pesquisa Pesquisa feita pelo usuário
     * @param null|array    $filtro   Lista de filtros realizada na busca
     * @param null|string   $ordem    Ordem que será aplicado a pesquisa
     * @param null|int      $pagina   Página atual da pesquisa
     * @param null|stdClass $config   Config do sistema
     */
    public function listarIndex(
        ?string $pesquisa = null,
        ?array $filtro = null,
        ?string $ordem = null,
        ?int $pagina = 1,
        ?stdClass $config = null
    );

    /**
     * @param string|array $id     ID dos registros a serem deletados
     * @param null|string  $campo  Campo que deseja usar para deletar
     * @param null|int     $status Status caso queira mudar o status no lugar de deletar
     */
    public function deletarLista(string | array $id, ?string $campo, ?int $status);

    /**
     * @param null|array $lista  ID dos registros a serem ordenados
     * @param int        $pagina Número da página
     */
    public function ordenarLista(?array $lista, int $pagina);
}
