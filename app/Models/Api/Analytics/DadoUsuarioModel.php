<?php

namespace App\Models\Api\Analytics;

use App\Models\Api\Trait\ValidarEmpresaTrait;
use Erro\Excecao;
use Helpers\OrmHelper;
use ORM\ORM;
use stdClass;

final class DadoUsuarioModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_ANALYTICS_DADO_USUARIO;

    /**
     * @param array|string|null $empresa    Filtra por Empresa
     * @param array|string|null $subempresa Filtra por Subempresa
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly array|string|null $empresa = null,
        private readonly array|string|null $subempresa = null
    ) {
        $this->setarIdEmpresa();
        $this->setarIdSubempresa();
        $this->setarIdUsuario();
        parent::__construct();
    }

    /**
     * @return array|array[]
     * @throws Excecao
     */
    public function gerarRelatorio(): array
    {
        $analytics = $this
            ->where($this->pegarWhere(), false)
            ->order('data_criacao')
            ->read();

        if (empty($analytics)) {
            return $this->retornarListaZerada();
        }

        $analytics = $this->pegarPrimerioDasEmpresas($analytics);
        $analytics = $this->somarAsEmpresas($analytics);
        return $this->montarRelatorio($analytics);
    }

    /**
     * @return array
     * @throws Excecao
     */
    private function pegarWhere(): array
    {
        $where = [];
        $ormHelper = new OrmHelper(TABELA_COMERCIAL_EMPRESA);
        if (empty($this->empresa)) {
            $where[] = ['id_admin_empresa', $this->idEmpresa];
        } elseif (validarUuid($this->empresa, false)) {
            $idEmpresa = $ormHelper->pegarIdPeloUuid(
                $this->empresa,
                'Há empresa informada não foi encontrada',
                'Empresa inválida!'
            );
            $where[] = ['id_admin_empresa', $idEmpresa];
        } elseif (is_array($this->empresa)) {
            $where[] = ['id_admin_empresa', 'in', $ormHelper->mudarListaUuidParaId($this->empresa)];
        }

        if (empty($this->subempresa) && !empty($this->idSubempresa) && $this->idSubempresa != 0) {
            $where[] = ['id_admin_subempresa', $this->idSubempresa];
        } elseif (validarUuid($this->subempresa, false)) {
            $idSubempresa = $ormHelper->pegarIdPeloUuid(
                $this->subempresa,
                'Há Subempresa informada não foi encontrada',
                'Subempresa inválida!'
            );
            $where[] = ['id_admin_subempresa', $idSubempresa];
        } elseif (is_array($this->subempresa)) {
            $where[] = ['id_admin_subempresa', 'in', $ormHelper->mudarListaUuidParaId($this->subempresa)];
        }

        return $where;
    }

    /**
     * @return array[]
     */
    private function retornarListaZerada(): array
    {
        $zero = [
            'total' => 0,
            'lista' => []
        ];
        return [
            'status'         => $zero,
            'estado'         => $zero,
            'genero'         => $zero,
            'situacao'       => $zero,
            'estado_civil'   => $zero,
            'atualizar_dado' => $zero,
            'faixa_etaria'   => $zero,
        ];
    }

    /**
     * @param array $analytics
     *
     * @return array
     */
    private function pegarPrimerioDasEmpresas(array $analytics): array
    {
        $resultados = [];
        $idsEncontrados = [];
        foreach ($analytics as $item) {
            $id = $item->id_admin_empresa;
            if (!in_array($id, $idsEncontrados)) {
                $idsEncontrados[] = $id;
                $resultados[] = $item;
            }
        }
        return $resultados;
    }

    /**
     * @param array $analytics
     *
     * @return stdClass
     */
    private function somarAsEmpresas(array $analytics): stdClass
    {
        $somaChaves = new stdClass();
        foreach ($analytics as $objeto) {
            foreach ($objeto as $key => $value) {
                if ($key == 'data_criacao') {
                    continue;
                }

                if (!isset($somaChaves->$key)) {
                    $somaChaves->$key = 0;
                }
                $somaChaves->$key += $value;
            }
        }
        return $somaChaves;
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    public function montarRelatorio(stdClass $analytics): array
    {
        return [
            'status'         => $this->montarStatus($analytics),
            'estado'         => $this->montarEstado($analytics),
            'genero'         => $this->montarGenero($analytics),
            'situacao'       => $this->montarSituacao($analytics),
            'estado_civil'   => $this->montarEstadoCivil($analytics),
            'atualizar_dado' => $this->montarAtualizarDado($analytics),
            'faixa_etaria'   => $this->montarFaixaEtaria($analytics)
        ];
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    private function montarStatus(stdClass $analytics): array
    {
        $usuario = $analytics->usuario - $analytics->status_bloqueado;
        return [
            'total'     => $analytics->usuario,
            'usuario'   => $usuario,
            'bloqueado' => $analytics->status_bloqueado,
            'lista'     => [
                [
                    'status'      => 'Ativo',
                    'total'       => $analytics->status_ativo,
                    'porcentagem' => porcentagem($analytics->status_ativo, $usuario),
                ],
                [
                    'status'      => 'Inativo',
                    'total'       => $analytics->status_inativo,
                    'porcentagem' => porcentagem($analytics->status_inativo, $usuario)
                ]
            ]
        ];
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    private function montarEstado(stdClass $analytics): array
    {
        $dado = [
            'total' => 0,
            'lista' => []
        ];
        $estado = [
            'outro', 'ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma',
            'mt', 'ms', 'mg', 'pa', 'pb', 'pr', 'pe', 'pi', 'rj', 'rn', 'rs',
            'ro', 'rr', 'sc', 'sp', 'se', 'to'
        ];
        foreach ($estado as $uf) {
            $indiceTotal = 'uf_' . $uf . '_total';
            $indiceAtivo = 'uf_' . $uf . '_ativo';
            $indiceInativo = 'uf_' . $uf . '_inativo';
            $indiceBloqueado = 'uf_' . $uf . '_bloqueado';

            $dado['total'] += $analytics->$indiceTotal;
            $dado['lista'][] = [
                'uf'        => strCaixaAlta($uf),
                'total'     => $analytics->$indiceTotal,
                'ativo'     => $analytics->$indiceAtivo,
                'inativo'   => $analytics->$indiceInativo,
                'bloqueado' => $analytics->$indiceBloqueado
            ];
        }
        return $dado;
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    private function montarGenero(stdClass $analytics): array
    {
        return [
            'total' => $analytics->usuario,
            'lista' => [
                [
                    'genero'      => 'Masculino',
                    'total'       => $analytics->genero_masculino,
                    'porcentagem' => porcentagem($analytics->genero_masculino, $analytics->usuario)
                ],
                [
                    'genero'      => 'Feminino',
                    'total'       => $analytics->genero_feminino,
                    'porcentagem' => porcentagem($analytics->genero_feminino, $analytics->usuario)
                ],
                [
                    'genero'      => 'Outro',
                    'total'       => $analytics->genero_outro,
                    'porcentagem' => porcentagem($analytics->genero_outro, $analytics->usuario)
                ],
                [
                    'genero'      => 'Não informado',
                    'total'       => $analytics->genero_nao_informado,
                    'porcentagem' => porcentagem($analytics->genero_nao_informado, $analytics->usuario)
                ],
                [
                    'genero'      => 'Sem dado',
                    'total'       => $analytics->genero_sem_dado,
                    'porcentagem' => porcentagem($analytics->genero_sem_dado, $analytics->usuario)
                ]
            ]
        ];
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    private function montarSituacao(stdClass $analytics): array
    {
        return [
            'total' => $analytics->usuario,
            'lista' => [
                [
                    'situacao'    => 'Ativo',
                    'total'       => $analytics->situacao_ativo,
                    'porcentagem' => porcentagem($analytics->situacao_ativo, $analytics->usuario)
                ],
                [
                    'situacao'    => 'Aposentado',
                    'total'       => $analytics->situacao_aposentado,
                    'porcentagem' => porcentagem($analytics->situacao_aposentado, $analytics->usuario)
                ],
                [
                    'situacao'    => 'Pensionista',
                    'total'       => $analytics->situacao_pensionista,
                    'porcentagem' => porcentagem($analytics->situacao_pensionista, $analytics->usuario)
                ],
                [
                    'situacao'    => 'Cedido',
                    'total'       => $analytics->situacao_cedido,
                    'porcentagem' => porcentagem($analytics->situacao_cedido, $analytics->usuario)
                ],
                [
                    'situacao'    => 'Excedente',
                    'total'       => $analytics->situacao_excedente,
                    'porcentagem' => porcentagem($analytics->situacao_excedente, $analytics->usuario)
                ],
                [
                    'situacao'    => 'Sem dado',
                    'total'       => $analytics->situacao_sem_dado,
                    'porcentagem' => porcentagem($analytics->situacao_sem_dado, $analytics->usuario)
                ]
            ]
        ];
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    private function montarEstadoCivil(stdClass $analytics): array
    {
        return [
            'total' => $analytics->usuario,
            'lista' => [
                [
                    'estado_civil' => 'Solteiro(a)',
                    'total'        => $analytics->estado_civil_solteiro,
                    'porcentagem'  => porcentagem($analytics->estado_civil_solteiro, $analytics->usuario)
                ],
                [
                    'estado_civil' => 'Casado(a)',
                    'total'        => $analytics->estado_civil_casado,
                    'porcentagem'  => porcentagem($analytics->estado_civil_casado, $analytics->usuario)
                ],
                [
                    'estado_civil' => 'Divorciado(a)',
                    'total'        => $analytics->estado_civil_divorciado,
                    'porcentagem'  => porcentagem($analytics->estado_civil_divorciado, $analytics->usuario)
                ],
                [
                    'estado_civil' => 'Viuvo(a)',
                    'total'        => $analytics->estado_civil_viuvo,
                    'porcentagem'  => porcentagem($analytics->estado_civil_viuvo, $analytics->usuario)
                ],
                [
                    'estado_civil' => 'Separado(a)',
                    'total'        => $analytics->estado_civil_separado,
                    'porcentagem'  => porcentagem($analytics->estado_civil_separado, $analytics->usuario)
                ],
                [
                    'estado_civil' => 'Sem dado',
                    'total'        => $analytics->estado_civil_sem_dado,
                    'porcentagem'  => porcentagem($analytics->estado_civil_sem_dado, $analytics->usuario)
                ]
            ]
        ];
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    private function montarAtualizarDado(stdClass $analytics): array
    {
        return [
            'total' => $analytics->usuario,
            'lista' => [
                [
                    'tempo'       => 'Até 3 meses',
                    'total'       => $analytics->dado_3_meses,
                    'porcentagem' => porcentagem($analytics->dado_3_meses, $analytics->usuario)
                ],
                [
                    'tempo'       => 'Até 6 meses',
                    'total'       => $analytics->dado_6_meses,
                    'porcentagem' => porcentagem($analytics->dado_6_meses, $analytics->usuario)
                ],
                [
                    'tempo'       => 'Até 9 meses',
                    'total'       => $analytics->dado_9_meses,
                    'porcentagem' => porcentagem($analytics->dado_9_meses, $analytics->usuario)
                ],
                [
                    'tempo'       => 'Até 12 meses',
                    'total'       => $analytics->dado_12_meses,
                    'porcentagem' => porcentagem($analytics->dado_12_meses, $analytics->usuario)
                ],
                [
                    'tempo'       => 'Mais de 1 ano',
                    'total'       => $analytics->dado_1_ano,
                    'porcentagem' => porcentagem($analytics->dado_1_ano, $analytics->usuario)
                ]
            ]
        ];
    }

    /**
     * @param stdClass $analytics
     *
     * @return array
     */
    private function montarFaixaEtaria(stdClass $analytics): array
    {
        return [
            'total' => $analytics->usuario,
            'lista' => [
                [
                    'faixa_etaria' => 'Até 20 anos',
                    'total'        => $analytics->idade_ate_20,
                    'porcentagem'  => porcentagem($analytics->idade_ate_20, $analytics->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 30 anos',
                    'total'        => $analytics->idade_ate_30,
                    'porcentagem'  => porcentagem($analytics->idade_ate_30, $analytics->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 40 anos',
                    'total'        => $analytics->idade_ate_40,
                    'porcentagem'  => porcentagem($analytics->idade_ate_40, $analytics->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 50 anos',
                    'total'        => $analytics->idade_ate_50,
                    'porcentagem'  => porcentagem($analytics->idade_ate_50, $analytics->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 60 anos',
                    'total'        => $analytics->idade_ate_60,
                    'porcentagem'  => porcentagem($analytics->idade_ate_60, $analytics->usuario)
                ],
                [
                    'faixa_etaria' => 'Mais de 60 anos',
                    'total'        => $analytics->idade_mais_60,
                    'porcentagem'  => porcentagem($analytics->idade_mais_60, $analytics->usuario)
                ],
                [
                    'faixa_etaria' => 'Sem dado',
                    'total'        => $analytics->idade_sem_dado,
                    'porcentagem'  => porcentagem($analytics->idade_sem_dado, $analytics->usuario)
                ]
            ]
        ];
    }
}
