<?php

namespace App\Models\Site\Loja;

use App\Helpers\ClubeApiHelper;

final class FavoritoModel extends ClubeApiHelper
{
    private array $lista;

    public function __construct()
    {
        parent::__construct();
        if (!sessaoExiste('LOJA_FAVORITO') || empty(sessao('LOJA_FAVORITO'))) {
            sessao('LOJA_FAVORITO', $this->buscarFavorito());
        }
        $this->lista = sessao('LOJA_FAVORITO');
    }

    private function buscarFavorito(): array
    {
        $dado = $this->get('/parceiro-favorito')->object();
        if (!chaveExiste('dado', $dado) || !$dado->dado) {
            $dado->dado = [];
        }
        $retorno = [];
        foreach ($dado->dado as $r) {
            $retorno[$r->parceiro] = true;
        }
        return $retorno;
    }

    public function favorito(string $id)
    {
        return array_key_exists($id, $this->lista) ? 'sim' : 'nao';
    }

    public function add(string $id)
    {
        $lista = $this->lista;
        $lista[$id] = true;
        $this->lista = $lista;
        sessao('LOJA_FAVORITO', $lista);
    }

    public function remover(string $id)
    {
        $lista = $this->lista;
        if (!array_key_exists($id, $lista)) {
            return;
        }
        unset($lista[$id]);
        $this->lista = $lista;
        sessao('LOJA_FAVORITO', $lista);
    }
}
