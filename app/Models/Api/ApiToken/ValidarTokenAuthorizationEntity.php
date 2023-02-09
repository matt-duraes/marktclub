<?php

namespace App\Models\Api\ApiToken;

use ORM\Entity;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiApp\AppEntity;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use App\Models\Api\UsuarioEquipe\EquipeEntity;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class ValidarTokenAuthorizationEntity extends Entity
{
    use TokenTrait;

    protected string $_tabela = TABELA_AUTH_TOKEN;
    protected array $_buscar = ['id_api_app', 'id_usuario', 'access_token', 'scope_permitido', 'grant_type', 'tipo'];

    protected Tipo $tipo;

    protected function regraPosBuscar()
    {
        $App = new AppEntity();
        $App->_id($this->id_api_app);

        $tipo = $this->tipo->indice();
        if (!array_key_exists($tipo, (new Tipo)->select())) {
            mensagemStatus(404);
        }

        if ($tipo == 'clube') {
            $Usuario = new ClienteEntity(validarToken: false);
            $Usuario->id($this->id_usuario);
        } else if ($tipo == 'painel') {
            $Usuario = new EquipeEntity(validarToken: false);
            $Usuario->id($this->id_usuario);
        }

        $Empresa = new EmpresaEntity();
        $Empresa->_id($Usuario->id_admin_empresa);

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
