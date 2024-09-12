<?php

namespace App\Models\Api\Votacao\Usuario;

use App\Models\Api\Votacao\Trait\idVotacaoTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\Botao;
use ORM\ORM;

final class ValidarModel extends ORM
{
    use idVotacaoTrait;

    public Botao $votou;
    protected string $ormTabela = TABELA_VOTACAO_USUARIO;
    private int $idVotacao;
    private int $idUsuario;

    /**
     * @param string $usuario
     * @param string $votacao
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly string $usuario,
        private readonly string $votacao
    ) {
        parent::__construct();

        $this->validarCampo();
        $this->setarId();
        $this->buscar();
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function validarCampo(): void
    {
        if (empty($this->usuario) || empty($this->votacao)) {
            mensagemStatus(400);
        }
    }

    /**
     * @return void
     */
    private function setarId(): void
    {
        $this->idVotacao = $this->idVotacao($this->votacao);
        $this->idUsuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarIdPeloUuid($this->usuario);
    }

    /**
     * @return void
     * @throws Excecao
     */
    private function buscar(): void
    {
        $this->votou = new Botao(
            $this->existe([
                ['id_votacao_dado', $this->idVotacao],
                ['id_usuario_cliente', $this->idUsuario]
            ]) ? Botao::SIM : Botao::NAO
        );
    }
}
