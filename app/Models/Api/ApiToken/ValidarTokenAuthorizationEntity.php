<?php

namespace App\Models\Api\ApiToken;

use ORM\Entity;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class ValidarTokenAuthorizationEntity extends Entity
{
    use TokenTrait;

    protected string $ormTabela = TABELA_AUTH_TOKEN;
    protected array $ormBuscar = ['id_api_app', 'id_usuario', 'access_token', 'scope_permitido', 'grant_type', 'tipo'];
    protected Tipo $tipo;
    protected int $id_api_app;
    protected string $id_usuario;
    protected string $access_token;
    protected array $scope_permitido;
    protected string $grant_type;

    protected function regraPosBuscar()
    {
        $App = new AppEntity();
        $App->id($this->id_api_app);

        $tipo = $this->tipo->indice();
        if (!array_key_exists($tipo, (new Tipo())->select())) {
            mensagemStatus(404);
        }

        if ($tipo == Tipo::CLUBE) {
            $Usuario = new ClienteEntity(validarToken: false);
            $Usuario->uuid($this->id_usuario);
        } elseif ($tipo == Tipo::PAINEL) {
            $Usuario = new EquipeEntity(validarToken: false);
            $Usuario->uuid($this->id_usuario);
        }

        $Empresa = new EmpresaEntity();
        $Empresa->id($Usuario->id_admin_empresa);

        $this->criarDefinesDoToken(
            $this->access_token,
            $App,
            $Empresa,
            $Usuario,
            jsonDecode($this->scope_permitido, true),
            $this->grant_type
        );
    }
}
