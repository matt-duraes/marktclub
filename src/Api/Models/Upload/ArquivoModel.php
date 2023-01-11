<?php

namespace ApiModel\Upload;

use ORM\ORM;
use App\Models\Api\UsuarioEquipe\PerfilModel;

final class ArquivoModel extends ORM
{

    protected string $_tabela = TABELA_UPLOAD_ARQUIVO;

    /*
    |--------------------------------------------------------------------------
    | LISTAR ARQUIVOS
    |--------------------------------------------------------------------------
    */
    public function buscarArquivos(int $pagina, string $pesquisa, string $grupo)
    {
        $Grupo = new GrupoEntity();
        $Grupo->id($grupo);

        $grupoId = $Grupo->get('id');
        $where = [['id_upload_grupo', $grupoId]];
        if (!empty($pesquisa)) {
            $where[] = ['nome', 'like', '%' . $pesquisa . '%'];
        }
        $dado = $this
            ->campo([
                'uuid', 'nome', 'extensao', 'tamanho', 'largura', 'altura', 'data_criacao'
            ])
            ->where($where)
            ->order('id', 'DESC')
            ->tabela(TABELA_USUARIO_EQUIPE)
            ->leftJoin('id', 'id_usuario_equipe')
            ->campo(
                [
                    'nome_real', 'nome_perfil', 'uuid', 'imagem_tipo', 'imagem_arquivo',
                    'imagem_facebook', 'imagem_google'
                ],
                'usuario'
            )
            ->pagina($pagina, 20)
            ->read();

        $dado->lista = $this->montarDado($dado->lista);
        return $dado;
    }

    private function montarDado($dado)
    {
        $lista = [];
        $Perfil = new PerfilModel();
        foreach ($dado as $r) {
            $lista[] = (object)[
                'id' => $r->uuid,
                'equipe' => $Perfil->montarUsuario(
                    $r->usuario_uuid,
                    $r->usuario_nome_perfil,
                    $r->usuario_nome_real,
                    $r->usuario_imagem_tipo,
                    $r->usuario_imagem_facebook,
                    $r->usuario_imagem_google,
                    $r->usuario_imagem_arquivo
                ),
                'nome' => $r->nome,
                'extensao' => $r->extensao,
                'tamanho' => !empty($r->tamanho) ? $r->tamanho : '',
                'largura' => !empty($r->largura) ? $r->largura : '',
                'altura' => !empty($r->altura) ? $r->altura : '',
                'arquivo' => arquivoPrivado($r->uuid),
                'data' => $r->data_criacao
            ];
        }
        return $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | MOVER ARQUIVOS
    |--------------------------------------------------------------------------
    */
    public function moverArquivos($arquivo, GrupoEntity $Grupo)
    {
        $idGrupo = $Grupo->get('id');

        foreach ($arquivo as $uuid) {
            $arquivo = $this->campo(['id', 'arquivo'])->where(['uuid', $uuid])->primeiro();
            if (!$arquivo) {
                $this->erroMoverArquivo();
            }

            $salvar = $this->dado(['id_upload_grupo' => $idGrupo])->where(['id', $arquivo->id])->update();
            if (!array_key_exists('id', $salvar)) {
                $this->erroMoverArquivo();
            }
        }
    }
    private function erroMoverArquivo()
    {
        mensagemErro('Erro!', 'Ocorreu um erro em mover um ou mais arquivos.');
    }

    /*
    |--------------------------------------------------------------------------
    | PEGAR ARQUIVO DO GRUPO
    |--------------------------------------------------------------------------
    */
    public function pegarArquivosDoGrupo(array $grupo): array
    {
        if (empty($grupo)) {
            return [];
        }
        return $this->campo(['arquivo'])->where(['id_upload_grupo', 'in', $grupo])->read();
    }
}
