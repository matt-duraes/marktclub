<?php

namespace App\Models\Api\PontoCvs;

use ORM\Entity;
use Modules\DataHora;
use App\Helpers\PontoCvsHelper;
use App\Classes\PontoCvs\Helper;
use App\Classes\PontoCvs\Status;

final class PontoEntity extends Entity
{
    protected string $_tabela = TABELA_PONTO_CVS;

    protected array $_buscar = [
        'ponto_solicitado', 'voucher', 'status', 'data_solicitacao', 'data_voucher', 'mensagem'
    ];

    protected array $_insert = ['id_usuario_cliente', 'ponto_solicitado', 'data_solicitacao'];
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

    private int $idUsuario;
    private int $cpfUsuario;

    public function __construct()
    {
        parent::__construct();
        $this->idUsuario = TOKEN['usuario']->get('id');
        $this->cpfUsuario = TOKEN['usuario']->cpf->numero();
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
        $this->id_usuario_cliente = $this->idUsuario;

        // $this->verificarSeJaExisteUmaSolicitacao();
        $this->verificarSeFoiPedidoNumeroMinimoPonto();
        $this->validarSeUsuarioTemPontoSuficiente();
    }
    private function verificarSeJaExisteUmaSolicitacao()
    {
        $quantidade = $this->contar([
            ['id_usuario_cliente', $this->idUsuario],
            ['status', 'in', [1, 2]]
        ]);

        if ($quantidade > 0) {
            mensagemErro(
                'Erro!',
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
            mensagemErro('Erro!', 'Quantidade de pontos maior informado é maior que seu saldo atual.');
        }
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
    }
    private function validarSePodeMudarStatus()
    {
        $statusAtual = $this->prop('status');
        $statusNovo = $this->status->indice();

        if ($statusAtual == 1 && in_array($statusNovo, [1, 2])) {
            mensagemErro('Erro!', 'Só é possível mudar o status de "Solicitado" para "Em andamento".');
        } else if ($statusAtual == 2 && !in_array($statusNovo, [2, 3, 4])) {
            mensagemErro('Erro!', 'Só é possível mudar o status de "Em andamento" para "Recusado" ou "Aprovado".');
        } else if (in_array($statusAtual, [3, 4]) && $statusAtual != $statusNovo) {
            mensagemErro('Erro!', 'Você não pode mudar o status de uma solicitação que foi recusada ou aprovada.');
        }
    }
}
