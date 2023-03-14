<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\Voucher\ExisteModel;
use App\Classes\SolicitacaoVoucher\Tipo;
use App\Models\Api\Voucher\VoucherEntity;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\UsuarioCliente\ClienteEntity;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;

final class VoucherController extends Controller implements
    ControllerSalvarInterface
{
    public function postSalvar(Request $request): Response
    {
        $this->validarSalvar($request);

        $Usuario = $this->pegarUsuario($request->usuario);
        $Parceiro = $this->pegarParceiro($request->url);
        $Tipo = new Tipo($request->tipo);

        $Existe = new ExisteModel($Parceiro, $Usuario, $Tipo);
        if ($Existe->jaExiste()) {
            return $this->retornoSucesso($Existe->pegarVoucher());
        }

        $Voucher = new VoucherEntity(
            Parceiro: $Parceiro,
            Usuario: $Usuario,
            tipo: $Tipo
        );
        $Voucher->salvar();

        return $this->retornoSucesso($Voucher);
    }

    private function retornoSucesso(VoucherEntity $Voucher)
    {
        return mensagemSucesso(pegarPropriedadeDaEntity(
            $Voucher,
            lista: ['id']
        ));
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÕES
    |--------------------------------------------------------------------------
    */
    private function validarSalvar(Request $request)
    {
        $request
            ->vazio('url', mensagem: 'O campo URL é obrigatório.')
            ->vazio('tipo', 'O campo tipo é obrigatório.');

        if (!(new Tipo($request->tipo))->valido()) {
            mensagemErro('Campo inválido!', 'O campo Tipo deve ser um valor válido.');
        }
    }
    private function pegarParceiro(string $url): ParceiroEntity
    {
        $Parceiro = new ParceiroEntity();
        $Parceiro->buscar([
            ['status', 4],
            ['url', $url]
        ], mensagem: 'URL do parceiro não foi encontrado.');

        return $Parceiro;
    }
    private function pegarUsuario(string $id): ClienteEntity
    {
        $Usuario = new ClienteEntity(validarToken: false);
        $Usuario->buscar([
            ['status', 'in', Helper::STATUS_LIBERADO],
            ['cod', $id]
        ], mensagem: 'Não foi encontrado um usuário pelo id enviado.');

        return $Usuario;
    }
}
