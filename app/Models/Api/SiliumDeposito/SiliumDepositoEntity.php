<?php

namespace App\Models\Api\SiliumDeposito;

use ORM\Entity;
use Modules\Cpf;
use Erro\Excecao;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Dinheiro;
use Helpers\OrmHelper;
use Helpers\EmailHelper;
use SendGrid\Mail\TypeException;
use App\Classes\SiliumDeposito\Status;
use App\Classes\SiliumDeposito\TipoConta;
use App\Classes\SiliumDeposito\TipoResgate;
use App\Classes\SiliumDeposito\TipoOperacao;
use App\Models\Api\SiliumSaldo\SiliumSaldoEntity;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;

class SiliumDepositoEntity extends Entity
{
    public string|array $usuario;
    public string $saque;
    public Nome $nome_titular;
    public Cpf $documento_cpf;
    public Email $email;
    public TipoConta $tipo_conta;
    public string $banco;
    public string $agencia;
    public string $conta;
    public int $pontuacao;
    public Dinheiro $valor;
    public Data $data_deposito;
    public string $documento_anexo;
    public TipoOperacao $tipo_operacao;
    public TipoResgate $tipo_resgate;
    public Status $status;
    protected string $ormTabela = TABELA_SILIUM_DEPOSITO;
    protected array $ormBuscar = [
        'id_usuario_cliente', 'nome_titular', 'documento_cpf',
        'email', 'tipo_conta', 'banco', 'agencia', 'conta', 'pontuacao', 'valor',
        'data_deposito', 'documento_anexo', 'status', 'tipo_operacao',
        'tipo_resgate', 'data_criacao', 'data_atualizacao'
    ];
    protected array $ormInsert = [
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormSalvar = [
        'nome_titular', 'documento_cpf', 'email', 'tipo_conta', 'banco',
        'agencia', 'conta', 'pontuacao', 'valor', 'data_deposito',
        'documento_anexo', 'status', 'tipo_operacao', 'tipo_resgate'
    ];
    protected int $id_usuario_cliente;
    protected int $idUsuario;
    private array $configs = [];

    public function __construct()
    {
        $this->pegarConfiguracoes();
        parent::__construct();
    }

    private function pegarConfiguracoes(): void
    {
        $OrmHelper = new OrmHelper(TABELA_SILIUM_CONFIG);
        $configs = $OrmHelper->pegarUltimoRegistro(
            ['id_admin_empresa', TOKEN['empresa']->id],
            ['desconto', 'pontuacao_minima_resgate'],
            'object'
        );

        if (empty($configs->desconto)) {
            $configs = $OrmHelper->pegarUltimoRegistro(
                ['id_admin_empresa', 1],
                ['desconto', 'pontuacao_minima_resgate'],
                'object'
            );
        }
        $this->configs = [
            'desconto'         => (new Botao($configs->desconto))->valor(),
            'pontuacao_minima' => jsonDecode($configs->pontuacao_minima_resgate, true, true)
        ];
    }

    protected function regraPosBuscar(): void
    {
        $this->pegarUsuario();
        if ($this->tipo_operacao->indice() === TipoOperacao::DEPOSITO) {
            $this->pegarComprovante();
        }
    }

    /**
     */
    private function pegarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['id', $this->id_usuario_cliente],
            ['uuid', 'nome'],
            'object'
        );

