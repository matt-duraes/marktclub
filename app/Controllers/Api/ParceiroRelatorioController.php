<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Dinheiro;
use Controller\Controller;
use App\Models\Api\AdminEmpresa\EmpresaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ConvenioParceiro\ParceiroEntity;
use App\Models\Api\ParceiroRelatorio\RelatorioModel;
use App\Models\Api\ParceiroRelatorio\RelatorioEntity;

final class ParceiroRelatorioController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request)
    {
        $Relatorio = new RelatorioModel($request);
        $dado = $Relatorio->listarDado();
        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id)
    {
        $Relatorio = new RelatorioEntity();
        $Relatorio->id($id);

        return mensagemSucesso($this->pegarDadoRetorno($Relatorio));
    }

    public function postSalvar(Request $request)
    {
        $Empresa = new EmpresaEntity();
        $Empresa->id($request->empresa, mensagem: 'Não foi encontrado uma empresa por esse código.');

        $Parceiro = new ParceiroEntity();
        $Parceiro->id($request->parceiro, mensagem: 'Não foi encontrado um parceiro por esse código.');

        $Relatorio = new RelatorioEntity(
            Empresa: $Empresa,
            Parceiro: $Parceiro,
            numero_transacao: $request->numero_transacao,
            valor_venda: new Dinheiro($request->valor_venda),
            data_relatorio: new Data($request->data_relatorio)
        );
        $Relatorio->salvar();

        return mensagemSucesso($this->pegarDadoRetorno($Relatorio), status: 201);
    }
    private function pegarDadoRetorno(RelatorioEntity $Relatorio)
    {
        return pegarPropriedadeDaEntity($Relatorio, lista: [
            'Empresa', 'Parceiro', 'numero_transacao', 'valor_venda', 'data_relatorio'
        ]);
    }

    public function putAtualizar(Request $request, string $id)
    {
        $Relatorio = new RelatorioEntity();
        $Relatorio->id($id);
        $Relatorio->set(lista: $request->dado());
        $Relatorio->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id)
    {
        $Relatorio = new RelatorioEntity();
        $Relatorio->id($id);
        $Relatorio->destruir();

        return new Response(status: 204);
    }
}
