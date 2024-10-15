<?php

namespace App\Models\Api\ParceiroFavorito;

use App\Models\Api\ParceiroLoja\LojaEntity;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\Entity;

class FavoritoEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_FAVORITO;
    protected array $ormSalvar = [
        'id_usuario_cliente', 'id_parceiro_loja'
    ];
    protected int $id_usuario_cliente;
    protected int $id_parceiro_loja;
    private int $idUsuario;
    private int $idLoja;

    public function __construct(
        private readonly ?LojaEntity $Parceiro = null
    ) {
        if (!empty($this->Parceiro)) {
            $this->idLoja = $this->Parceiro->get('id');
        }
        $this->idUsuario = TOKEN['usuario']->id;
        parent::__construct();
    }

    /**
     * @return void
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        if (!$this->verificaFavoritado()) {
            mensagemErro('Ação duplicada', 'Loja já está favoritada');
        }
        $this->id_usuario_cliente = $this->idUsuario;
        $this->id_parceiro_loja = $this->idLoja;
    }

    /**
     * @return bool
     */
    private function verificaFavoritado(): bool
    {
        $OrmHelper = new ORMHelper($this->ormTabela);
        $favorito = $OrmHelper->pegarCampoPor('id', [
            ['id_usuario_cliente', $this->idUsuario],
            ['id_parceiro_loja', $this->idLoja]
        ]);
        return empty($favorito);
    }
}
