<?php

namespace Tests\Api;

use stdClass;
use Tests\Tests;
use App\Classes\SolicitacaoVoucher\Status;

final class SolicitacaoVoucherTest extends Tests
{
    private ?stdClass $voucher;

    private string $usuario1 = '5595203c-f7b1-4211-9981-bf09eb236b35';
    private string $usuario2 = '87cd8f94-601e-4e8e-b800-7f42a75fc0e1';
    private string $usuario3 = 'c91d0f54-d166-456e-9f21-e072722faa34';
    private string $parceiroId = 'f10e05c0-5b02-4bff-8e22-719a8797f0d6';
    private string $parceiroUrl = 'parceiro-normal';
    private string $parceiroPrazo = 'adca39ea4a6d6bcc51eba8afcdb54eaa';
    private string $parceiroLimite = '4502e7e8-9359-470e-9588-0a1501449675';
    private string $parceiroPrazoFixo = 'f9cbb6ae-b847-43cf-b9b8-6f72b67789df';
    private string $parceiroBlueFit = 'ca0bde20602db3ec777acbbcfb5a4c61';

    public function __construct()
    {
        parent::__construct();

        $this->resetarTabela(TABELA_SOLICITACAO_VOUCHER);
        $this->resetarTabela(TABELA_SOLICITACAO_CODIGO);
        $this->resetarTabela(TABELA_PARCEIRO_LOJA);
        $this->resetarTabela(TABELA_USUARIO_CLIENTE);
    }

    public function salvarParceiroNormalPeloIdTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => $this->parceiroId,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher');

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function salvarParceiroNormalPelaUrlTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => $this->parceiroUrl,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher');

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function codigoParceiroNormalDeveSerDiferenteSempreTest()
    {
        $this->scopeSalvar();
        $codigo1 = $this
            ->Curl
            ->body([
                'id' => $this->parceiroUrl,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')
            ->object()->dado->codigo;

        $codigo2 = $this
            ->Curl
            ->body([
                'id' => $this->parceiroUrl,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')
            ->object()->dado->codigo;

        return $this
            ->checkNaoVazio($codigo1)
            ->checkNaoVazio($codigo2)
            ->checkDiferente($codigo1, $codigo2);
    }

    public function salvarVoucherComLimiteTest()
    {
        $this->scopeSalvar();
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->parceiroLimite,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher');

        $this->voucher = $dado->object()->dado ?? (object)[];

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function voucherComLimiteDeveRetornarMesmoVoucherTest()
    {
        $this->scopeSalvar();
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->parceiroLimite,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object()->dado ?? (object)[];

        return $this
            ->checkIgual($dado->id, $this->voucher->id)
            ->checkIgual($dado->codigo, $this->voucher->codigo)
            ->checkIgual($dado->data_criacao, $this->voucher->data_criacao)
            ->checkIgual($dado->data_vencimento, $this->voucher->data_vencimento)
            ->checkIgual($dado->status, $this->voucher->status);
    }
    public function deveGerarNovoVoucherComPrazoSeVoucherForCanceladoTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => $this->parceiroPrazo,
                'usuario' => $this->usuario3
            ])
            ->post('/solicitacao-voucher')->object();

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function vencimentoVoucherSalvoOntemNaoPodeMudarSeTiverPrazoTest()
    {
        $this->scopeSalvar();
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->parceiroPrazo,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object()->dado ?? (object)[];

        $this->voucher = $dado;

        return $this
            ->checkIgual($dado->codigo, '123123123')
            ->checkIgual(dataBanco($dado->data_criacao), dataRemover(hoje(), '1', 'dia'), mensagem: 'Data de criação')
            ->checkIgual($dado->data_vencimento, dataAdicionar(hoje(), '4', 'dia'), mensagem: 'Data vencimento');
    }

    public function temQueGerarUmNovoVoucherComPrazoParaOutroUsuarioTest()
    {
        $this->scopeSalvar();
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->parceiroPrazo,
                'usuario' => $this->usuario2
            ])
            ->post('/solicitacao-voucher')->object()->dado ?? (object)[];

        return $this
            ->checkStatus(201)
            ->checkDiferente($dado->codigo, $this->voucher->codigo);
    }

    public function naoPodeSalvarVoucherComLimiteAtingidoTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => $this->parceiroLimite,
                'usuario' => $this->usuario2
            ])
            ->post('/solicitacao-voucher')->object();
        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O saldo deste mês para esse parceiro expirou, abriremos um novo lote de vouchers no próximo mês.');
    }

    public function parceiroComPrazoFixoDeveUsarEleNoVencimentoTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => $this->parceiroPrazoFixo,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object();

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.data_vencimento', dataAdicionar(hoje(), 60, 'dias'));
    }
    public function naoPodeSalvarVoucherSemIdTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => '',
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object();

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo ID é obrigatório.');
    }
    public function naoPodeSalvarVoucherComIdInvalidoTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => uuid(),
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object();

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Não foi encontrado um parceiro pelo ID enviado.');
    }
    public function naoPodeSalvarVoucherComUrlInvalidaTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => 'url-nao-existe',
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object();

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Não foi encontrado um parceiro pelo ID enviado.');
    }
    public function naoPodeSalvarVoucherComUsuarioInvalidoTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => $this->parceiroId,
                'usuario' => uuid()
            ])
            ->post('/solicitacao-voucher')->object();

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Não foi encontrado nenhum usuário pelo código enviado.');
    }

    /*
    |--------------------------------------------------------------------------
    | BLUEFIT
    |--------------------------------------------------------------------------
    */
    public function salvarParceiroDaBlueFitTest()
    {
        $this->scopeSalvar();
        $this->voucher = $this
            ->Curl
            ->body([
                'id' => $this->parceiroBlueFit,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object()->dado ?? (object)[];

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function deveRetornarMesmoVoucherDaBluefitTest()
    {
        $this->scopeSalvar();
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->parceiroBlueFit,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher')->object()->dado ?? (object)[];

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIgual($this->voucher->codigo, $dado->codigo);
    }
    public function outroUsuarioDeveCriarVoucherNovoDaBlueFitTest()
    {
        $this->scopeSalvar();
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->parceiroBlueFit,
                'usuario' => $this->usuario2
            ])
            ->post('/solicitacao-voucher')->object()->dado ?? (object)[];

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkDiferente($this->voucher->codigo, $dado->codigo);
    }
    public function usuarioComVoucherVencidoDeveCriarNovoVoucherTest()
    {
        $this->scopeSalvar();
        $dado = $this
            ->Curl
            ->body([
                'id' => $this->parceiroBlueFit,
                'usuario' => $this->usuario3
            ])
            ->post('/solicitacao-voucher')->object()->dado ?? (object)[];

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('status')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkDiferente(123123, $dado->codigo);
    }

    public function blueFitTemPrazoFixoEDeveUsarEleNoVencimentoTest()
    {
        $this->scopeSalvar();
        $this
            ->Curl
            ->body([
                'id' => $this->parceiroBlueFit,
                'usuario' => $this->usuario1
            ])
            ->post('/solicitacao-voucher');

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.data_vencimento', dataAdicionar(hoje(), 60, 'dias'));
    }

    private function scopeSalvar()
    {
        $this->api('solicitacao_voucher:salvar');
    }
}
