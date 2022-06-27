<?php

namespace App\Models\Api\ApiToken;

use ORM\Entity;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioEquipe\EquipeEntity;

final class ValidarTokenAuthorizationEntity extends Entity
{
    use TokenTrait;

    protected string $_tabela = TABELA_AUTH_TOKEN;
    protected array $_buscar = ['id_api_app', 'id_usuario', 'access_token', 'scope_permitido', 'grant_type'];

    protected function regraPosBuscar()
    {
        $App = new AppEntity();
        $App->_id($this->id_api_app);

        $Usuario = new EquipeEntity(validarToken: false);
        $Usuario->id($this->id_usuario);

        $Empresa = new EmpresaEntity();
        $Empresa->_id($Usuario->id_admin_empresa);

        $this->criarToken(
            $this->access_token,
            $App,
            $Empresa,
            $Usuario,
            jsonDecode($this->scope_permitido, true),
            $this->grant_type
        );
    }
}
