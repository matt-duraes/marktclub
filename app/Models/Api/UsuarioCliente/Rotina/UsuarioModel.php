<?php

namespace App\Models\Api\UsuarioCliente\Rotina;

use ORM\ORM;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\ComercialEmpresa\EmpresaValidaModel;
use App\Models\Api\UsuarioCliente\Trait\DadoInicialRotina;

final class UsuarioModel extends ORM
{
    use DadoInicialRotina;

    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    private array $empresaValida;
    private array $dado;

    public function __construct()
    {
        parent::__construct();

        $this->pegarEmpresasValidas();
        $this->montarDadoInicial();
        $this->buscarTodosRegistros();
        $this->montarDadoAnalytics();
    }

    private function pegarEmpresasValidas()
    {
        $Empresa = new EmpresaValidaModel();
        $this->empresaValida = $Empresa->listarDados();
    }

    private function buscarTodosRegistros()
    {
        $lista = $this
            ->campo(['empresa', 'status', 'estado_civil', 'sexo', 'situacao', 'aniversario', 'uf', 'data_acesso'])
            ->where([
                ['status', 'in', Helper::STATUS_LIBERADO],
                ['empresa', 'in', $this->empresaValida]
            ])
            ->read();

        foreach ($lista as $r) {
            $this->dado[$r->empresa]['usuario']++;
            if ($r->status == 1) {
                $this->dado[$r->empresa]['status_ativo']++;
            } elseif ($r->status == 2) {
                $this->dado[$r->empresa]['status_inativo']++;
            } elseif ($r->status == 3) {
                $this->dado[$r->empresa]['status_bloqueado']++;
            }

            if ($r->estado_civil == 1) {
                $this->dado[$r->empresa]['estado_civil_solteiro']++;
            } elseif ($r->estado_civil == 2) {
                $this->dado[$r->empresa]['estado_civil_casado']++;
            } elseif ($r->estado_civil == 3) {
                $this->dado[$r->empresa]['estado_civil_divorciado']++;
            } elseif ($r->estado_civil == 4) {
                $this->dado[$r->empresa]['estado_civil_viuvo']++;
            } elseif ($r->estado_civil == 5) {
                $this->dado[$r->empresa]['estado_civil_separado']++;
            } else {
                $this->dado[$r->empresa]['estado_civil_sem_dado']++;
            }
        }
    }

    private function montarDadoAnalytics()
    {
    }
}
