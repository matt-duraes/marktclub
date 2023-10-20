<?php

namespace App\Models\Api\UsuarioCliente;

use App\Classes\Carteirinha\Status;
use App\Controllers\Api\Trait\ClienteTrait;
use Modules\DataHora;
use ORM\ORM;
use stdClass;

class PegarCarteirinhaModel extends ORM
{
    use ClienteTrait;

    protected string $ormTabela = TABELA_CARTEIRINHA;
    private ClienteEntity $usuario;
    private int $idEmpresa;

    public function __construct(
    ) {
        parent::__construct();
        $this->usuario = $this->pegarCliente(TOKEN['usuario']->uuid);
        $this->idEmpresa = TOKEN['empresa']->id;
    }

    public function gerarCarteirinha(): stdClass
    {
        $carteirinha = $this
            ->where($this->pegarWhere())
            ->campo(['bg_frente', 'bg_fundo'], 'carteirinha')
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod',
                'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_CONSTRUTOR_CLUBE)
            ->join('id_admin_empresa', 'id_admin_empresa')
            ->campo([
                'uuid',
                'logo_principal',
                'logo_secundaria'
            ], 'construtor_clube')
            ->primeiro();

        return $this->montarCarteirinha($carteirinha);
    }

    private function pegarWhere()
    {
        return [
            ['status', (new Status(Status::ATIVO))->numero()],
            ['id_admin_empresa', $this->idEmpresa]
        ];
    }

    private function montarCarteirinha(stdClass|array $carteirinha): stdClass
    {
        if (is_array($carteirinha)) {
            return object([]);
        }

        $link = LINK_ARQUIVO . '/construtor/';

        return object([
            'usuario' => $this->pegarUsuarioCriptografado(),
            'empresa' => [
                'id'   => $carteirinha->empresa_cod,
                'nome' => $carteirinha->empresa_nome_fantasia
            ],
            'imagem' => [
                'logo_principal'  => $link . $carteirinha->construtor_clube_logo_principal,
                'logo_secundaria' => $link . $carteirinha->construtor_clube_logo_secundaria,
                'bg_frente'       => $link . $carteirinha->carteirinha_bg_frente,
                'bg_fundo'        => $link . $carteirinha->carteirinha_bg_fundo
            ],
            'data_emissao' => (new DataHora(agora()))->date()
        ]);
    }

    private function pegarUsuarioCriptografado()
    {
        $usuario = $this->usuario;

        return criptografarDado(
            dado: [
                'nome'            => $usuario->nome,
                'cpf'             => $usuario->cpf,
                'matricula'       => $usuario->matricula,
                'data_nascimento' => $usuario->data_nascimento,
            ],
            criptografia: ['nome', 'cpf', 'matricula', 'data_nascimento', 'data_filiacao', 'estado']
        );
    }
}
