<?php

namespace App\Models\Api\Rotina;

use ORM\ORM;

final class RelatorioUsuarioModel extends ORM
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    private array $lista;
    private array $analytics;

    public function __construct()
    {
        parent::__construct();

        $this->buscarTodosRegistros();
        $this->montarDadoAnalytics();
    }

    private function buscarTodosRegistros()
    {
        $this->lista = $this
            ->campo([
                'empresa', 'status', 'estado_civil', 'sexo', 'uf', 'aniversario',
                'data_dado', 'data_criacao', 'situacao'
            ])
            ->where(['status', 'in', [1, 2, 3]])
            ->read();
    }

    private function montarDadoAnalytics()
    {
        $lista = [];
        foreach ($this->lista as $r) {
            $empresa = $r->empresa;
            if (!array_key_exists($r->empresa, $lista)) {
                $lista[$empresa] = $this->montarDadoPadrao($empresa);
            }

            // Status
            $lista[$empresa]['usuario']++;
            if ($r->status == 1) {
                $lista[$empresa]['status_ativo']++;
                $status = 'ativo';
            } else if ($r->status == 2) {
                $lista[$empresa]['status_inativo']++;
                $status = 'inativo';
            } else if ($r->status == 3) {
                $lista[$empresa]['status_bloqueado']++;
            }

            // Estado Civil
            if ($r->estado_civil == 1) {
                $lista[$empresa]['estado_civil_solteiro']++;
            } else if ($r->estado_civil == 2) {
                $lista[$empresa]['estado_civil_casado']++;
            } else if ($r->estado_civil == 3) {
                $lista[$empresa]['estado_civil_divorciado']++;
            } else if ($r->estado_civil == 4) {
                $lista[$empresa]['estado_civil_viuvo']++;
            } else if ($r->estado_civil == 5) {
                $lista[$empresa]['estado_civil_separado']++;
            } else {
                $lista[$empresa]['estado_civil_sem_dado']++;
            }

            // Genero
            if ($r->estado_civil == 1) {
                $lista[$empresa]['genero_masculino']++;
            } else if ($r->estado_civil == 2) {
                $lista[$empresa]['genero_feminino']++;
            } else if ($r->estado_civil == 3) {
                $lista[$empresa]['genero_outro']++;
            } else if ($r->estado_civil == 4) {
                $lista[$empresa]['genero_nao_informado']++;
            } else {
                $lista[$empresa]['genero_sem_dado']++;
            }

            // Data de atualização de dados
            $diaDiferenca = dataDiferencaDia(empty($r->data_dado) ? $r->data_criacao : $r->data_dado, hoje());
            if ($diaDiferenca <= 90) {
                $lista[$empresa]['dado_3_meses']++;
            } else if ($diaDiferenca <= 180) {
                $lista[$empresa]['dado_6_meses']++;
            } else if ($diaDiferenca <= 270) {
                $lista[$empresa]['dado_9_meses']++;
            } else if ($diaDiferenca <= 365) {
                $lista[$empresa]['dado_12_meses']++;
            } else if ($diaDiferenca > 365) {
                $lista[$empresa]['dado_1_ano']++;
            }

            // Idade
            $idade = !empty($r->aniversario) ? dataIdade($r->aniversario) : false;
            if (false === $idade) {
                $lista[$empresa]['idade_sem_dado']++;
            } else if ($idade <= 20) {
                $lista[$empresa]['idade_ate_20']++;
            } else if ($idade <= 30) {
                $lista[$empresa]['idade_ate_30']++;
            } else if ($idade <= 40) {
                $lista[$empresa]['idade_ate_40']++;
            } else if ($idade <= 50) {
                $lista[$empresa]['idade_ate_50']++;
            } else if ($idade <= 60) {
                $lista[$empresa]['idade_ate_60']++;
            } else if ($idade > 60) {
                $lista[$empresa]['idade_mais_60']++;
            }

            // Situação
            if ($r->situacao == 1) {
                $lista[$empresa]['situacao_ativo']++;
            } else if ($r->situacao == 2) {
                $lista[$empresa]['situacao_aposentado']++;
            } else if ($r->situacao == 3) {
                $lista[$empresa]['situacao_pensionista']++;
            } else if ($r->situacao == 4) {
                $lista[$empresa]['situacao_cedido']++;
            } else if ($r->situacao == 5) {
                $lista[$empresa]['situacao_excedente']++;
            } else {
                $lista[$empresa]['situacao_sem_dado']++;
            }

            $estado = [
                'ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma', 'mt', 'ms', 'mg', 'pa', 'pb',
                'pr', 'pe', 'pi', 'rj', 'rn', 'rs', 'ro', 'rr', 'sc', 'sp', 'se', 'to'
            ];

            $uf = !empty($r->uf) ? strCaixaBaixa($r->uf) : '';
            if (in_array($uf, $estado) && in_array($r->status, [1, 2])) {
                $lista[$empresa]['uf_' . $uf . '_total']++;
                $lista[$empresa]['uf_' . $uf . '_' . $status]++;
            }
        }
        $this->lista = $lista;
    }

    public function rodarRotina()
    {
        $Base = new SalvarModel(TABELA_ANALYTICS_BASE_USUARIO);
        foreach ($this->lista as $dado) {
            $Base->salvarSemValidar($dado);
        }
    }

    private function montarDadoPadrao($empresa)
    {
        return [
            'id_admin_empresa' => $empresa,
            'usuario' => 0,
            'status_ativo' => 0,
            'status_inativo' => 0,
            'status_bloqueado' => 0,
            'estado_civil_solteiro' => 0,
            'estado_civil_casado' => 0,
            'estado_civil_divorciado' => 0,
            'estado_civil_viuvo' => 0,
            'estado_civil_separado' => 0,
            'estado_civil_sem_dado' => 0,
            'genero_masculino' => 0,
            'genero_feminino' => 0,
            'genero_outro' => 0,
            'genero_nao_informado' => 0,
            'genero_sem_dado' => 0,
            'dado_3_meses' => 0,
            'dado_6_meses' => 0,
            'dado_9_meses' => 0,
            'dado_12_meses' => 0,
            'dado_1_ano' => 0,
            'idade_ate_20' => 0,
            'idade_ate_30' => 0,
            'idade_ate_40' => 0,
            'idade_ate_50' => 0,
            'idade_ate_60' => 0,
            'idade_mais_60' => 0,
            'idade_sem_dado' => 0,
            'situacao_ativo' => 0,
            'situacao_aposentado' => 0,
            'situacao_pensionista' => 0,
            'situacao_cedido' => 0,
            'situacao_excedente' => 0,
            'situacao_sem_dado' => 0,
            'uf_ac_total' => 0,
            'uf_ac_ativo' => 0,
            'uf_ac_inativo' => 0,
            'uf_al_total' => 0,
            'uf_al_ativo' => 0,
            'uf_al_inativo' => 0,
            'uf_ap_total' => 0,
            'uf_ap_ativo' => 0,
            'uf_ap_inativo' => 0,
            'uf_am_total' => 0,
            'uf_am_ativo' => 0,
            'uf_am_inativo' => 0,
            'uf_ba_total' => 0,
            'uf_ba_ativo' => 0,
            'uf_ba_inativo' => 0,
            'uf_ce_total' => 0,
            'uf_ce_ativo' => 0,
            'uf_ce_inativo' => 0,
            'uf_df_total' => 0,
            'uf_df_ativo' => 0,
            'uf_df_inativo' => 0,
            'uf_es_total' => 0,
            'uf_es_ativo' => 0,
            'uf_es_inativo' => 0,
            'uf_go_total' => 0,
            'uf_go_ativo' => 0,
            'uf_go_inativo' => 0,
            'uf_ma_total' => 0,
            'uf_ma_ativo' => 0,
            'uf_ma_inativo' => 0,
            'uf_mt_total' => 0,
            'uf_mt_ativo' => 0,
            'uf_mt_inativo' => 0,
            'uf_ms_total' => 0,
            'uf_ms_ativo' => 0,
            'uf_ms_inativo' => 0,
            'uf_mg_total' => 0,
            'uf_mg_ativo' => 0,
            'uf_mg_inativo' => 0,
            'uf_pa_total' => 0,
            'uf_pa_ativo' => 0,
            'uf_pa_inativo' => 0,
            'uf_pb_total' => 0,
            'uf_pb_ativo' => 0,
            'uf_pb_inativo' => 0,
            'uf_pr_total' => 0,
            'uf_pr_ativo' => 0,
            'uf_pr_inativo' => 0,
            'uf_pe_total' => 0,
            'uf_pe_ativo' => 0,
            'uf_pe_inativo' => 0,
            'uf_pi_total' => 0,
            'uf_pi_ativo' => 0,
            'uf_pi_inativo' => 0,
            'uf_rj_total' => 0,
            'uf_rj_ativo' => 0,
            'uf_rj_inativo' => 0,
            'uf_rn_total' => 0,
            'uf_rn_ativo' => 0,
            'uf_rn_inativo' => 0,
            'uf_rs_total' => 0,
            'uf_rs_ativo' => 0,
            'uf_rs_inativo' => 0,
            'uf_ro_total' => 0,
            'uf_ro_ativo' => 0,
            'uf_ro_inativo' => 0,
            'uf_rr_total' => 0,
            'uf_rr_ativo' => 0,
            'uf_rr_inativo' => 0,
            'uf_sc_total' => 0,
            'uf_sc_ativo' => 0,
            'uf_sc_inativo' => 0,
            'uf_sp_total' => 0,
            'uf_sp_ativo' => 0,
            'uf_sp_inativo' => 0,
            'uf_se_total' => 0,
            'uf_se_ativo' => 0,
            'uf_se_inativo' => 0,
            'uf_to_total' => 0,
            'uf_to_ativo' => 0,
            'uf_to_inativo' => 0,
            'data_criacao' => agora(),
        ];
    }
}
