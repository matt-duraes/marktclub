<?php

namespace App\Models\Api\UsuarioCliente;

use App\Classes\Carteirinha\Status;
use Erro\Excecao;
use Modules\DataHora;
use ORM\ORM;
use stdClass;

class PegarCarteirinhaModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    /**
     * @param string $idCliente
     */
    public function __construct(
        private readonly string $idCliente
    ) {
        parent::__construct();
    }

    /**
     * @return stdClass
     * @throws Excecao
     */
    public function gerarCarteirinha(): stdClass
    {
        $carteirinha = $this
            ->campo([
                'nome', 'cpf', 'matricula', 'data_nascimento',
                'data_filiacao', 'endereco_estado'
            ])
            ->where(['cod', $this->idCliente])
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo([
                'cod', 'nome_fantasia'
            ], 'empresa')
            ->tabela(TABELA_CONSTRUTOR_CLUBE)
            ->join('id_admin_empresa', 'id_admin_empresa')
            ->campo([
                'uuid', 'logo_principal', 'logo_secundaria'
            ], 'construtor_clube')
            ->tabela(TABELA_CARTEIRINHA)
            ->where(['status', (new Status(Status::ATIVO))->numero()])
            ->join('id_admin_empresa', 'id_admin_empresa')
            ->campo([
                'bg_frente', 'bg_fundo'
            ], 'carteirinha')
            ->primeiro();

        return $this->montarCarteirinha($carteirinha);
    }

    /**
     * @param stdClass|array $carteirinha
     *
     * @return stdClass
     */
    private function montarCarteirinha(stdClass|array $carteirinha): stdClass
    {
        if (is_array($carteirinha)) {
            return object([]);
        }

        return object([
            'usuario'      => [
                'nome'            => $carteirinha->nome,
                'cpf'             => $carteirinha->cpf,
                'matricula'       => $carteirinha->matricula,
                'data_nascimento' => $carteirinha->data_nascimento,
                'data_filiacao'   => $carteirinha->data_filiacao,
                'estado'          => $carteirinha->endereco_estado
            ],
            'empresa'      => [
                'id'   => $carteirinha->empresa_cod,
                'nome' => $carteirinha->empresa_nome_fantasia
            ],
            'imagem'       => [
                'logo_principal'  => LINK_ARQUIVO . '/construtor/' . $carteirinha->construtor_clube_logo_principal,
                'logo_secundaria' => LINK_ARQUIVO . '/construtor/' . $carteirinha->construtor_clube_logo_secundaria,
                'bg_frente'       => LINK_ARQUIVO . '/construtor/' . $carteirinha->carteirinha_bg_frente,
                'bg_fundo'        => LINK_ARQUIVO . '/construtor/' . $carteirinha->carteirinha_bg_fundo
            ],
            'data_emissao' => (new DataHora(agora()))->date()
        ]);
    }
}
