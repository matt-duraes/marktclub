<?php

namespace App\Models\Api\SolicitacaoVoucher;

use ORM\Entity;
use Modules\DataHora;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;

final class VoucherEntity extends Entity
{
    use ValidarEmpresaTrait;

    protected string $_tabela = TABELA_SOLICITACAO_VOUCHER;
    protected array $_buscar = [
        'id_usuario_cliente' => 'usuario',
        'tipo', 'data_criacao', 'data_atualizacao', 'data_validacao', 'status'
    ];

    protected DataHora $data_validacao;
    public Tipo $tipo;
    public Status $status;

    protected string $parceiro_cod;
    protected string $parceiro_titulo;
    protected string $usuario_cod;
    protected string $usuario_nome;
    protected int $usuario_documento;
    protected int $usuario_telefone_fixo;
    protected int $usuario_telefone_celular;
    protected string $usuario_email_pessoal;
    protected string $usuario_email_trabalho;
    protected int $usuario_status;

    public function __construct()
    {
        parent::__construct();
        $this->validarEmpresa('empresa');

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
