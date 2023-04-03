<?php

namespace App\Models\Api\Rotina;

use ORM\ORM;
use Modules\Data;

final class RelatorioAnalyticsModel extends ORM
{
    protected string $ormTabela = TABELA_ANALYTICS;

    private array $lista;
    private array $analytics;
    private string $dataAcesso;

    /**
     * @param  null|Data   $data   Data que será processada
     * @param  null|array  $lista  Lista caso já tenha a lista de dados no analytics,
     *                     caso não tenha, será buscado pela data
     */
    public function __construct(
        ?Data $data = null
    ) {
        parent::__construct();

        $this->dataAcesso = !$data->valido() ? dataRemover(hoje(), 1, 'dia') : $data->date();
        $this->buscarTodosRegistros();
        $this->montarDadoAnalytics();
    }

    private function buscarTodosRegistros()
    {
        $this->lista = $this
            ->campo([
                'empresa', 'data_criacao', 'usuario', 'vinculo', 'vinculo_nome', 'url',
                'usuario_nome', 'usuario_cpf', 'dispositivo', 'os', 'browser'
            ])
            ->where([
                ['data_criacao', 'between', [$this->dataAcesso . ' 00:00:00', $this->dataAcesso . ' 23:59:59']]
            ])->read();
    }

    private function montarDadoAnalytics()
    {
        $data = $this->dataAcesso;

        $analyticsUnico = [];
        $analytics = [];
        foreach ($this->lista as $r) {
            // Extrutura inicial
            if (!array_key_exists($r->empresa, $analytics)) {
                $analytics[$r->empresa] = [
                    'dia' => [
                        'id_admin_empresa' => $r->empresa,
                        'quantidade_total' => 0,
                        'quantidade_unico' => 0,
                        'data_acesso' => $data
                    ],
                    'loja' => [],
                    'url' => [],
                    'cliente' => [],
                    'os' => [],
                    'navegador' => [],
                    'dispositivo' => [],
                ];
            }

            if (empty($r->dispositivo)) {
                $r->dispositivo = 'Não identificado';
            }
            if (empty($r->os)) {
                $r->os = 'Não identificado';
            }
            if (empty($r->browser)) {
                $r->browser = 'Não identificado';
            }

            // Analytics
            $analytics[$r->empresa]['dia']['quantidade_total']++;
            $comparar = dataBanco($r->data_criacao) . '-' . $r->usuario;
            if (!array_key_exists($comparar, $analyticsUnico)) {
                $analytics[$r->empresa]['dia']['quantidade_unico']++;
                $analyticsUnico[$comparar] = true;
            }

            // Convenio
            $eConvenio =
                !empty($r->vinculo) &&
                (str_starts_with($r->url, '/convenios') || str_starts_with($r->url, '/parceiro'));

            if ($eConvenio && !array_key_exists($r->vinculo, $analytics[$r->empresa]['loja'])) {
                $analytics[$r->empresa]['loja'][$r->vinculo] = [
                    'id_admin_empresa' => $r->empresa,
                    'id_parceiro_loja' => $r->vinculo,
                    'parceiro_nome' => $r->vinculo_nome,
                    'quantidade' => 1,
                    'data_acesso' => $data
                ];
            } elseif ($eConvenio) {
                $analytics[$r->empresa]['loja'][$r->vinculo]['quantidade']++;
            }

            // URL
            if (!array_key_exists($r->url, $analytics[$r->empresa]['url'])) {
                $analytics[$r->empresa]['url'][$r->url] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'url' => $r->url,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['url'][$r->url]['quantidade']++;
            }

            // CLIENTE
            if (!array_key_exists($r->usuario, $analytics[$r->empresa]['cliente'])) {
                $analytics[$r->empresa]['cliente'][$r->usuario] = [
                    'id_admin_empresa' => $r->empresa,
                    'id_usuario_cliente' => $r->usuario,
                    'usuario_nome' => $r->usuario_nome,
                    'usuario_cpf' => $r->usuario_cpf,
                    'quantidade' => 1,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['cliente'][$r->usuario]['quantidade']++;
            }

            // Dispositivo
            if (!array_key_exists($r->dispositivo, $analytics[$r->empresa]['dispositivo'])) {
                $analytics[$r->empresa]['dispositivo'][$r->dispositivo] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'dispositivo' => $r->dispositivo,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['dispositivo'][$r->dispositivo]['quantidade']++;
            }

            // OS
            if (!array_key_exists($r->os, $analytics[$r->empresa]['os'])) {
                $analytics[$r->empresa]['os'][$r->os] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'os' => $r->os,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['os'][$r->os]['quantidade']++;
            }

            // Navegador
            if (!array_key_exists($r->browser, $analytics[$r->empresa]['navegador'])) {
                $analytics[$r->empresa]['navegador'][$r->browser] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'navegador' => $r->browser,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['navegador'][$r->browser]['quantidade']++;
            }
        }
        $this->analytics = $analytics;
    }

    public function rodarRotina()
    {
        $Loja = new SalvarModel(TABELA_ANALYTICS_LOJA);
        $Cliente = new SalvarModel(TABELA_ANALYTICS_USUARIO);
        $Dispositivo = new SalvarModel(TABELA_ANALYTICS_DISPOSITIVO);
        $Navegador = new SalvarModel(TABELA_ANALYTICS_NAVEGADOR);
        $Os = new SalvarModel(TABELA_ANALYTICS_OS);
        $Url = new SalvarModel(TABELA_ANALYTICS_PAGINA);
        $Dia = new SalvarModel(TABELA_ANALYTICS_ACESSO_DIA);

        foreach ($this->analytics as $empresa) {
            $Loja->salvar($this->dataAcesso, 'id_parceiro_loja', $empresa['loja']);
            $Cliente->salvar($this->dataAcesso, 'id_usuario_cliente', $empresa['cliente']);
            $Dispositivo->salvar($this->dataAcesso, 'dispositivo', $empresa['dispositivo']);
            $Navegador->salvar($this->dataAcesso, 'navegador', $empresa['navegador']);
            $Os->salvar($this->dataAcesso, 'os', $empresa['os']);
            $Url->salvar($this->dataAcesso, 'url', $empresa['url']);

            // Dia
            if ($empresa['dia']['quantidade_total'] > 0 && empty($empresa['dia']['quantidade_unico'])) {
                $empresa['dia']['quantidade_unico'] = 1;
            }
            if ($empresa['dia']['quantidade_total'] > 0) {
                $Dia->salvarDia($this->dataAcesso, $empresa['dia']);
            }
        }
    }
}
