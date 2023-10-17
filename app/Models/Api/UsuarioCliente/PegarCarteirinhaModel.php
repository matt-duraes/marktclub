<?php

namespace App\Models\Api\UsuarioCliente;

use App\Classes\Carteirinha\Status;
use Erro\Excecao;
use Modules\DataHora;
use ORM\ORM;

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
     * @return array
     * @throws Excecao
     */
    public function gerarCarteirinha(): array
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
            ->read();

        return $this->montarCarteirinha($carteirinha);
    }

    /**
     * @param array $carteirinha
     *
     * @return array
     */
    private function montarCarteirinha(array $carteirinha): array
    {
        if (empty($carteirinha)) {
            return $carteirinha;
        }

        $dataEmissao = new DataHora(agora());
        $retorno = [];
        foreach ($carteirinha as $item) {
            $retorno[] = [
                'usuario'      => [
                    'nome'            => $item->nome,
                    'cpf'             => $item->cpf,
                    'matricula'       => $item->matricula,
                    'data_nascimento' => $item->data_nascimento,
                    'data_filiacao'   => $item->data_filiacao,
                    'estado'          => $item->endereco_estado
                ],
                'empresa'      => [
                    'id'   => $item->empresa_cod,
                    'nome' => $item->empresa_nome_fantasia
                ],
                'imagem'       => [
                    'logo_principal'  => LINK_ARQUIVO . '/construtor/' . $item->construtor_clube_logo_principal,
                    'logo_secundaria' => LINK_ARQUIVO . '/construtor/' . $item->construtor_clube_logo_secundaria,
                    'bg_frente'       => LINK_ARQUIVO . '/construtor/' . $item->carteirinha_bg_frente,
                    'bg_fundo'        => LINK_ARQUIVO . '/construtor/' . $item->carteirinha_bg_fundo
                ],
                'data_emissao' => $dataEmissao->date()
            ];
        }
        return $retorno;
    }
}
