<?php

namespace App\Models\Api\ParceiroFavorito;

use ORM\Entity;
use App\Models\Api\ParceiroLoja\LojaEntity;

final class FavoritoEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_FAVORITO;
    protected array $ormSalvar = ['id_usuario_cliente', 'id_parceiro_loja'];
    private int $idUsuario;
    protected int $id_usuario_cliente;
    protected int $id_parceiro_loja;

    public function __construct(
        private ?LojaEntity $Parceiro = null
    ) {
        $this->idUsuario = 1;
        parent::__construct();
    }

    protected function regraSalvar()
    {
        $idLoja = $this->Parceiro->get('id');
        try {
            $this->buscar([
                ['id_usuario_cliente', $this->idUsuario],
                ['id_parceiro_loja', $idLoja]
            ]);
            $this->cancelarSalvar();
        } catch (\Throwable) {
            $this->id_usuario_cliente = $this->idUsuario;
            $this->id_parceiro_loja = $idLoja;
        }
    }
}
