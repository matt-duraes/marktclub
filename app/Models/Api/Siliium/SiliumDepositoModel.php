<?php

namespace App\Models\Api\Siliium;

use App\Classes\Silium\TipoConta;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\ValidarHelper;
use Http\Request;
use Modules\Data;
use ORM\ORM;

class SiliumDepositoModel extends ORM
{
    use ValidarEmpresaTrait;

    private const SALDO_MINIMO = 10000;
    protected string $ormTabela = '';
    protected ?int $idEmpresa;
    protected ?int $idUsuario;

    public function __construct(
        protected readonly Request $request
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
                'uuid', 'banco', 'agencia', 'conta', 'tipo_conta', 'documento',
                'nome', 'valor', 'data_deposito', 'status', 'data_criacao'
            ])
            ->where([
                ['empresa', $this->idEmpresa],
                ['usuario', $this->idUsuario]
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
                    'documento_cpf' => $transacao->documento
                ],
                'data'   => [
                    'solicitado' => (new Data($transacao->data_criacao))->data(),
                    'deposito'   => empty($transacao->data_deposito) ?: (new Data($transacao->data_deposito))->data()
                ],
                'valor'  => number_format($transacao->valor, 2, ',', '.'),
                'status' => $transacao->status
            ];
        }

        return $retornoExtrato;
    }

    /**
     * @return bool|string
     * @throws Excecao
     */
    public function realizarSaque(): bool|string
    {
        $SiliumComissaoModel = new SiliumComissaoModel();
        $saldo = $SiliumComissaoModel->pegarSaldo();

        if ($saldo <= self::SALDO_MINIMO) {
            return false;
        }

        $this->validarRequest();

        $comissao = $SiliumComissaoModel->comissaoDisponivel();
        $valor = $SiliumComissaoModel->pegarSaldo($comissao);
        $transacao = [
            'uuid'       => uuid(),
            'empresa'    => $this->idEmpresa,
            'usuario'    => $this->idUsuario,
            'comissao'   => $comissao,
            'valor'      => $valor,
            'banco'      => $this->request->banco,
            'agencia'    => $this->request->agencia,
            'conta'      => $this->request->conta,
            'tipo_conta' => $this->request->tipo_conta,
            'documento'  => soNumero($this->request->documento_cpf),
            'nome'       => $this->request->titular,
            'status'     => 1
        ];

        $dados = $this
            ->dado($transacao)
            ->insert();

        $SiliumComissaoModel
            ->dado([
                'uuid'   => $dados['uuid'],
                'status' => 2
            ])
            ->update();

        return $dados['uuid'];
    }

    /**
     * @return void
     */
    private function validarRequest(): void
    {
        (new ValidarHelper())
            ->valor($this->request->titular, 'Nome do Titular')
            ->obrigatorio()
            ->vazio()
            ->valor($this->request->documento_cpf, 'CPF do Titular')
            ->obrigatorio()
            ->vazio()
            ->cpf()
            ->valor($this->request->banco, 'Nome/Número do banco')
            ->obrigatorio()
            ->vazio()
            ->valor($this->request->agencia, 'Número da agência')
            ->obrigatorio()
            ->vazio()
            ->valor($this->request->conta, 'Número da Conta')
            ->obrigatorio()
            ->vazio()
            ->valor($this->request->tipo_conta, 'Tipo de conta')
            ->obrigatorio()
            ->vazio()
            ->inArray((new TipoConta())->listarNumero());
    }
}
