<?php

namespace App\Models\Api\SolicitacaoVoucher;

use Modules\DataHora;
use App\Models\Api\GeralEntity;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;

final class VoucherEntity extends GeralEntity
{
    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;
    protected array $_buscar = [
        'id_usuario_cliente' => 'usuario',
        'tipo', 'data_criacao', 'data_atualizacao', 'data_validacao', 'status'
    ];

    protected DataHora $data_validacao;
    public Tipo $tipo;
    public Status $status;

    public function __construct()
    {
        parent::__construct();
        $this->_wherePadrao = ['empresa', $this->idEmpresa];
        $this->relacionarTabela(
            tabela: 'parceiro_novo',
            campoAtual: 'cod',
            campoOriginal: 'vinculo',
            campo: ['cod', 'titulo'],
            alias: 'parceiro'
        );
        $this->relacionarTabela(
            tabela: 'usuario_novo',
            campoAtual: 'id',
            campoOriginal: 'usuario',
            campo: [
                'cod', 'nome', 'documento', 'email_pessoal', 'email_trabalho',
                'telefone_fixo', 'telefone_celular', 'status'
            ],
            alias: 'usuario'
        );
    }

    public function retorno()
    {
        $telefone = !empty($this->usuario_telefone_fixo) ? $this->usuario_telefone_fixo : $this->usuario_telefone_celular;
        $email = !empty($this->usuario_email_pessoal) ? $this->usuario_email_pessoal : $this->usuario_email_trabalho;

        $usuario = [];
        if ($this->usuario_status != 4) {
            $usuario = [
                'id' => $this->usuario_cod,
                'nome' => $this->usuario_nome,
                'cpf' => strCpf($this->usuario_documento),
                'email' => strEmail($email),
                'telefone' => strTelefone($telefone),
            ];
        }

        return [
            'id' => $this->id,
            'parceiro' => [
                'id' => $this->parceiro_cod,
                'titulo' => $this->parceiro_titulo
            ],
            'usuario' => $usuario,
            'tipo' => $this->tipo->indice(),
            'data_criacao' => $this->data_criacao->data(),
            'data_atualizacao' => $this->data_atualizacao->data(),
            'data_validacao' => $this->data_validacao->data(),
            'status' => $this->status->indice()
        ];
    }
}
