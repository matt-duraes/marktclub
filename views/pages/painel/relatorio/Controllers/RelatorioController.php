<?php

namespace Painel\Relatorio\Controllers;

use Http\Request;
use Helpers\ApiHelper;
use Controller\Controller;
use Painel\Relatorio\Models\MontarRelatorioModel;

final class RelatorioController extends Controller
{
    public function acesso()
    {
        return view(arquivo: 'painel.relatorio.acesso', var: [
            'appTitulo' => 'Relatório de acesso',
            'app'       => 'relatorio-acesso',
            'de'        => dataRemover(date('Y-m-d'), 8, 'dias', 'd/m/Y'),
            'ate'       => dataRemover(date('d/m/Y'), 1, 'dia', 'd/m/Y'),
            'empresa'   => $this->pegarSelectEmpresa()
        ]);
    }

    public function usuario()
    {
        return view(arquivo: 'painel.relatorio.usuario', var: [
            'appTitulo' => 'Relatório de usuário',
            'app'       => 'relatorio-usuario',
            'de'        => dataRemover(date('Y-m-d'), 7, 'dias', 'd/m/Y'),
            'ate'       => date('d/m/Y'),
            'empresa'   => $this->pegarSelectEmpresa()
        ]);
    }

    public function lojaVenda()
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        $parceiroPermissao = in_array('relatorio_loja_venda_parceiro', $usuarioPermissao) && sessao('EMPRESA.id') == '14afa776394ada4be23be6acf7e3259e';

