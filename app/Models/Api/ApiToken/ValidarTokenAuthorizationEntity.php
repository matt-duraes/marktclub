<?php

namespace App\Models\Api\ApiToken;

use ORM\Entity;
use Modules\DataHora;
use App\Classes\ApiToken\Tipo;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\ApiApp\Trait\AppParaTokenTrait;
use App\Models\Api\ApiToken\Trait\PegarEquipeTrait;
use App\Models\Api\ApiToken\Trait\PegarClienteTrait;
use App\Models\Api\ApiToken\Trait\PegarEmpresaTrait;

final class ValidarTokenAuthorizationEntity extends Entity
{
    use TokenTrait;
    use AppParaTokenTrait;
    use PegarEmpresaTrait;
    use PegarClienteTrait;
    use PegarEquipeTrait;

    protected string $ormTabela = TABELA_AUTH_TOKEN;
    protected array $ormBuscar = [
        'id_api_app', 'id_admin_empresa', 'id_usuario', 'access_token', 'scope_permitido',
        'data_vencimento', 'grant_type', 'tipo', 'status'
    ];
    protected Tipo $tipo;
    protected int $id_admin_empresa;
    protected int $id_api_app;
    protected string $id_usuario;
    protected string $access_token;
    protected array $scope_permitido;
    protected string $grant_type;
    protected DataHora $data_vencimento;
    protected int $status;

    protected function regraPosBuscar()
    {
        if ($this->data_vencimento->date() <= agora() || $this->status != 1) {
            $this->destruir();
            mensagemStatus(401);
        }
        $tipoUsuario = $this->tipo->indice();
        if (!array_key_exists($tipoUsuario, (new Tipo())->select())) {
            mensagemStatus(404);
        }

        $App = $this->pegarApp(['id', $this->id_api_app]);

        $whereUsuario = ['uuid', $this->id_usuario];
        if (preg_match('/^[0-9]{1,}$/', $this->id_usuario)) {
            $whereUsuario = ['id', $this->id_usuario];
        }

        if ($tipoUsuario == Tipo::CLUBE) {
            $Usuario = $this->pegarCliente($whereUsuario);
        } elseif ($tipoUsuario == Tipo::PAINEL) {
            $Usuario = $this->pegarEquipe($whereUsuario);
        }

        $idEmpresa = !empty($this->id_admin_empresa) ? $this->id_admin_empresa : $App->id_admin_empresa;
        $where = ['id', $idEmpresa];
        if (!vazio($Usuario) && empty($this->id_admin_empresa)) {
            $where = ['id', $Usuario->id_admin_empresa];
        }
        $Empresa = $this->pegarEmpresa($where);

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
