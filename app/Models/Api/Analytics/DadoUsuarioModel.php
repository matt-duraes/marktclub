<?php

namespace App\Models\Api\Analytics;

use ORM\ORM;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;

final class DadoUsuarioModel extends ORM
{
    use ValidarEmpresaTrait;

    protected string $ormTabela = TABELA_ANALYTICS_DADO_USUARIO;
    private int $idEmpresa;

    public function __construct(
        private ?EmpresaEntity $Empresa = null
    ) {
        parent::__construct();

        $this->setarEmpresaDaBusca();
    }
    private function setarEmpresaDaBusca()
    {
        $this->verificarSeExisteToken();
        $this->setarIdUsuario();

        if ($this->Empresa instanceof EmpresaEntity && $this->verificarSePodeMudarEmpresa()) {
            $this->idEmpresa = $this->Empresa->get('id');
            return;
        }
        $this->idEmpresa = TOKEN['empresa']->get('id');
    }

    public function listarDados(): array
    {
        $dado = $this
            ->where([
                ['id_admin_empresa', $this->idEmpresa],
            ])
            ->order('data_criacao', 'DESC')
            ->limit(0, 1)
            ->primeiro();

        if (!$dado) {
            return $this->retornarListaZerada();
        }

        return $this->montarDado($dado);
    }
    private function retornarListaZerada()
    {
        $zero = [
            'total' => 0,
            'lista' => []
        ];
        return [
            'status' => $zero,
            'estado' => $zero,
            'genero' => $zero,
            'situacao' => $zero,
            'estado_civil' => $zero,
            'atualizar_dado' => $zero,
            'faixa_etaria' => $zero,
        ];
    }

    public function montarDado($r): array
    {
        return [
            'status' => $this->montarStatus($r),
            'estado' => $this->montarEstado($r),
            'genero' => $this->montarGenero($r),
            'situacao' => $this->montarSituacao($r),
            'estado_civil' => $this->montarEstadoCivil($r),
            'atualizar_dado' => $this->montarAtualizarDado($r),
            'faixa_etaria' => $this->montarFaixaEtaria($r),
        ];
    }

    private function montarStatus($r): array
    {
        $usuario = $r->usuario - $r->status_bloqueado;
        return [
            'total' => $r->usuario,
            'usuario' => $usuario,
            'bloqueado' => $r->status_bloqueado,
            'lista' => [
                [
                    'status' => 'Ativo',
                    'total' => $r->status_ativo,
                    'porcentagem' => porcentagem($r->status_ativo, $usuario),
                ],
                [
                    'status' => 'Inativo',
                    'total' => $r->status_inativo,
                    'porcentagem' => porcentagem($r->status_inativo, $usuario)
                ]
            ]
        ];
    }

    private function montarEstado($r): array
    {
        $dado = [
            'total' => 0,
            'lista' => []
        ];
        $estado = [
            'outro', 'ac', 'al', 'ap', 'am', 'ba', 'ce', 'df', 'es', 'go', 'ma', 'mt', 'ms', 'mg', 'pa', 'pb',
            'pr', 'pe', 'pi', 'rj', 'rn', 'rs', 'ro', 'rr', 'sc', 'sp', 'se', 'to'
        ];
        foreach ($estado as $uf) {
            $indiceTotal = 'uf_' . $uf . '_total';
            $indiceAtivo = 'uf_' . $uf . '_ativo';
            $indiceInativo = 'uf_' . $uf . '_inativo';
            $indiceBloqueado = 'uf_' . $uf . '_bloqueado';

            $dado['total'] += $r->$indiceTotal;
            $dado['lista'][] = [
                'uf' => strCaixaAlta($uf),
                'total' => $r->$indiceTotal,
                'ativo' => $r->$indiceAtivo,
                'inativo' => $r->$indiceInativo,
                'bloqueado' => $r->$indiceBloqueado
            ];
        }
        return $dado;
    }

    private function montarGenero($r): array
    {
        return [
            'total' => $r->usuario,
            'lista' => [
                [
                    'genero' => 'Masculino',
                    'total' => $r->genero_masculino,
                    'porcentagem' => porcentagem($r->genero_masculino, $r->usuario)
                ],
                [
                    'genero' => 'Feminino',
                    'total' => $r->genero_feminino,
                    'porcentagem' => porcentagem($r->genero_feminino, $r->usuario)
                ],
                [
                    'genero' => 'Outro',
                    'total' => $r->genero_outro,
                    'porcentagem' => porcentagem($r->genero_outro, $r->usuario)
                ],
                [
                    'genero' => 'Não informado',
                    'total' => $r->genero_nao_informado,
                    'porcentagem' => porcentagem($r->genero_nao_informado, $r->usuario)
                ],
                [
                    'genero' => 'Sem dado',
                    'total' => $r->genero_sem_dado,
                    'porcentagem' => porcentagem($r->genero_sem_dado, $r->usuario)
                ],
            ]
        ];
    }

