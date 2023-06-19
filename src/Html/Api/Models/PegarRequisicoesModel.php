<?php

use Route\Route;

final class PegarRequisicoesModel
{
    private array $rota = [];
    public function __construct()
    {
        $this->pegarListaRota();
    }
    public function listar()
    {
        return $this->rota;
    }
    private function pegarListaRota()
    {
        require_once ROOT . '/routes/ApiRoute.php';
        $rota = Route::pegarTodasRotas()['rota'];
        $this->montarRota($rota['GET'] ?? []);
        $this->montarRota($rota['POST'] ?? []);
        $this->montarRota($rota['PUT'] ?? []);
        $this->montarRota($rota['DELETE'] ?? []);
        $this->ordenarRota();
    }

    private function montarRota($dado)
    {
        foreach ($dado as $ind => $r) {
            $grupo = $this->formatarNomeGrupo($r['grupo']);
            if (empty($grupo)) {
                continue;
            }
            if (!array_key_exists($grupo, $this->rota)) {
                $this->rota[$grupo] = (object)[
                    'grupo' => $grupo,
                    'rota' => []
                ];
            }
            $this->rota[$grupo]->rota[] = (object) [
                'id' => $r['metodo'] . '.' . $ind,
                'uri' => $r['uri'],
                'metodo' => $r['metodo'],
                'request' => $r['request']
            ];
        }
    }
    private function formatarNomeGrupo($nome)
    {
        return trim(strCaixaAltaAlta(
            str_replace('_', ' ', preg_replace("/(\G(?!^)|\b[a-zA-Z][a-z]*)([A-Z][a-z]*|\d+)/", '_', $nome))
        ));
    }
    private function ordenarRota()
    {
        ksort($this->rota);
    }
}
