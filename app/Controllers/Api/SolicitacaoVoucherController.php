<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Classes\SolicitacaoVoucher\Helper;
use App\Controllers\Api\Trait\ClienteTrait;
use App\Controllers\Api\Trait\ParceiroTrait;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\SolicitacaoVoucher\CodigoEntity;
use App\Models\Api\SolicitacaoVoucher\VoucherModel;
use App\Models\Api\SolicitacaoVoucher\DownloadModel;
use App\Models\Api\SolicitacaoVoucher\VoucherEntity;
use App\Models\Api\SolicitacaoVoucher\Interface\VoucherInterface;

final class SolicitacaoVoucherController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface
{
    use ClienteTrait;
    use ParceiroTrait;

    public function postSalvar(Request $request): Response
    {
        $Voucher = $this->pegarEntidadeDoVoucher(
            id: $request->id,
            usuario: $request->usuario,
            tipo: $request->tipo
        );
        $Voucher->salvar();

        return $this->retornoSucesso($Voucher, 201);
    }

    private function retornoSucesso(VoucherInterface $Voucher, int $status = 200)
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Voucher,
                lista: [
                    'id',
                    'Usuario'    => ['id', 'nome', 'cpf'],
                    'Parceiro'   => ['id', 'titulo', 'link_logo'],
                    'Construtor' => ['id', 'link_logo', 'link_logo_marktclub'],
                    'codigo', 'data_criacao', 'data_vencimento', 'data_validacao', 'qr_code', 'texto_desconto',
                    'texto_voucher', 'texto_juridico', 'texto_validar', 'status'
                ],
            ),
            // criptografar: Helper::CRIPTOGRAFAR,
            status: $status
        );
    }

    private function pegarEntidadeDoVoucher(string $id, string $usuario, string $tipo): VoucherInterface
    {
        $Parceiro = $this->pegarParceiro(
            id: $id,
            obrigatorio: true,
            tituloVazio: 'Campo obrigatório!',
            mensagemVazio: 'O campo ID é obrigatório.',
            mensagemErro: 'Não foi encontrado um parceiro pelo ID enviado.'
        );
        $Usuario = $this->pegarCliente(
            id: $usuario,
            obrigatorio: true,
            tituloVazio: 'Campo obrigatório!',
            mensagemVazio: 'O campo usuário é obrigatório.',
            mensagemErro: 'Não foi encontrado o usuário pelo código enviado.'
        );
        if (in_array($Parceiro->get('id'), ['4207', '15612'])) {
            return new CodigoEntity(
                Parceiro: $Parceiro,
                Usuario: $Usuario
            );
        }
        return new VoucherEntity(
            Parceiro: $Parceiro,
            Usuario: $Usuario,
            tipo: new Tipo($tipo)
        );
    }

    public function getListar(Request $request): Response
    {
        $Voucher = new VoucherModel($request);
        $dado = $Voucher->listarDados();

        if (existeErro($dado, 'lista')) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro ao listar os vouchers.',
            );
        }

        return mensagemSucesso($dado);
    }

    public function postDownload(Request $request)
    {
        $Voucher = new DownloadModel($request);
        $dado = $Voucher->download();

        $Download = new ArquivoEntity(
            $dado,
            $request->usuario
        );
        $Download->salvar();

        return mensagemSucesso([
            'id' => $Download->id
        ], status: 201);
    }

    public function getBuscar(string $id): Response
    {
        $Voucher = new VoucherEntity();
        $Voucher->uuid($id);

        return $this->retornoSucesso($Voucher);
    }
}
