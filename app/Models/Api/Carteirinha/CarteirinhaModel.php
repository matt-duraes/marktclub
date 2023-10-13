<?php

namespace App\Models\Api\Carteirinha;

use App\Classes\Carteirinha\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use Erro\Excecao;
use Modules\DataHora;
use ORM\ORM;

class CarteirinhaModel extends ORM
{
    protected string $ormTabela = TABELA_CARTEIRINHA;

    public function __construct(
        private readonly ClienteEntity $clienteEntity
    ) {
        parent::__construct();
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function pegarDados(): array
    {
        $dado = $this
            ->campo([
                'texto', 'texto_perdido', 'bg_frente', 'bg_fundo'
            ])
            ->where([
                ['id_admin_empresa', $this->clienteEntity->id_admin_empresa],
                ['status', (new Status(Status::ATIVO))->numero()]
            ])
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
            ->read();

        return $this->montarRetorno($dado);
    }

    /**
     * @param array $carteirinhas
     *
     * @return array
     */
    private function montarRetorno(array $carteirinhas): array
    {
        if (empty($carteirinhas)) {
            return $carteirinhas;
        }

        $dataEmissao = new DataHora(agora());
        $retorno = [];
        foreach ($carteirinhas as $carteirinha) {
            $retorno[] = [
                'usuario'      => [
                    'nome'            => $this->clienteEntity->nome->nome(),
                    'cpf'             => $this->clienteEntity->cpf->cpf(),
                    'matricula'       => $this->clienteEntity->matricula,
                    'data_nascimento' => $this->clienteEntity->data_nascimento->date(),
                    'data_filiacao'   => $this->clienteEntity->data_termo->date(),
                    'estado'          => $this->clienteEntity->endereco_estado,
                ],
                'empresa'      => [
                    'nome' => $carteirinha->empresa_nome_fantasia
                ],
                'imagem'       => [
                    'logo_principal'  => LINK_ARQUIVO . '/construtor/' . $carteirinha->construtor_clube_logo_principal,
                    'logo_secundaria' => LINK_ARQUIVO . '/construtor/' . $carteirinha->construtor_clube_logo_secundaria,
                    'bg_frente'       => LINK_ARQUIVO . '/construtor/' . $carteirinha->bg_frente,
                    'bg_fundo'        => LINK_ARQUIVO . '/construtor/' . $carteirinha->bg_fundo
                ],
                'data_emissao' => $dataEmissao->date()
            ];
        }
        return $retorno;
    }
}
