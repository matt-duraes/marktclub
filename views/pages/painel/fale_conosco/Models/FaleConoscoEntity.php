<?php

namespace Painel\FaleConosco\Models;

use stdClass;
use App\Models\Painel\AppGeral\AppGeralEntity;

final class FaleConoscoEntity extends AppGeralEntity
{
    protected string $ormTabela = TABELA_FALE_CONOSCO;

    protected array $ormBuscar = [
        'nome', 'email', 'telefone', 'mensagem', 'ip', 'endereco_pais', 'endereco_estado', 'endereco_cidade',
        'sistema_operacional', 'browser', 'data_criacao', 'data_atualizacao', 'status'
    ];
    protected array $ormUpdate = ['status'];

    public function setarStatus(string|int $status): void
    {
        if (!in_array($status, FaleConoscoHelper::STATUS_INT_VALOR)) {
            mensagemErro('Erro!', 'O status enviado não é válido.');
        }
        $this->status = FaleConoscoHelper::STATUS_VALOR_INT[$status];
    }

    /*
    |--------------------------------------------------------------------------
    | DADOS PARA VISUALIZAR USUÁRIO
    |--------------------------------------------------------------------------
    */
    public function dadoVisualizar(): stdClass
    {

        return object([
            'id' => $this->id,
            'nome' => $this->nome,
            'email' => $this->email,
            'telefone' => $this->telefone,
            'mensagem' => $this->mensagem,
            'ip' => $this->ip,
            'endereco_pais' => $this->endereco_pais,
            'endereco_estado' => $this->endereco_estado,
            'endereco_cidade' => $this->endereco_cidade,
            'sistema_operacional' => $this->sistema_operacional,
            'browser' => $this->browser,
            'data_criacao' => $this->data_criacao->data(),
            'data_atualizacao' => $this->data_atualizacao->data(),
            'status' => FaleConoscoHelper::STATUS_INT_VALOR[$this->status] ?? ''
        ]);
    }
}