        if (empty($usuario->uuid)) {
            $this->usuario = [
                'id'   => '',
                'nome' => 'Não foi encontrado'
            ];
            return;
        }
        $this->usuario = [
            'id'   => $usuario->uuid,
            'nome' => $usuario->nome
        ];
    }

    private function pegarComprovante(): void
    {
        $this->documento_anexo = arquivoPrivado($this->documento_anexo);
    }

    /**
     * @throws Excecao
     */
    protected function regraInsert(): void
    {
        if (empty($this->tipo_operacao) && !$this->tipo_operacao->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo de Operação informado não é válido.');
        }
        if ($this->tipo_operacao->indice() === TipoOperacao::SAQUE) {
            $this->status = new Status(Status::AGUARDANDO);
            $this->validarRequestSaque();
            $this->setarUsuario();
            $this->verificarSolicitacaoPendente();
            $this->validarResgate();
            $this->validarSaldoSuficiente();
        } elseif ($this->tipo_operacao->indice() === TipoOperacao::DEPOSITO) {
            $this->validarRequestDeposito();
            $this->pegarSolicitacao();
        }
    }

    /**
     * @throws Excecao
     */
    private function validarRequestSaque(): void
    {
        if (!empty($this->usuario) && !validarUuid($this->usuario)) {
            mensagemErro('Campo inválido!', 'Você deve informar o usuário.');
        }
        if (!$this->nome_titular->vazio() && !$this->nome_titular->valido()) {
            mensagemErro('Campo inválido!', 'O Nome informado não é válido.');
        }
        if (!$this->documento_cpf->vazio() && !$this->documento_cpf->valido()) {
            mensagemErro('Campo inválido!', 'A CPF informado não é válido.');
        }
        if (!$this->email->vazio() && !$this->email->valido()) {
            mensagemErro('Campo inválido!', 'O E-mail informado não é válido.');
        }
        if (!$this->tipo_conta->vazio() && !$this->tipo_conta->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo de Conta informado não é válido.');
        }
        if (!empty($this->pontuacao) && !filter_var($this->pontuacao, FILTER_VALIDATE_INT)) {
            mensagemErro('Campo inválido!', 'A Pontuação não é válida.');
        }
        if (!$this->tipo_resgate->vazio() && !$this->tipo_resgate->valido()) {
            mensagemErro('Campo inválido!', 'O Tipo de Resgate informado não é válido.');
        }
    }

    /**
     * @throws Excecao
     */
    private function setarUsuario(): void
    {
        $OrmHelper = new OrmHelper(TABELA_USUARIO_CLIENTE);
        $id = $OrmHelper->pegarIdPeloUuid($this->usuario);

        if (empty($id)) {
            mensagemErro(
                'Campo obrigatório!!!',
                'Não foi possível achar um usuário.'
            );
        }
        $this->idUsuario = $id;
    }

    /**
     * @throws Excecao
     */
    private function verificarSolicitacaoPendente(): void
    {
        $OrmHelper = new OrmHelper($this->ormTabela);
        $saque = $OrmHelper->pegarUltimoRegistro(
            ['id_usuario_cliente', $this->idUsuario],
            ['uuid', 'status'],
            'object'
        );
        if (!empty($saque->uuid) && (new Status($saque->status))->indice() === Status::AGUARDANDO) {
            mensagemErro(
                'Resgate não autorizado!!!',
                'Você já possui uma solicitação de saque pendente.'
            );
        }
    }

    /**
     * @throws Excecao
     */
    private function validarResgate(): void
    {
        if ($this->tipo_resgate->indice() === TipoResgate::DINHEIRO) {
            $ponto = $this->configs['pontuacao_minima'][TipoResgate::DINHEIRO];
            if ($this->pontuacao < $ponto) {
                mensagemErro(
                    'Resgate não autorizado!!!',
                    'Solicitações de resgate devem ser acima de ' . $ponto
                );
            }
        } elseif ($this->tipo_resgate->indice() === TipoResgate::MENSALIDADE) {
            $ponto = $this->configs['pontuacao_minima'][TipoResgate::MENSALIDADE];
            if ($this->pontuacao < $ponto) {
                mensagemErro(
                    'Resgate não autorizado!!!',
                    'Solicitações de desconto devem ser acima de ' . $ponto
                );
            }
        }
    }

    /**
     * @throws Excecao
     */
    private function validarSaldoSuficiente(): void
    {
        if (empty($this->idUsuario)) {
            mensagemErro(
                'Falha na identificação!!!',
                'Houve uma falha e não foi possível identificar o usuário.'
            );
        }

        $OrmHelper = new OrmHelper(TABELA_SILIUM_SALDO);
        $usuario = $OrmHelper->pegarUltimoRegistro(
            ['id_usuario_cliente', $this->idUsuario],
            ['saldo_silium'],
            'object'
        );
        if (empty($usuario) || ($usuario->saldo_silium < $this->pontuacao)) {
            mensagemErro(
                'Resgate não autorizado!!!',
                'Sua pontuação é insuficiente para o resgate.'
            );
        }
    }

    /**
     * @throws Excecao
     */
    private function validarRequestDeposito(): void
    {
        if (!empty($this->saque) && !validarUuid($this->saque)) {
            mensagemErro('Campo inválido!', 'A Identificação do Saque não é válido.');
        }
        if (!empty($this->valor) && !$this->valor->vazio() && !$this->valor->valido()) {
            mensagemErro('Campo inválido!', 'O Valor informado não é válido.');
        }
        if (!empty($this->data_deposito) && !$this->data_deposito->vazio() && !$this->data_deposito->valido()) {
            mensagemErro('Campo inválido!', 'A Data de Depósito informada não é válida.');
        }
    }

    /**
     * @throws Excecao
     */
    private function pegarSolicitacao(): void
    {
        $OrmHelper = new OrmHelper($this->ormTabela);
        $solicitacao = $OrmHelper->pegarUltimoRegistro(
            ['uuid', $this->saque],
            [
                'id_usuario_cliente', 'nome_titular', 'documento_cpf', 'email',
                'tipo_conta', 'banco', 'agencia', 'conta', 'pontuacao',
                'tipo_resgate'
            ],
            'object'
        );
        if (empty($solicitacao->id_usuario_cliente)) {
            mensagemErro(
                'Falha na identificação!!!',
                'Houve uma falha e não foi possível identificar a solicitação.'
            );
        }
        $this->set(lista: [
            'idUsuario'     => $solicitacao->id_usuario_cliente,
            'nome_titular'  => $solicitacao->nome_titular,
            'documento_cpf' => $solicitacao->documento_cpf,
            'email'         => $solicitacao->email,
            'tipo_conta'    => $solicitacao->tipo_conta,
            'banco'         => $solicitacao->banco,
            'agencia'       => $solicitacao->agencia,
            'conta'         => $solicitacao->conta,
            'pontuacao'     => $solicitacao->pontuacao,
            'tipo_resgate'  => $solicitacao->tipo_resgate
        ]);
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    protected function regraPosInsert(): void
    {
        $operacao = $this->tipo_operacao->indice() === TipoOperacao::DEPOSITO;
        $status = $this->status->indice() === Status::DEPOSITADO;
        if ($operacao) {
            $this->atualizarStatusSolicitacao($this->status->indice());
            if ($status) {
                $this->debitarSaldo();
                $this->enviarEmail();
            }
        }
    }

    /**
     * @param string $status
     *
     * @throws Excecao
     */
    private function atualizarStatusSolicitacao(string $status): void
    {
        $SiliumDepositoEntity = new SiliumDepositoEntity();
        $SiliumDepositoEntity->buscar([
            'uuid', $this->saque
        ], false);
        $SiliumDepositoEntity->status = new Status($status);
        $SiliumDepositoEntity->salvar();
    }

    /**
     * @throws Excecao
     */
    private function debitarSaldo(): void
    {
        $SiliumSaldoEntity = new SiliumSaldoEntity();
        $SiliumSaldoEntity->buscar([
            'id_usuario_cliente', $this->id_usuario_cliente
        ], false);

        $pontos = $SiliumSaldoEntity->saldo_silium - $this->pontuacao;
        $SiliumSaldoEntity->saldo_silium = $pontos;
        $SiliumSaldoEntity->salvar();
    }

    /**
     * @throws Excecao
     * @throws TypeException
     */
    private function enviarEmail(): void
    {
        if (eLocalhost()) {
            return;
        }

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['id_admin_empresa', TOKEN['empresa']->id]);

        $titulo = '';
        $assunto = '';
        $acao = '';
        $mensagem = '';

        if ($this->tipo_resgate->indice() === TipoResgate::DINHEIRO) {
            $titulo = 'Saque de Cashback';
            $assunto = 'Saque de Cashback';
            $acao = 'Silium Cashback';
            $mensagem = 'Caro(a) <strong>' . $this->nome_titular->nome() . '</strong>, Confirmamos o recebimento do seu pedido de saque de cashback
            no valor de R$ ' . $this->valor->dinheiro() . ' (' . $this->pontuacao . ' Pontos), registrado em ' . $this->data_deposito->data() . '.';
        } elseif ($this->tipo_resgate->indice() === TipoResgate::MENSALIDADE) {
            $titulo = 'Desconto de Mensalidade';
            $assunto = 'Desconto de Mensalidade via Silium';
            $acao = 'Silium Cashback';
            $mensagem = 'Caro(a) <strong>' . $this->nome_titular->nome() . '</strong>, Confirmamos o recebimento do seu pedido de desconto na mensalidade
            no valor de R$ ' . $this->valor->dinheiro() . ' (' . $this->pontuacao . ' Pontos), registrado em ' . $this->data_deposito->data() . '.';
        }

        $Email = new EmailHelper();
        $Email->mensagem(
            titulo: $titulo,
            mensagem: $mensagem,
            assunto: $assunto,
            posMensagem: 'Caso fique com alguma dúvida, por favor, entre em contato.',
            acao: $acao,
            logo: $Construtor->logo_principal,
            cor: $Construtor->cor_principal
        );
        $Email->sendGrid($titulo, $this->nome_titular->nome(), $this->email->email(), deNome: 'Cashback Silium');
    }
}
