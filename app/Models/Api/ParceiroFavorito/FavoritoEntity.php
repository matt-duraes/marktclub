<?php

namespace App\Models\Api\ParceiroFavorito;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\Entity;

class FavoritoEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_PARCEIRO_FAVORITO;
    protected array $ormSalvar = [
        'id_usuario_cliente', 'id_parceiro_loja'
    ];
    protected int $id_usuario_cliente;
    protected int $id_parceiro_loja;

    /**
     * @param string|null $parceiro
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly ?string $parceiro = null
    ) {
        parent::__construct();
        if (empty($this->parceiro)) {
            return;
        }
        $this->pegarUsuario();
        $this->pegarParceiro();
    }

    /**
     * @throws Excecao
     */
    private function pegarUsuario(): void
    {
        if (!array_key_exists('usuario', TOKEN) || vazio(TOKEN['usuario'])) {
            mensagemErro(
                'Usuário não foi encontrado',
                'Não foi possível econtrar um usuário'
            );
        }
        $this->id_usuario_cliente = TOKEN['usuario']->id;
    }

    /**
     * @throws Excecao
     */
    private function pegarParceiro(): void
    {
        $ormHelper = new ORMHelper(TABELA_PARCEIRO_LOJA);
        $idParceiro = $ormHelper->pegarIdPeloUuid($this->parceiro);
        if (empty($idParceiro)) {
            mensagemErro(
                'Parceiro não foi encontrado',
                'Não foi possível econtrar um parceiro'
            );
        }
        $this->id_parceiro_loja = $idParceiro;
    }

    /**
     * @throws Excecao
     */
    protected function regraSalvar(): void
    {
        $this->verificaFavoritado();
    }

    /**
     * @throws Excecao
     */
    private function verificaFavoritado(): void
    {
        $ormHelper = new ORMHelper($this->ormTabela);
        $favorito = $ormHelper->pegarPrimeiroRegistro([
            ['id_usuario_cliente', $this->id_usuario_cliente],
            ['id_parceiro_loja', $this->id_usuario_cliente]
        ], ['id']);

        if (!empty($favorito)) {
            mensagemErro('Ação duplicada', 'Loja já está favoritada');
        }
    }
}