        return view(arquivo: 'painel.relatorio.venda', var: [
            'appTitulo'         => 'Relatório de venda',
            'app'               => 'relatorio-loja-venda',
            'de'                => '01/' . dataRemover(date('Y-m-') . '01', 6, 'meses', 'm/Y'),
            'ate'               => '01/' . date('m/Y'),
            'empresa'           => $this->pegarSelectEmpresa(),
            'parceiro'          => $this->pegarSelectParceiro(),
            'parceiroPermissao' => $parceiroPermissao
        ]);
    }

    private function pegarSelectEmpresa()
    {
        return (new ApiHelper(token: true))
            ->get('/comercial-empresa/select')
            ->array()['dado'] ?? [];
    }

    private function pegarSelectParceiro()
    {
        return (new ApiHelper(token: true))
            ->get('/parceiro-loja/select')
            ->array()['dado'] ?? [];
    }

    /*
    |--------------------------------------------------------------------------
    | GRAFICO DE ACESSO
    |--------------------------------------------------------------------------
    */
    public function getAcessoDia(Request $request)
    {
        $de = $request->de;
        $ate = $request->ate;

        $this->validarData($de, $ate);

        $body = [
            'de'  => dataBanco($de),
            'ate' => dataBanco($ate),
        ];
        if ($request->empresa) {
            $body['empresa'] = explode(',', $request->empresa);
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->json($body)->get('/relatorio/acesso-dia')
            ->object();

        $Montar = new MontarRelatorioModel();
        $relatorio = $Montar->montarLinha($dado->dado, 'data', ['total' => 'Total', 'unico' => 'Unico']);
        $relatorio = $Montar->montarHeaderUsuarioAcesso($dado->dado, $relatorio);

        return mensagemSucesso($relatorio);
    }

    public function getMaisAcessado(Request $request)
    {
        $de = $request->de;
        $ate = $request->ate;
        $local = $request->local;

        $this->validarData($de, $ate);
        if (!in_array($local, ['usuario', 'pagina', 'loja'])) {
            $this->erroPadrao();
        }

        $uri = [
            'usuario'     => 'usuario-mais-acesso',
            'pagina'      => 'pagina-mais-acessada',
            'loja'        => 'loja-mais-acessada'
        ];

        $body = [
            'de'  => dataBanco($de),
            'ate' => dataBanco($ate),
        ];
        if ($request->empresa) {
            $body['empresa'] = explode(',', $request->empresa);
        }
        if ($local == 'loja' && !empty($request->estabelecimento)) {
            $body['estabelecimento'] = $request->estabelecimento;
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->json($body)
            ->get('/relatorio/' . $uri[$local])
            ->object()->dado ?? [];

        if ($local == 'usuario') {
            $Montar = new MontarRelatorioModel();
            $dado = $Montar->montarUsuarioComMaisAcesso($dado);
        }
        return mensagemSucesso($dado);
    }

    public function getDispositivo(Request $request)
    {
        $de = $request->de;
        $ate = $request->ate;
        $tipo = $request->tipo;

        $this->validarData($de, $ate);
        if (!in_array($tipo, ['dispositivo', 'navegador', 'os'])) {
            $this->erroPadrao();
        }

        $body = [
            'de'  => dataBanco($de),
            'ate' => dataBanco($ate),
        ];
        if ($request->empresa) {
            $body['empresa'] = explode(',', $request->empresa);
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->json($body)
            ->get('/relatorio/' . $tipo)
            ->object();

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($dado->dado, $tipo);

        return mensagemSucesso($dado);
    }

    private function erroPadrao(): void
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao buscar o relatório, por favor, tente novamente.');
    }

    private function validarData(string $de, string $ate): void
    {
        if (empty($de)) {
            mensagemErro('Data obrigatória', 'A data de início da busca é obrigatória.');
        } elseif (!validarData($de)) {
            mensagemErro('Data inválida', 'A data de início da busca não é uma data válida.');
        } elseif (empty($ate)) {
            mensagemErro('Data obrigatória', 'A data final da busca é obrigatória.');
        } elseif (!validarData($ate)) {
            mensagemErro('Data inválida', 'A data final da busca não é uma data válida.');
        }
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | GRÁFICO DE USUÁRIO
    |--------------------------------------------------------------------------
    */
    public function getDadoUsuario(Request $request)
    {
        $body = [];
        if ($request->empresa) {
            $body['empresa'] = explode(',', $request->empresa);
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->json($body)
            ->get('/relatorio/dado-usuario')
            ->object();

        $Montar = new MontarRelatorioModel();
        return mensagemSucesso([
            'status'         => $Montar->montarRelatorioStatus($dado->dado->status),
            'estado'         => $Montar->montarRelatorioEstado($dado->dado->estado),
            'genero'         => $Montar->montarPizza($dado->dado->genero->lista, 'genero'),
            'faixa_etaria'   => $Montar->montarPizza($dado->dado->faixa_etaria->lista, 'faixa_etaria'),
            'atualizar_dado' => $Montar->montarPizza($dado->dado->atualizar_dado->lista, 'tempo'),
            'estado_civil'   => $Montar->montarPizza($dado->dado->estado_civil->lista, 'estado_civil'),
            'situacao'       => $Montar->montarPizza($dado->dado->situacao->lista, 'situacao'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | VENDA LOJA
    |--------------------------------------------------------------------------
    */
    public function getLojaVendaBuscar(Request $request)
    {
        $de = $request->de;
        $ate = $request->ate;

        $this->validarData($de, $ate);
        $body = [
            'de'  => dataBanco($de),
            'ate' => dataBanco($ate),
        ];
        if ($request->empresa) {
            $body['empresa'] = explode(',', $request->empresa);
        }
        if ($request->parceiro) {
            $body['parceiro'] = explode(',', $request->parceiro);
        }

        $dado = (new ApiHelper(token: true))
            ->json($body)
            ->get('/relatorio/loja-venda')
            ->object();

        $Montar = new MontarRelatorioModel();
        $mes = $Montar->montarLinha(
            $dado->dado->venda_mes ?? [],
            'data',
            ['valor' => 'Valor total', 'ticket' => 'Ticket médio', 'venda' => 'Quantidade de vendas']
        );

        return mensagemSucesso([
            'mes'    => $mes,
            'venda'  => $dado->dado->venda_loja ?? [],
            'ticket' => $dado->dado->ticket_loja ?? [],
        ]);
    }
}
