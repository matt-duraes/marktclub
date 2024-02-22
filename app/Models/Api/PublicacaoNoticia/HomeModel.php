<?php

namespace App\Models\Api\PublicacaoNoticia;

use Where\Where;
use App\Models\Api\PublicacaoHome\HomeEntity;

final class HomeModel extends GeralModel
{
    public array $noticia = [];
    private ?HomeEntity $Home;

    public function __construct()
    {
        parent::__construct();
        $this->pegarHome();
        $this->buscarNoticia();
    }

    private function pegarHome()
    {
        try {
            $this->Home = new HomeEntity();
        } catch (\Throwable $e) {
            $this->Home = null;
        }
    }

    private function buscarNoticia()
    {
        if (is_null($this->Home) || !$this->Home->listaId) {
            return;
        }
        $dado = $this->campo(self::CAMPO)->where($this->pegarWhere())->read();
        $dado = $this->ordenarNoticia($dado);
        $this->noticia = $this->montardado($dado);
    }

    private function pegarWhere(): Where
    {
        $Where = new Where($this, $this->ormWherePadrao);
        $Where
            ->publicadoSim()
            ->manual(['uuid', 'in', $this->Home->listaId]);
        return $Where;
    }

    private function ordenarNoticia(array $dado): array
    {
        $lista = [];
        foreach ($dado as $r) {
            $lista[$r->uuid] = $r;
        }
        $Home = $this->Home;
        $ordem = [];
        if (array_key_exists($Home->noticia_1, $lista)) {
            $ordem[] = $lista[$Home->noticia_1];
        }
        if (array_key_exists($Home->noticia_2, $lista)) {
            $ordem[] = $lista[$Home->noticia_2];
        }
        if (array_key_exists($Home->noticia_3, $lista)) {
            $ordem[] = $lista[$Home->noticia_3];
        }
        return $ordem;
    }
}
