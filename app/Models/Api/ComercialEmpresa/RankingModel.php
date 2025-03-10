<?php

namespace App\Models\Api\ComercialEmpresa;

use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\ORM;

class RankingModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_COMERCIAL_EMPRESA;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->setarIdEmpresa();
        parent::__construct();
    }

    /**
     * @return array
     * @throws Excecao
     */
    public function gerarRanking(): array
    {
        $indicacoes = $this
            ->campo([
                'id_usuario_dono', 'prospeccao_status'
            ])
            ->where($this->pegarWhere(), false)
            ->read();
        return $this->montarRanking($indicacoes);
    }

    private function pegarWhere(): array
    {
        return ['id_usuario_dono', '<>', 'null'];
    }

    /**
     * @param array $indicacoes
     *
     * @return array
     */
    private function montarRanking(array $indicacoes): array
    {
        if (empty($indicacoes)) {
            return $indicacoes;
        }

        $ProspeccaoStatus = new ProspeccaoStatus();
        $donoIndicacao = [];
        foreach ($indicacoes as $indicacao) {
            if (empty($indicacao->id_usuario_dono)) {
                continue;
            }

            if (!array_key_exists($indicacao->id_usuario_dono, $donoIndicacao)) {
                $donoIndicacao[$indicacao->id_usuario_dono] = [
                    'indicado' => 0,
                    'fechado'  => 0
                ];
            }
            if ($ProspeccaoStatus->indice($indicacao->prospeccao_status) === ProspeccaoStatus::CONCLUIDO) {
                $donoIndicacao[$indicacao->id_usuario_dono]['fechado']++;
            }
            $donoIndicacao[$indicacao->id_usuario_dono]['indicado']++;
        }
        arsort($donoIndicacao);
        $ranking = [];
        $ormHelperEmpresa = new OrmHelper($this->ormTabela);
        $ormHelperEquipe = new OrmHelper(TABELA_USUARIO_EQUIPE);
        foreach ($donoIndicacao as $dono => $quantidade) {
            $idEmpresa = $ormHelperEquipe->pegarCampoPor('id_admin_empresa', ['id', $dono]);
            $nomeEmpresa = $ormHelperEmpresa->pegarCampoPor('nome_fantasia', ['id', $idEmpresa]);
            if (empty($idEmpresa)) {
                continue;
            }
            if (!array_key_exists($idEmpresa, $ranking)) {
                $ranking[$idEmpresa] = [
                    'posicao'  => 0,
                    'empresa'  => $nomeEmpresa,
                    'indicado' => 0,
                    'fechado'  => 0,
                    'me'       => $this->idEmpresa == $idEmpresa
                ];
            }
            $ranking[$idEmpresa]['indicado'] = $ranking[$idEmpresa]['indicado'] + $quantidade['indicado'];
            $ranking[$idEmpresa]['fechado'] = $ranking[$idEmpresa]['fechado'] + $quantidade['fechado'];
        }
        array_multisort(
            array_column($ranking, 'fechado'),
            SORT_DESC,
            array_column($ranking, 'indicado'),
            SORT_DESC,
            $ranking
        );

        /*usort($ranking, function ($a, $b) {
            if (($b['fechado'] <=> $a['fechado']) != 0) {
                return $b['fechado'] <=> $a['fechado'];
            }

            return $b['indicado'] <=> $a['indicado'];
        });*/

        $posicao = 1;
        for ($i = 0; $i < count($ranking); $i++) {
            $ranking[$i]['posicao'] = $posicao++;
        }
        return $ranking;
    }
}
