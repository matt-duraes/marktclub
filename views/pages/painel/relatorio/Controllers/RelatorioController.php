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
            'app' => 'relatorio-acesso',
            'de' => dataRemover(date('Y-m-d'), 7, 'dias', 'd/m/Y'),
            'ate' => date('d/m/Y'),
        ]);
    }
    public function usuario()
    {
        return view(arquivo: 'painel.relatorio.usuario', var: [
            'appTitulo' => 'Relatório de usuário',
            'app' => 'relatorio-usuario',
            'de' => dataRemover(date('Y-m-d'), 7, 'dias', 'd/m/Y'),
            'ate' => date('d/m/Y'),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | GRAFICO DE ACESSO
    |--------------------------------------------------------------------------
    */
    public function getUsuarioAcesso(Request $request)
    {
        $de = $request->de;
        $ate = $request->ate;

        $this->validarData($de, $ate);

        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->json([
            'de' => dataBanco($de),
            'ate' => dataBanco($ate)
        ])->get('/relatorio/usuario-acesso')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }

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
        if (!in_array($local, ['usuario', 'pagina', 'parceiro'])) {
            $this->erroPadrao();
        }

        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->json([
            'de' => dataBanco($de),
            'ate' => dataBanco($ate),
            'local' => $local
        ])->get('/relatorio/mais-acessado')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }

        return mensagemSucesso($dado->dado);
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

        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->json([
            'de' => dataBanco($de),
            'ate' => dataBanco($ate),
            'tipo' => $tipo
        ])->get('/relatorio/dispositivo')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($dado->dado, 'item');

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
        } else if (!validarData($de)) {
            mensagemErro('Data inválida', 'A data de início da busca não é uma data válida.');
        } else if (empty($ate)) {
            mensagemErro('Data obrigatória', 'A data final da busca é obrigatória.');
        } else if (!validarData($ate)) {
            mensagemErro('Data inválida', 'A data final da busca não é uma data válida.');
        }
        return;
    }

    /*
    |--------------------------------------------------------------------------
    | GRÁFICO DE USUÁRIO
    |--------------------------------------------------------------------------
    */
    public function getUsuarioStatus()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->get('/relatorio/usuario-status')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }

        $Montar = new MontarRelatorioModel();
        $relatorio = $Montar->montarRelatorioStatus($dado->dado);

        return mensagemSucesso($relatorio);
    }

    public function getUsuarioEstado()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->get('/relatorio/usuario-estado')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }
        $lista = $dado->dado->lista;

        $outro = [];
        if ($lista[0]->uf == 'outro') {
            $outro = $lista[0];
            unset($lista[0]);
        }

        $Montar = new MontarRelatorioModel();
        $relatorio = $Montar->montarBarra($lista, 'uf', ['total' => 'Total', 'ativo' => 'Ativo', 'inativo' => 'Inativo']);
        $relatorio['header'] = [];

        if ($outro) {
            $relatorio['header'] = [
                ['Usuários sem Estado', $outro->total],
                ['Ativos sem Estado', $outro->ativo],
                ['Inativos sem Estado', $outro->inativo],
            ];
        }

        return mensagemSucesso($relatorio);
    }

    public function getUsuarioGenero()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->get('/relatorio/usuario-genero')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }
        $lista = $dado->dado->lista;

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($lista, 'genero');

        return mensagemSucesso($dado);
    }

    public function getUsuarioFaixaEtaria()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->get('/relatorio/usuario-faixa-etaria')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }
        $lista = $dado->dado->lista;

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($lista, 'faixa');

        return mensagemSucesso($dado);
    }

    public function getUsuarioSituacao()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->get('/relatorio/usuario-situacao')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }
        $lista = $dado->dado->lista;

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($lista, 'situacao');

        return mensagemSucesso($dado);
    }

    public function getUsuarioEstadoCivil()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->get('/relatorio/usuario-estado-civil')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }
        $lista = $dado->dado->lista;

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($lista, 'estado');

        return mensagemSucesso($dado);
    }

    public function getUsuarioAtualizarDado()
    {
        $Api = new ApiHelper(token: true);
        $dado = $Api->headerJson()->get('/relatorio/usuario-atualizar-dado')->object();

        if (!object_key_exists('dado', $dado)) {
            $this->erroPadrao();
        }

        $lista = $dado->dado->lista;

        $Montar = new MontarRelatorioModel();
        $dado = $Montar->montarPizza($lista, 'tempo');

        return mensagemSucesso($dado);
    }
}
