<?php

namespace App\Controllers\Api;

use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Dinheiro;
use Controller\Controller;
use App\Models\Api\ParceiroLoja\LojaEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ParceiroRelatorio\RelatorioModel;
use App\Models\Api\ParceiroRelatorio\RelatorioEntity;

final class ParceiroRelatorioController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Relatorio = new RelatorioModel($request);
        $dado = $Relatorio->listarDado();
        return mensagemSucesso($dado);
    }

    public function getBuscar(string $id): Response
    {
        $Relatorio = new RelatorioEntity();
        $Relatorio->uuid($id);

        return mensagemSucesso($this->pegarDadoRetorno($Relatorio));
    }

    public function postSalvar(Request $request): Response
    {
        $request
            ->vazio('empresa', mensagem: 'O campo empresa é obrigatório.')
            ->vazio('parceiro', mensagem: 'O campo parceiro é obrigatório.')
            ->vazio('data_relatorio', mensagem: 'O campo data do relatório é obrigatório.')
            ->validarDate('data_relatorio', mensagem: 'O campo data do relatório é inválida.');

        $Empresa = $this->pegarEmpresa($request->empresa);
        $Parceiro = $this->pegarParceiro($request->parceiro);

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

    public function putAtualizar(Request $request, string $id): Response
    {
        $Relatorio = new RelatorioEntity();
        $Relatorio->uuid($id);

        $dado = $request->dado();
        if ($request->existe('empresa')) {
            $Relatorio->Empresa = $this->pegarEmpresa($request->empresa);
            unset($dado['empresa']);
        }
        if ($request->existe('parceiro')) {
            $Relatorio->Parceiro = $this->pegarParceiro($request->parceiro);
            unset($dado['parceiro']);
        }

        $Relatorio->set(lista: $dado);
        $Relatorio->salvar();

        return new Response(status: 204);
    }

    private function pegarEmpresa(?string $empresa)
    {
        $Empresa = new EmpresaEntity();
        $Empresa->uuid($empresa, mensagem: 'Não foi encontrado uma empresa por esse código.');
        return $Empresa;
    }
    private function pegarParceiro(?string $parceiro)
    {
        $Parceiro = new LojaEntity();
        $Parceiro->uuid($parceiro, mensagem: 'Não foi encontrado um parceiro por esse código.');
        return $Parceiro;
    }

    public function deleteDeletar(string $id): Response
    {
        $Relatorio = new RelatorioEntity();
        $Relatorio->uuid($id);
        $Relatorio->destruir();

        return new Response(status: 204);
    }
}
