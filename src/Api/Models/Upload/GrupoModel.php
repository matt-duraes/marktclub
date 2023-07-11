<?php

namespace ApiModel\Upload;

use Erro\Excecao;
use ORM\ORM;
use Throwable;

final class GrupoModel extends ORM
{
    /**
     * @var string
     */
    protected string $ormTabela = TABELA_UPLOAD_GRUPO;

    /**
     * @param          $grupo
     * @return array
     * @throws Excecao
     */
    public function listarSubGrupo($grupo): array
    {
        $Grupo = new GrupoEntity();
        $Grupo->uuid($grupo);

        $dado = $this
            ->campo(['uuid', 'nome'])
            ->where(['id_upload_grupo', $Grupo->get('id')])
            ->order('nome', 'ASC')
            ->read();
        return $this->montarGrupo($dado);
    }

    /**
     * @param  array $dado
     * @return array
     */
    private function montarGrupo(array $dado): array
    {
        $lista = [];
        foreach ($dado as $r) {
            $lista[] = (object)[
                'id'   => $r->uuid,
                'nome' => $r->nome
            ];
        }
        return $lista;
    }

    /**
     * Pega a lista dos pais do grupo
     *
     * @param  int|string $grupo Id ou uuid do grupo atual
     * @return array
     * @throws Excecao
     */
    public function pegarGrupoPai(int|string $grupo): array
    {
        $lista = [];

        $id = $this->pegarIdGrupo($grupo);
        if (empty($id)) {
            return $lista;
        }

        for ($i = 0; $i < 100; ++$i) {
            $dado = $this->campo(['uuid', 'id_upload_grupo', 'nome'])->where(['id', $id])->primeiro(retorno: 'array');

            if (!is_array($dado) || !array_key_exists('uuid', $dado)) {
                break;
            }

            $lista[] = (object)[
                'id'   => $dado['uuid'],
                'nome' => $dado['nome'],
            ];

            if (empty($dado['id_upload_grupo'])) {
                break;
            }
            $id = $dado['id_upload_grupo'];
        }

        return array_reverse($lista);
    }

    /**
     * @param        $grupo
     * @return mixed
     */
    private function pegarIdGrupo($grupo): mixed
    {
        if (is_numeric($grupo)) {
            return $grupo;
        }

        try {
            $Grupo = new GrupoEntity();
            $Grupo->uuid($grupo);
            return $Grupo->get('id');
        } catch (Throwable) {
            return '';
        }
    }

    /**
     * Valida se grupo atual faz parte do grupo inicial
     *
     * @param  string  $grupoInicial Uuid do grupo incial
     * @param  string  $grupoAtual   Uuid do grupo atual
     * @return bool
     * @throws Excecao
     */
    public function validarGrupoAtual(string $grupoInicial, string $grupoAtual): bool
    {
        if ($grupoInicial == $grupoAtual) {
            return true;
        }

        $idInicial = $this->pegarIdGrupo($grupoInicial);
        $idAtual = $this->pegarIdGrupo($grupoAtual);
        if (empty($idInicial) || empty($idAtual)) {
            return false;
        }

        for ($i = 0; $i < 100; ++$i) {
            $dado = $this->campo(['id_upload_grupo', 'id'])->where(['id', $idAtual])->primeiro(retorno: 'array');

            if (!is_array($dado) || !array_key_exists('id', $dado)) {
                return false;
            }

            if ($dado['id'] == $idInicial) {
                return true;
            } elseif (empty($dado['id_upload_grupo'])) {
                return false;
            }
            $idAtual = $dado['id_upload_grupo'];
        }
        return false;
    }

    /**
     * Lista toda a arvore de diretorio do grupo
     *
     * @param  string  $grupo Uuid do grupo
     * @return array
     * @throws Excecao
     */
    public function listarTodaArvoreDiretorio(string $grupo): array
    {
        $diretorio = $this->campo(['id', 'uuid', 'nome'])->where(['uuid', $grupo])->primeiro();
        if (!$diretorio) {
            return [];
        }
        return [
            [
                'id'    => $diretorio->uuid,
                'nome'  => $diretorio->nome,
                'lista' => $this->listarTodaArvoreSubDiretorio($diretorio->id)
            ]
        ];
    }

    /**
     * @param          $id
     * @return array
     * @throws Excecao
     */
    private function listarTodaArvoreSubDiretorio($id): array
    {
        $dado = $this->campo(['id', 'uuid', 'nome'])->where(['id_upload_grupo', $id])->read();
        $lista = [];
        foreach ($dado as $r) {
            $lista[] = [
                'id'    => $r->uuid,
                'nome'  => $r->nome,
                'lista' => $this->listarTodaArvoreSubDiretorio($r->id)
            ];
        }

        return $lista;
    }
}
