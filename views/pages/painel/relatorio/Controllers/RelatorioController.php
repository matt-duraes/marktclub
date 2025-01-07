<?php

namespace Painel\Relatorio\Controllers;

use Erro\Excecao;
use Http\Request;
use Helpers\ApiHelper;
use Controller\Controller;
use Http\Response;
use Painel\Relatorio\Models\MontarRelatorioModel;

final class RelatorioController extends Controller
{
    /**
     * @throws Excecao
     */
    public function acesso(): Response
    {
        $empresas = [];
        $subempresas = [];
        if (painelPermissao('relatorio_acesso_empresa', false)) {
            $empresas = $this->pegarSelectEmpresa();
            $subempresas = $this->pegarSelectSubempresa();
        }
        return view('painel.relatorio.acesso', [
            'appTitulo'         => 'Relatório de acesso',
            'app'               => 'relatorio-acesso',
            'de'                => dataRemover(date('Y-m-d'), 8, 'dias', 'd/m/Y'),
            'ate'               => dataRemover(date('d/m/Y'), 1, 'dia', 'd/m/Y'),
            'empresa'           => $empresas,
            'subempresa'        => $subempresas,
            'parceiro'          => $this->pegarSelectParceiro(),
            'parceiroPermissao' => $this->pegarPermissaoParceiro('relatorio_acesso')
        ]);
    }

    /**
     * @throws Excecao
     */
    public function usuario(): Response
    {
        return view('painel.relatorio.usuario', [
            'appTitulo' => 'Relatório de usuário',
            'app'       => 'relatorio-usuario',
            'de'        => dataRemover(date('Y-m-d'), 7, 'dias', 'd/m/Y'),
            'ate'       => date('d/m/Y'),
            'empresa'   => $this->pegarSelectEmpresa()
        ]);
    }

    /**
     * @throws Excecao
     */
    public function lojaVenda(): Response
    {
        return view('painel.relatorio.venda', [
            'appTitulo'         => 'Relatório de venda',
            'app'               => 'relatorio-loja-venda',
            'de'                => '01/' . dataRemover(date('Y-m-') . '01', 6, 'meses', 'm/Y'),
            'ate'               => '01/' . date('m/Y'),
            'empresa'           => $this->pegarSelectEmpresa(),
            'parceiro'          => $this->pegarSelectParceiro(),
            'parceiroPermissao' => $this->pegarPermissaoParceiro('relatorio_loja_venda')
        ]);
    }

    /**
     * @throws Excecao
     */
    private function pegarSelectEmpresa()
    {
        return (new ApiHelper(token: true))
            ->get('/comercial-empresa/select')
            ->array()['dado'] ?? [];
    }

    /**
     * @throws Excecao
     */
    private function pegarSelectSubempresa()
    {
        return (new ApiHelper(token: true))
            ->get('/comercial-subempresa/select')
            ->array()['dado'] ?? [];
    }

    /**
     * @throws Excecao
     */
    private function pegarSelectParceiro()
    {
        return (new ApiHelper(token: true))
            ->get('/parceiro-loja/select')
            ->array()['dado'] ?? [];
    }

    /**
     * @throws Excecao
     */
    private function pegarPermissaoParceiro(string $app): bool
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        $isYouhuul = sessao('EMPRESA.id') == '14afa776394ada4be23be6acf7e3259e';
        return in_array($app . '_parceiro', $usuarioPermissao) && $isYouhuul;
    }

    /**
     * Busca o relatório de acesso diário
     *
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getAcessoDia(Request $request): Response
    {
        $this->validarData($request->de, $request->ate);

        $body = [
            'de'  => dataBanco($request->de),
            'ate' => dataBanco($request->ate),
        ];
        if (!empty($request->empresa)) {
            $body['empresa'] = explode(',', $request->empresa);
        }
        if (!empty($request->subempresa)) {
            $body['subempresa'] = explode(',', $request->subempresa);
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api
            ->json($body)->get('/relatorio/acesso-dia')
            ->object()->dado ?? [];

        $Montar = new MontarRelatorioModel();
        $relatorio = $Montar->montarLinha($dado, 'data', ['total' => 'Total', 'unico' => 'Unico']);
        $relatorio = $Montar->montarHeaderUsuarioAcesso($dado, $relatorio);

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
            'usuario' => 'usuario-mais-acesso',
            'pagina'  => 'pagina-mais-acessada',
            'loja'    => 'loja-mais-acessada'
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
        if ($local == 'loja' && !empty($request->parceiro)) {
            $body['parceiro'] = explode(',', $request->parceiro);
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
            ->object()->dado ?? [];

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($dado, $tipo);

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
            ->object()->dado ?? [];

        $Montar = new MontarRelatorioModel();
        return mensagemSucesso([
            'status'         => $Montar->montarRelatorioStatus($dado->status),
            'estado'         => $Montar->montarRelatorioEstado($dado->estado),
            'genero'         => $Montar->montarPizza($dado->genero->lista, 'genero'),
            'faixa_etaria'   => $Montar->montarPizza($dado->faixa_etaria->lista, 'faixa_etaria'),
            'atualizar_dado' => $Montar->montarPizza($dado->atualizar_dado->lista, 'tempo'),
            'estado_civil'   => $Montar->montarPizza($dado->estado_civil->lista, 'estado_civil'),
            'situacao'       => $Montar->montarPizza($dado->situacao->lista, 'situacao'),
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
