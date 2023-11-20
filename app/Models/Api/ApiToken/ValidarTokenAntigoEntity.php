<?php

namespace App\Models\Api\ApiToken;

use ORM\Entity;
use App\Models\Api\ApiToken\Trait\TokenTrait;
use App\Models\Api\ApiToken\Trait\PegarAppTrait;
use App\Models\Api\ApiToken\Trait\PegarClienteTrait;
use App\Models\Api\ApiToken\Trait\PegarEmpresaTrait;

final class ValidarTokenAntigoEntity extends Entity
{
    use TokenTrait;
    use PegarAppTrait;
    use PegarEmpresaTrait;
    use PegarClienteTrait;

    protected string $ormTabela = TABELA_AUTH_TOKEN_ANTIGO;
    protected array $ormBuscar = ['token_acesso', 'id_usuario_cliente', 'id_admin_empresa'];
    protected int $id_usuario_cliente;
    protected int $id_admin_empresa;
    protected string $token_acesso;

    protected function regraPosBuscar()
    {
        $App = $this->pegarApp(['uuid', env('API_CLUBE_ID', '')]);
        $Usuario = $this->pegarCliente(['id', $this->id_usuario_cliente]);
        $Empresa = $this->pegarEmpresa(['id', $this->id_admin_empresa]);

        $this->criarDefinesDoToken(
            $this->token_acesso,
            $App,
            $Empresa,
            $Usuario,
            jsonDecode($App->scope_permitido, true),
            'implicit'
        );
    }
}
