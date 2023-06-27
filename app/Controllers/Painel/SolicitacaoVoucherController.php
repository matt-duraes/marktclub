<?php

namespace App\Controllers\Painel;

use Http\Response;
use Helpers\ApiHelper;
use Controller\Controller;

final class SolicitacaoVoucherController extends Controller
{
    public function voucher(string $parceiro, string $usuario): Response
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->validar(mensagem: 'Erro ao gerar voucher', status: 500)
            ->body([
                'id' => $parceiro,
                'usuario' => $usuario,
                'tipo' => 'loja'
            ])
            ->post('/solicitacao-voucher')->object();

        return view(
            arquivo: 'solicitacao_voucher.voucher',
            var: [
                'dado' => $dado->dado
            ]
        );
    }
}
