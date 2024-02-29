<?php

namespace App\Models\Api\Votacao\Usuario;

use ORM\ORM;
use Modules\Botao;
use Helpers\OrmHelper;
use App\Models\Api\Votacao\Trait\idVotacaoTrait;

final class ValidarModel extends ORM
{
    use idVotacaoTrait;

    protected string $ormTabela = TABELA_VOTACAO_USUARIO;
    public Botao $votou;
    private int $idVotacao;
    private int $idUsuario;

    public function __construct(
        private string $usuario,
        private string $votacao
    ) {
        parent::__construct();

        $this->validarCampo();
        $this->idVotacao = $this->idVotacao($this->votacao);
        $this->idUsuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarIdPeloUuid($this->usuario);
        $this->buscar();
    }

    private function validarCampo()
    {
        if (empty($this->usuario) || empty($this->votacao)) {
            mensagemStatus(400);
        }
    }

    private function buscar()
    {
        $this->votou = new Botao($this->existe([
            ['id_votacao_dado', $this->idVotacao],
            ['id_usuario_cliente', $this->idUsuario]
        ]) ? 'sim' : 'nao');
    }
}
