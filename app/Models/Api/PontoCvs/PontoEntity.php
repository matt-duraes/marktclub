<?php

namespace App\Models\Api\PontoCvs;

use ORM\Entity;
use Modules\DataHora;
use App\Helpers\PontoCvsHelper;
use App\Classes\PontoCvs\Helper;
use App\Classes\PontoCvs\Status;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use Helpers\EmailHelper;

final class PontoEntity extends Entity
{
    protected string $_tabela = TABELA_PONTO_CVS;

    protected array $_buscar = [
        'uuid', 'id_usuario_cliente', 'ponto_solicitado', 'voucher', 'status', 'data_atualizacao', 
        'data_solicitacao', 'data_voucher', 'mensagem'
    ];

    protected array $_insert = ['uuid', 'id_usuario_cliente', 'ponto_solicitado', 'data_solicitacao'];
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
    public string $cpf;
    public string $mensagem;

    private string $cpfUsuario;

    public function __construct(string $cpf = '')
    {
        parent::__construct();

        $this->cpfUsuario = !empty($cpf) ? $cpf : TOKEN['usuario']->cpf->numero();
        $this->cpfUsuario = soNumero($this->cpfUsuario);

        $this->relacionarTabela(
            tabela: 'usuario_novo',
            campoAtual: 'documento',
            campoOriginal: 'id_usuario_cliente',
            campo: [
                'cod', 'matricula', 'nome', 'documento', 'email_pessoal', 'email_trabalho',
                'telefone_fixo', 'telefone_celular', 'status'
            ],
            alias: 'usuario'
        );
    }

    public function retorno()
    {
        $telefone = !empty($this->usuario_telefone_fixo) ? $this->usuario_telefone_fixo : $this->usuario_telefone_celular;
        $email = !empty($this->usuario_email_pessoal) ? $this->usuario_email_pessoal : $this->usuario_email_trabalho;

        $PontoCvsHelper = new PontoCvsHelper;
        $pontos = $PontoCvsHelper->buscarPontos($this->usuario_documento);

        $usuario = [];
        if ($this->usuario_status != 4) {
            $usuario = [
                'matricula' => $this->usuario_matricula,
                'id' => $this->usuario_cod,
                'nome' => $this->usuario_nome,
                'cpf' => strCpf($this->usuario_documento),
                'email' => strEmail($email),
                'telefone' => strTelefone($telefone),
                'credito' => $pontos->credito,
                'debito' => $pontos->debito,
                'saldo' => $pontos->saldo
            ];
        }

        return [
            'id' => $this->id,
            'usuario' => $usuario,
            'ponto' => strNull($this->ponto_solicitado),
            'voucher' => strNull($this->voucher),
            'mensagem' => strNull($this->mensagem),
            'data_solicitacao' => $this->data_solicitacao->data(),
            'data_voucher' => $this->data_voucher->data(),
            'data_atualizacao' => $this->data_atualizacao->data(),
            'status' => $this->status->indice()
        ];
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
        $this->id_usuario_cliente = $this->cpfUsuario;

        $this->verificarSeUsuarioConstaNaBase();
        $this->verificarSeFoiPedidoNumeroMinimoPonto();
        $this->validarSeUsuarioTemPontoSuficiente();
        $this->verificarSeJaExisteUmaSolicitacao();
        $this->validarSeSolicitacaoFoiEfetuadaAPI();
    }

    private function verificarSeUsuarioConstaNaBase()
    {
        $PontoCvsHelper = new PontoCvsHelper;
        if (!$PontoCvsHelper->validarUsuario($this->cpfUsuario)) {
            mensagemErro('Erro!', 'O Usuario indicado não pode realizar uma solicitação!');
        }
    }

    private function verificarSeJaExisteUmaSolicitacao()
    {
        $quantidade = $this->contar([
            ['id_usuario_cliente', $this->cpfUsuario],
            ['status', 'in', [1, 2]]
        ]);

        if ($quantidade > 0) {
            mensagemErro(
                'Por favor, aguarde!',
                'Você só pode fazer uma solicitação por vez, aguarde a finalização da solicitação em aberto.'
            );
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
        if (!$PontoCvsHelper->validarQuantidadePonto($this->ponto_solicitado, $this->cpfUsuario)) {
            mensagemErro('Saldo Insuficiente!', 'Quantidade de pontos informada é maior que seu saldo atual.');
        }
    }

    private function validarSeSolicitacaoFoiEfetuadaAPI()
    {
        $PontoCvsHelper = new PontoCvsHelper;
        $PontoCvsHelper->enviarSolicitacaoPonto($this->cpfUsuario, $this->ponto_solicitado);
    }

    protected function regraPosInsert()
    {
        $email = 'fabiogomes@spbancarios.com.br';
        if (eLocalhost() || eHomologacao() || SISTEMA == 'LOCALHOST') {
            $email =  'brian@marktclub.com.br';
        }

        $PontoCvsHelper = new PontoCvsHelper;
        $matricula = $PontoCvsHelper->buscarPontos($this->cpfUsuario)->matricula;

        $Construtor = new ConstrutorEntity();
        $Construtor->buscar(['id', 165]);

        $logo = $Construtor->logo;
        $titulo = $Construtor->titulo;

        $Email = new EmailHelper();
        $Email->mensagem(
            'Voucher Solicitado!',
            'Um voucher foi solicitado',
            'Olá <strong>Fabio Gomes</strong>, um novo voucher foi solicitado no painel! Para analisar sua situação, 
            clique no botão abaixo:',
            posMensagem: 'Caso fique com alguma dúvida, por favor, entre em contato.',
            botaoTexto: 'Verificar Voucher',
            botaoLink: LINK_PADRAO . '/painel/app/visualizar/ponto-cvs',
            logo: LINK_ARQUIVO . '/construtor/' . $logo,
            acao: 'Voucher',
            cor: $Construtor->cor
        );
        $Email->sendGrid("Voucher Solicitado - $matricula", 'Fabio Gomes', $email, deNome: $titulo);
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

        if (in_array($status, [1, 2, 4]) && !empty($this->prop('voucher'))) {
            $this->voucher = '';
        } else if (in_array($status, [1, 2, 4]) && !empty($this->voucher)) {
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

        if ($statusAtual == 1 && in_array($statusNovo, [3, 4])) {
            mensagemErro('Erro!', 'Só é possível mudar o status de "Solicitado" para "Em andamento".');
        } else if ($statusAtual == 2 && $statusNovo == 1) {
            mensagemErro('Erro!', 'Só é possível mudar o status de "Em andamento" para "Recusado" ou "Aprovado".');
        } else if (in_array($statusAtual, [3, 4]) && $statusAtual != $statusNovo) {
            mensagemErro('Erro!', 'Você não pode mudar o status de uma solicitação que foi recusada ou aprovada.');
        }
    }
}
