<?php

namespace App\Models\Api\Silium;

use App\Classes\Silium\TipoConta;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\ValidarHelper;
use Http\Request;
use Modules\Cpf;
use Modules\Data;
use Modules\DataHora;
use Modules\Dinheiro;
use Modules\Nome;
use ORM\ORM;
use Status\StatusInterface;

class SiliumDepositoModel extends ORM
{
    use ValidarEmpresaTrait;

    private const SALDO_MINIMO = 10000;

    protected string $ormTabela = TABELA_SILIUM_DEPOSITO;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;

    /**
     * @param Request|null $request
     *
     * @throws Excecao
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function gerarExtrato(): array
    {
        $extrato = $this
            ->campo([
                'uuid', 'banco', 'agencia', 'conta', 'tipo_conta', 'documento_cpf',
                'nome', 'valor', 'data_deposito', 'status', 'data_criacao'
            ])
            ->where([
                ['id_admin_empresa', $this->idEmpresa],
                ['id_usuario', $this->idUsuario]
            ])
            ->read();

        $retornoExtrato = [];
        foreach ($extrato as $transacao) {
            $retornoExtrato[] = [
                'uuid'   => $transacao->uuid,
                'conta'  => [
                    'banco'         => $transacao->banco,
                    'agencia'       => $transacao->agencia,
                    'conta'         => $transacao->conta,
                    'tipo_conta'    => (new TipoConta($transacao->tipo_conta))->indice(),
                    'titular'       => $transacao->nome,
                    'documento_cpf' => (new Cpf($transacao->documento_cpf))->cpf()
                ],
                'data'   => [
                    'solicitado' => (new Data($transacao->data_criacao))->data(),
                    'deposito'   => empty($transacao->data_deposito) ?: (new Data($transacao->data_deposito))->data()
                ],
                'valor'  => (new Dinheiro($transacao->valor))->dinheiro(),
                'status' => $transacao->status
            ];
        }

        return $retornoExtrato;
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function realizarSaque(): array
    {
        $this->validarRequest();

        $SiliumComissaoModel = new SiliumComissaoModel();
        $saldo = $SiliumComissaoModel->pegarSaldo();

        if ($saldo <= self::SALDO_MINIMO) {
            mensagemErro('Saldo insuficiente', 'Seu saldo está abaixo de ' . self::SALDO_MINIMO);
        }

        $comissao = $SiliumComissaoModel->comissaoDisponivel();
        $valor = $SiliumComissaoModel->pegarSaldo($comissao);
        $transacao = [
            'uuid'             => uuid(),
            'id_admin_empresa' => $this->idEmpresa,
            'id_usuario'       => $this->idUsuario,
            'nome'             => (new Nome($this->request->getPost('titular')))->valor(),
            'documento_cpf'    => (new Cpf($this->request->getPost('documento_cpf')))->valor(),
            'banco'            => $this->request->getPost('banco'),
            'agencia'          => $this->request->getPost('agencia'),
            'conta'            => $this->request->getPost('conta'),
            'tipo_conta'       => (new TipoConta($this->request->getPost('tipo_conta')))->numero(),
            'comissao'         => $comissao,
            'valor'            => (new Dinheiro($valor))->decimal(),
            'status'           => 1
        ];

        $dados = $this
            ->dado($transacao)
            ->insert();

        $this->atualizarStatus($dados['id'], 2);
        return $this->montarRetorno($dados);
    }

    private function validarRequest(): void
    {
        $tipoConta = new TipoConta($this->request->getPost('tipo_conta'));
        $nomeTitular = new Nome($this->request->getPost('titular'));
        $cpf = new Cpf($this->request->getPost('documento_cpf'));

        (new ValidarHelper())
            ->valor($nomeTitular, 'Nome do Titular')
            ->obrigatorio()
            ->vazio()
            ->valido()
            ->valor($cpf, 'CPF do Titular')
            ->obrigatorio()
            ->vazio()
            ->valido()
            ->valor($this->request->getPost('banco'), 'Nome/Número do banco')
            ->obrigatorio()
            ->vazio()
            ->valor($this->request->getPost('agencia'), 'Número da agência')
            ->obrigatorio()
            ->vazio()
            ->valor($this->request->getPost('conta'), 'Número da Conta')
            ->obrigatorio()
            ->vazio()
            ->valor($tipoConta, 'Tipo de conta')
            ->obrigatorio()
            ->vazio()
            ->valido();
    }

    /**
     * @param array|int|string           $id
     * @param StatusInterface|int|string $status
     *
     * @return array
     * @throws Excecao
     */
    public function atualizarStatus(array|int|string $id, StatusInterface|int|string $status): array
    {
        return $this
            ->dado([
                'status' => ($status instanceof StatusInterface) ? $status->numero() : $status
            ])
            ->where((is_array($id) && !empty($id)) ? ['id', 'in', $id] : ['id', $id])
            ->update();
    }

    /**
     * @param array $dados
     *
     * @return array
     */
    private function montarRetorno(array $dados): array
    {
        $dados['documento_cpf'] = (new Cpf($dados['documento_cpf']))->cpf();
        $dados['tipo_conta'] = (new TipoConta($dados['tipo_conta']))->indice();
        $dados['valor'] = (new Dinheiro($dados['valor']))->dinheiro();
        $dados['data_criacao'] = (new DataHora($dados['data_criacao']))->data();
        $dados['data_atualizacao'] = (new DataHora($dados['data_atualizacao']))->data();
        return $dados;
    }
}
