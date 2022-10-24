<?php

namespace App\Models\Api\LoginApi;

use ORM\ORM;
use App\Helpers\DigioHelper;
use App\Models\Api\LoginApi\Trait\LinkTrait;
use App\Models\Api\LoginApi\Trait\UsuarioTrait;
use App\Models\Api\LoginApi\Trait\ConstrutorTrait;

final class DigioModel extends ORM
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
        private ?string $id
    ) {
        if (empty($id)) {
            mensagemErro('Campo obrigatório!', 'Você deve passar um usuário para continuar.');
        }

        parent::__construct();

        $this->idEmpresa = 223;
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
        $Digio = new DigioHelper($this->id);
        $this->dadoUsuario = $Digio->usuario();
    }
}
