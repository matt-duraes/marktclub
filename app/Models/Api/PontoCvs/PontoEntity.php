<?php

namespace App\Models\Api\PontoCvs;

use ORM\Entity;
use Modules\Email;
use Modules\DataHora;
use Helpers\EmailHelper;
use App\Helpers\PontoCvsHelper;
use App\Classes\PontoCvs\Helper;
use App\Classes\PontoCvs\Status;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Classes\UsuarioCliente\Helper as ClienteHelper;

final class PontoEntity extends Entity
{
    protected string $_tabela = TABELA_PONTO_CVS;

    protected array $_buscar = [
        'uuid', 'id_usuario_cliente', 'ponto_solicitado', 'voucher', 'status', 'data_atualizacao',
        'data_solicitacao', 'data_voucher', 'mensagem', 'pedido_codigo'
    ];

    protected array $_insert = ['uuid', 'id_usuario_cliente', 'ponto_solicitado', 'data_solicitacao', 'pedido_codigo'];
    protected array $_update = ['voucher', 'data_voucher', 'mensagem'];
    protected array $_salvar = ['status'];

    protected string $_validarSalvar = '
        status|Status|obrigatorio|vazio|valido
    ';

    public Status $status;
    public DataHora $data_solicitacao;
    public DataHora $data_voucher;
    public string $voucher;
    public int $ponto_solicitado;
    public string $mensagem;
    public string $pedido_codigo;

    public Email $email;

    public function __construct(
        public null|string $cpf = null
    ) {
        parent::__construct();

        $this->cpf = !is_null($cpf) ? soNumero($this->cpf) : null;
        $this->relacionarTabela(
            tabela: 'usuario_novo',
            campoAtual: 'id',
            campoOriginal: 'id_usuario_cliente',
            campo: [
                'cod', 'matricula', 'nome', 'documento', 'email_pessoal', 'email_trabalho',
                'telefone_fixo', 'telefone_celular', 'status'
            ],
            alias: 'usuario',
            where: [
                'OR',
                ['empresa', 198],
                [
                    ['empresa', 1],
                    ['tipo', 3]
                ]
            ]
        );
    }