    private function montarSituacao($r): array
    {
        return [
            'total' => $r->usuario,
            'lista' => [
                [
                    'situacao' => 'Ativo',
                    'total' => $r->situacao_ativo,
                    'porcentagem' => porcentagem($r->situacao_ativo, $r->usuario)
                ],
                [
                    'situacao' => 'Aposentado',
                    'total' => $r->situacao_aposentado,
                    'porcentagem' => porcentagem($r->situacao_aposentado, $r->usuario)
                ],
                [
                    'situacao' => 'Pensionista',
                    'total' => $r->situacao_pensionista,
                    'porcentagem' => porcentagem($r->situacao_pensionista, $r->usuario)
                ],
                [
                    'situacao' => 'Cedido',
                    'total' => $r->situacao_cedido,
                    'porcentagem' => porcentagem($r->situacao_cedido, $r->usuario)
                ],
                [
                    'situacao' => 'Excedente',
                    'total' => $r->situacao_excedente,
                    'porcentagem' => porcentagem($r->situacao_excedente, $r->usuario)
                ],
                [
                    'situacao' => 'Sem dado',
                    'total' => $r->situacao_sem_dado,
                    'porcentagem' => porcentagem($r->situacao_sem_dado, $r->usuario)
                ],
            ]
        ];
    }

    private function montarEstadoCivil($r): array
    {
        return [
            'total' => $r->usuario,
            'lista' => [
                [
                    'estado_civil' => 'Solteiro(a)',
                    'total' => $r->estado_civil_solteiro,
                    'porcentagem' => porcentagem($r->estado_civil_solteiro, $r->usuario)
                ],
                [
                    'estado_civil' => 'Casado(a)',
                    'total' => $r->estado_civil_casado,
                    'porcentagem' => porcentagem($r->estado_civil_casado, $r->usuario)
                ],
                [
                    'estado_civil' => 'Divorciado(a)',
                    'total' => $r->estado_civil_divorciado,
                    'porcentagem' => porcentagem($r->estado_civil_divorciado, $r->usuario)
                ],
                [
                    'estado_civil' => 'Viuvo(a)',
                    'total' => $r->estado_civil_viuvo,
                    'porcentagem' => porcentagem($r->estado_civil_viuvo, $r->usuario)
                ],
                [
                    'estado_civil' => 'Separado(a)',
                    'total' => $r->estado_civil_separado,
                    'porcentagem' => porcentagem($r->estado_civil_separado, $r->usuario)
                ],
                [
                    'estado_civil' => 'Sem dado',
                    'total' => $r->estado_civil_sem_dado,
                    'porcentagem' => porcentagem($r->estado_civil_sem_dado, $r->usuario)
                ],
            ]
        ];
    }

    private function montarAtualizarDado($r): array
    {
        return [
            'total' => $r->usuario,
            'lista' => [
                [
                    'tempo' => 'Até 3 meses',
                    'total' => $r->dado_3_meses,
                    'porcentagem' => porcentagem($r->dado_3_meses, $r->usuario)
                ],
                [
                    'tempo' => 'Até 6 meses',
                    'total' => $r->dado_6_meses,
                    'porcentagem' => porcentagem($r->dado_6_meses, $r->usuario)
                ],
                [
                    'tempo' => 'Até 9 meses',
                    'total' => $r->dado_9_meses,
                    'porcentagem' => porcentagem($r->dado_9_meses, $r->usuario)
                ],
                [
                    'tempo' => 'Até 12 meses',
                    'total' => $r->dado_12_meses,
                    'porcentagem' => porcentagem($r->dado_12_meses, $r->usuario)
                ],
                [
                    'tempo' => 'Mais de 1 ano',
                    'total' => $r->dado_1_ano,
                    'porcentagem' => porcentagem($r->dado_1_ano, $r->usuario)
                ],
            ]
        ];
    }

    private function montarFaixaEtaria($r)
    {
        return [
            'total' => $r->usuario,
            'lista' => [
                [
                    'faixa_etaria' => 'Até 20 anos',
                    'total' => $r->idade_ate_20,
                    'porcentagem' => porcentagem($r->idade_ate_20, $r->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 30 anos',
                    'total' => $r->idade_ate_30,
                    'porcentagem' => porcentagem($r->idade_ate_30, $r->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 40 anos',
                    'total' => $r->idade_ate_40,
                    'porcentagem' => porcentagem($r->idade_ate_40, $r->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 50 anos',
                    'total' => $r->idade_ate_50,
                    'porcentagem' => porcentagem($r->idade_ate_50, $r->usuario)
                ],
                [
                    'faixa_etaria' => 'Até 60 anos',
                    'total' => $r->idade_ate_60,
                    'porcentagem' => porcentagem($r->idade_ate_60, $r->usuario)
                ],
                [
                    'faixa_etaria' => 'Mais de 60 anos',
                    'total' => $r->idade_mais_60,
                    'porcentagem' => porcentagem($r->idade_mais_60, $r->usuario)
                ],
                [
                    'faixa_etaria' => 'Sem dado',
                    'total' => $r->idade_sem_dado,
                    'porcentagem' => porcentagem($r->idade_sem_dado, $r->usuario)
                ],
            ]
        ];
    }
}
