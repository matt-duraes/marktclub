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
use App\Models\Api\SolicitacaoVoucher\CodigoEntity;
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
            usuario: $request->usuario
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
                    'id', 'codigo', 'data_criacao', 'data_vencimento', 'status',
                    'Usuario' => ['id', 'nome'],
                    'Parceiro' => ['id', 'titulo'],
                    'Empresa' => ['id', 'nome_fantasia']
                ],
            ),
            status: $status
        );
    }

    private function pegarEntidadeDoVoucher(string $id, string $usuario): VoucherInterface
    {
        $Parceiro = $this->pegarParceiro(
            id: $id,
            obrigatorio: true,
            tituloVazio: 'Campo obrigatório!',
            mensagemVazio: 'O campo ID é obrigatório.',
            mensagemErro: 'Não foi encontrado um parceiro pelo ID enviado.'
        );
        $Usuario = $this->pegarCliente(id: $usuario);

        if (in_array($Parceiro->get('id'), ['4207'])) {
            return new CodigoEntity(
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
