<?php

namespace App\Models\Api\LoginApi;

use ORM\Entity;
use App\Helpers\DigioHelper;
use App\Models\Api\LoginApi\Trait\LinkTrait;
use App\Models\Api\LoginApi\Trait\UsuarioTrait;
use App\Models\Api\LoginApi\Trait\ConstrutorTrait;

final class DigioModel extends Entity
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    use ConstrutorTrait;
    use UsuarioTrait;
    use LinkTrait;

    private string $linkClube;
    private int $idEmpresa;
    private array $dadoUsuario;
    private ?string $idUsuario = null;
    private ?int $statusUsuario = null;
    private ?string $hash = null;

    public function __construct(
        private ?string $usuario,
        private ?string $clube
    ) {
        if (!defined('TOKEN')) {
            mensagemStatus(401);
        } else if (empty($usuario)) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um usuário para continuar.');
        }

        parent::__construct();

        $this->idEmpresa = $clube == 'uber' ? 1982 : 223;
        $this->hash = uuid();

        $this->buscarLinkClube();
        $this->buscarUsuarioNaApiDigio();

        $this->verificarSeUsuarioJaExiste();
        if (!empty($this->idUsuario)) {
            $this->atualizarUsuarioJaExistente();
            return;
        }
        $this->salvarNovoUsuario();
    }

    private function buscarUsuarioNaApiDigio()
    {
        $Digio = new DigioHelper($this->usuario);
        $this->dadoUsuario = $Digio->usuario();
    }
}
