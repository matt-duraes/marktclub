<?php

namespace App\Controllers\Api;

use ORM\Entity;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Controllers\Api\Trait\ClienteTrait;
use App\Controllers\Api\Trait\ParceiroTrait;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use App\Models\Api\SolicitacaoVoucher\BlueFitEntity;
use App\Models\Api\SolicitacaoVoucher\DownloadModel;
use App\Models\Api\SolicitacaoVoucher\VoucherEntity;

final class SolicitacaoVoucherController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface
{
    use ClienteTrait;
    use ParceiroTrait;

    public function postSalvar(Request $request): Response
    {
        $Voucher = $this->setarEntidadeDoVoucher($request->id, $request->usuario);
        $Voucher->salvar();

        return mensagemSucesso([]);
    }

    private function setarEntidadeDoVoucher(string $id, string $usuario): Entity
    {
        $Parceiro = $this->pegarParceiro(id: $id, obrigatorio: true);
        $Usuario = $this->pegarCliente(id: $usuario);

        if ($Parceiro->id == '') {
            return new BlueFitEntity(
                Parceiro: $Parceiro,
                Usuario: $Usuario
            );
        }
        return new VoucherEntity(
            Parceiro: $Parceiro,
            Usuario: $Usuario
        );
    }

    public function getListar(Request $request): Response
    {
        return mensagemSucesso([
            'lista' => [],
            'registro' => [
                'inicio' => 0,
                'final' => 0,
                'atual' => 0,
                'total' => 0
            ],
            'pagina' => [
                'total' => 0,
                'atual' => 1,
                'paginacao' => [1]
            ]
        ]);

        // $Voucher = new VoucherModel($request);
        // $dado = $Voucher->listarDados();

        // if (existeErro($dado, 'lista')) {
        //     mensagemErro(
        //         $dado->erro->titulo ?? 'Erro!',
        //         $dado->erro->mensagem ?? 'Ocorreu um erro ao listar os vouchers.',
        //     );
        // }

        // return mensagemSucesso($dado);
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
        validarUuid($id);

        $Voucher = new VoucherEntity;
        $Voucher->id($id);

        return mensagemSucesso($Voucher->retorno());
    }
}
