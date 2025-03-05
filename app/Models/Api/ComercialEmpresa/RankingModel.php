<?php

namespace App\Models\Api\ComercialEmpresa;

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
        $indicacoes = $this->campo(['id_usuario_dono'])->where($this->pegarWhere(), false)->read();
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

        $donoIndicacao = [];
        foreach ($indicacoes as $indicacao) {
            if (empty($indicacao->id_usuario_dono)) {
                continue;
            }
            if (array_key_exists($indicacao->id_usuario_dono, $donoIndicacao)) {
                $donoIndicacao[$indicacao->id_usuario_dono]++;
                continue;
            }
            $donoIndicacao[$indicacao->id_usuario_dono] = 1;
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
            if (array_key_exists($idEmpresa, $ranking)) {
                $ranking[$idEmpresa]['quantidade'] = $ranking[$idEmpresa]['quantidade'] + $quantidade;
                continue;
            }
            $ranking[$idEmpresa] = [
                'posicao'    => 0,
                'empresa'    => $nomeEmpresa,
                'quantidade' => $quantidade,
                'elo'        => 'Ferro',
                'cor'        => 'cinza',
                'me'         => $this->idEmpresa == $idEmpresa
            ];
        }
        array_multisort(array_column($ranking, 'quantidade'), SORT_DESC, $ranking);

        // RANQUEMENTO POR ELOS
        usort($ranking, function ($a, $b) {
            return $b['quantidade'] <=> $a['quantidade'];
        });

        for ($i = 1; $i <= 3; $i++) {
            $this->mudarElo(
                $ranking,
                $i - 1,
                1,
                3,
                'Radiante',
                'amarelo'
            );
        }

        for ($i = 4; $i <= 10; $i++) {
            $this->mudarElo(
                $ranking,
                $i - 1,
                4,
                10,
                'Mestre',
                'vermelho'
            );
        }

        for ($i = 11; $i <= 20; $i++) {
            $this->mudarElo(
                $ranking,
                $i - 1,
                11,
                20,
                'Ascendente',
                'verde'
            );
        }

        $posicao = 1;
        for ($i = 0; $i < count($ranking); $i++) {
            $ranking[$i]['posicao'] = $posicao++;
        }
        return $ranking;
    }

    private function mudarElo(
        array &$arr,
        int $offset,
        int $limiteInferior,
        int $limiteSuperior,
        string $novoElo,
        string $cor
    ): void {
        if ($offset >= 0 && $offset < count($arr)) {
            if ($offset >= $limiteInferior - 1 && $offset <= $limiteSuperior - 1) {
                //$arr[$offset]['elo'] = $novoElo;
                $arr[$offset]['cor'] = $cor;
            }
        }
    }
}
