<?php

namespace App\Models\Api\ParceiroFavorito;

use ORM\ORM;

final class FavoritoModel extends ORM
{
    protected string $ormTabela = TABELA_PARCEIRO_FAVORITO;
    private int $idUsuario;

    public function __construct(
    ) {
        $this->idUsuario = 1;
        parent::__construct();
    }

    public function pegarListaParceiro(): array
    {
        $dado = $this->campo(['id_parceiro_loja'])->where(['id_usuario_cliente', $this->idUsuario])->read();
        return $this->montarRetorno($dado);
    }

    private function montarRetorno($lista)
    {
        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = $r->id_parceiro_loja;
        }
        return $retorno;
    }
}