    protected function regraPosBuscar()
    {
        $PontoCvsHelper = new PontoCvsHelper;
        $pontos = $PontoCvsHelper->buscarPontos($this->usuario_documento);

        $telefone = !empty($this->usuario_telefone_fixo) ? $this->usuario_telefone_fixo : $this->usuario_telefone_celular;

        if (in_array($this->usuario_status, ClienteHelper::STATUS_LIBERADO)) {
            $this->usuario = [
                'matricula' => $this->usuario_matricula,
                'id' => $this->usuario_cod,
                'nome' => $this->usuario_nome,
                'cpf' => strCpf($this->usuario_documento),
                'email' => strEmail($this->usuario_email_pessoal),
                'telefone' => strTelefone($telefone),
                'credito' => $pontos->credito,
                'debito' => $pontos->debito,
                'saldo' => $pontos->saldo
            ];
        }
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA INSERIR
    |--------------------------------------------------------------------------
    */
    protected function regraInsert()
    {
        $this->data_solicitacao = new DataHora(agora());
        $this->status = new Status('solicitado');
        $this->id_usuario_cliente = $this->pegarIdUsuario();

        $Cliente = new AtualizarUsuarioModel;
        $Cliente->atualizarUsuario([
            'nome' => $this->nome,
            'cpf' => $this->cpf,
            'email_pessoal' => $this->email->email(),
        ]);

        $this->verificarSeUsuarioConstaNaBase();
        $this->verificarSeFoiPedidoNumeroMinimoPonto();
        $this->validarSeUsuarioTemPontoSuficiente();
        $this->validarSeSolicitacaoFoiEfetuadaAPI();
    }

    private function pegarIdUsuario(): int
    {
        $Usuario = new ClienteEntity(validarToken: false);
        try {
            $Usuario->buscar([
                ['documento', $this->cpf],
                ['status', 'in', ClienteHelper::STATUS_LIBERADO],
                [
                    'OR',
                    ['empresa', 198],
                    [
                        ['empresa', 1],
                        ['tipo', 3]
                    ]
                ]
            ]);
        } catch (\Throwable) {
            mensagemErro('Erro!', 'Usuario não foi encontrado.', status: 404);
        }

        return $Usuario->get('id');
    }

    private function verificarSeUsuarioConstaNaBase()
    {
        $PontoCvsHelper = new PontoCvsHelper;
        if (!$PontoCvsHelper->validarUsuario($this->cpf)) {
            mensagemErro('Erro!', 'O Usuario indicado não pode realizar uma solicitação!');
        }
    }

    private function verificarSeFoiPedidoNumeroMinimoPonto()
    {
        $ponto = $this->ponto_solicitado;
        $pontoMinimo = Helper::PONTO_MINIMO;
        if (empty($ponto) || !is_int((int)$ponto) || $ponto < $pontoMinimo) {
            mensagemErro(
                'Pontos inválidos!',
                'Você deve enviar pelo menos ' . $pontoMinimo . ' para solicitar resgate.'
            );
        }
    }

    private function validarSeUsuarioTemPontoSuficiente()
    {
        $PontoCvsHelper = new PontoCvsHelper;
        if (!$PontoCvsHelper->validarQuantidadePonto($this->ponto_solicitado, $this->cpf)) {
            mensagemErro('Saldo Insuficiente!', 'Quantidade de pontos informada é maior que seu saldo atual.');
        }
    }

    private function validarSeSolicitacaoFoiEfetuadaAPI()
    {
        $PontoCvsHelper = new PontoCvsHelper;
        $this->pedido_codigo = $PontoCvsHelper->enviarSolicitacaoPonto($this->cpf, $this->ponto_solicitado);
    }

    protected function regraPosInsert()
    {
        $PontoCvsHelper = new PontoCvsHelper;
        $matricula = $PontoCvsHelper->buscarPontos($this->cpf)->matricula;

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['id', 165]);

        $titulo = $Construtor->titulo;

        $email = 'arrecadacao@spbancarios.com.br';
        $assunto = "Voucher Solicitado - Matrícula $matricula";

        if (eLocalhost()) {
            $email =  'ti@markt.club';
        } else if (eHomologacao()) {
            $assunto = "Mensagem de teste em Homologação: Ponto + Ação";
        }

        $EmailCvs = new EmailHelper;
        $EmailCvs->mensagem(
            titulo: 'Voucher Solicitado!',
            assunto: 'Um voucher foi solicitado',
            mensagem: "Olá <strong>Fabio Gomes</strong>, um novo voucher foi solicitado no painel!<br>
            O Usuário de Matrícula: $matricula, solicitou a quantia de $this->ponto_solicitado pontos.",
            posMensagem: 'Caso fique com alguma dúvida, por favor, entre em contato.',
            logo: $Construtor->logo,
            acao: 'Voucher',
            cor: $Construtor->cor
        );
        $EmailCvs->sendGrid($assunto, 'Fabio Gomes', $email, deNome: $titulo);

        $EmailUsuario = new EmailHelper;
        $EmailUsuario->mensagem(
            titulo: 'Voucher Solicitado!',
            assunto: 'Você Solicitou um novo Voucher',
            mensagem: "Olá <strong>$this->nome</strong>, recebemos sua solicitação de um novo voucher!
            Logo entraremos em contato com mais informações sobre a situação de seu pedido.",
            posMensagem: 'Caso fique com alguma dúvida, por favor, entre em contato.',
            logo: $Construtor->logo,
            acao: 'Voucher',
            cor: $Construtor->cor
        );
        $EmailUsuario->sendGrid("Voucher Solicitado", $this->nome, $this->email->email(), deNome: $titulo);
    }

    /*
    |--------------------------------------------------------------------------
    | REGRAS PARA ATUALIZAR
    |--------------------------------------------------------------------------
    */
    protected function regraUpdate()
    {
        if (empty($this->prop('voucher')) && !empty($this->voucher) && $this->status->indice() == 'aprovado') {
            $this->data_voucher = new DataHora(agora());
        }

        $this->validarSePodeMudarStatus();
        $this->validarSePodeMudarVoucher();
    }

    private function validarSePodeMudarVoucher()
    {
        $status = $this->status->numero();

        if (in_array($status, [1, 3]) && !empty($this->prop('voucher'))) {
            $this->voucher = '';
        } else if (in_array($status, [1, 3]) && !empty($this->voucher)) {
            mensagemErro('Erro!', 'Só é possível preencher o voucher caso o mesmo tenha sido "Aprovado".');
        }
    }

    private function validarSePodeMudarStatus()
    {
        if (!$this->status->valido()) {
            mensagemErro('Campo inválido!', 'O campo status não está no formato correto.');
        }

        $statusAtual = $this->prop('status');
        $statusNovo = $this->status->numero();

        if (in_array($statusAtual, [2, 3]) && $statusAtual != $statusNovo) {
            mensagemErro('Erro!', 'Você não pode mudar o status de uma solicitação que foi recusada ou aprovada.');
        }
    }
}
