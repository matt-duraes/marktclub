<?php

namespace App\Controllers\Painel;

use Erro\Excecao;
use Http\Response;
use Helpers\ApiHelper;
use App\Controllers\Painel\PadraoController as Controller;

final class DashboardController extends Controller
{
    private string $de;
    private string $ate;
    public function __construct()
    {
        parent::__construct();
        $this->ate = date('Y-m-d');
        $this->de = dataRemover($this->ate, 7, 'days');
    }

    public function index()
    {
        return view(arquivo: 'dashboard', var: [
            'appTitulo' => 'Dashboard',
            'app' => 'dashboard'
        ]);
    }

    public function getAcesso()
    {
        if (sessaoExiste('relatorio.acesso')) {
            $dado = sessao('relatorio.acesso');
        } else {
            $Api = new ApiHelper(token: sessao('TOKEN'));
            $dado = $Api->parametro([
                'de' => $this->de,
                'ate' => $this->ate
            ])->get('/relatorio/usuario-acesso')->object();
            sessao('relatorio.acesso', $dado);
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $this->montarGraficoAcesso($dado)
        ]);
    }
    private function montarGraficoAcesso($acesso)
    {
        $label = [];
        $dado = [
            [
                'dado' => [],
                'cor' => 'verde',
                'label' => 'Total Acesso'
            ],
            [
                'dado' => [],
                'cor' => 'azul',
                'label' => 'Acesso Unico',
            ],
        ];

        foreach ($acesso->dado as $data => $r) {
            $label[] = $data;
            $dado[0]['dado'][] = $r->acesso;
            $dado[1]['dado'][] = $r->unico;
        }

        return [
            'label' => $label,
            'dado' => $dado
        ];
    }

    public function getUsuario()
    {
        if (sessaoExiste('relatorio.status')) {
            $status = sessao('relatorio.status');
        } else {
            $Api = new ApiHelper(token: sessao('TOKEN'));
            $status = $Api->parametro([
                'de' => $this->de,
                'ate' => $this->ate
            ])->get('/relatorio/usuario-status')->object();
        }
        if (sessaoExiste('relatorio.estado')) {
            $estado = sessao('relatorio.estado');
        } else {
            $Api = new ApiHelper(token: sessao('TOKEN'));
            $estado = $Api->parametro([
                'de' => $this->de,
                'ate' => $this->ate
            ])->get('/relatorio/usuario-estado')->object();
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado' => $this->montarGraficoUsuario($status, $estado)
        ]);
    }

    private function montarGraficoUsuario($status, $estado)
    {
        $header = [];
        foreach ($status->dado as $r) {
            $header[] = [$r->titulo, $r->total];
        }

        $label = [];
        $dado = [
            [
                'dado' => [],
                'cor' => 'vermelho',
                'label' => 'Inativo',
            ],
            [
                'dado' => [],
                'cor' => 'verde',
                'label' => 'Ativo'
            ],
        ];

        foreach ($estado->dado as $uf => $r) {
            $label[] = $uf;
            $dado[0]['dado'][] = $r->inativo;
            $dado[1]['dado'][] = $r->ativo;
        }

        return [
            'header' => $header,
            'label' => $label,
            'dado' => $dado
        ];
    }
}
