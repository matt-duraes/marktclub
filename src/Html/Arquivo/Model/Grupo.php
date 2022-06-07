<?php

use ORM\Entity;

final class Grupo extends Entity
{
    protected string $_tabela = TABELA_UPLOAD_GRUPO;
    protected array $_buscar = ['id_upload_grupo', 'id_usuario_equipe', 'extensao', 'local', 'diretorio', 'privado'];

    public array $extensao;

    protected function regraPosBuscar()
    {
        if (empty($this->id_upload_grupo)) {
            return;
        }

        $pai = (object)[];
        $id = $this->id_upload_grupo;
        for ($i = 0; $i < 100; ++$i) {
            $dado = $this->campo(['id_upload_grupo', 'extensao', 'local', 'diretorio', 'privado'])->where(['id', $id])->primeiro();
            if (!$dado) {
                return;
            } else if (empty($dado->id_upload_grupo)) {
                $pai = $dado;
                break;
            }
            $id = $dado->id_upload_grupo;
        }
        if (!object_key_exists('id_upload_grupo', $pai)) {
            return;
        }
        $this->extensao = jsonDecode($pai->extensao, true, true);
        $this->local = $pai->local;
        $this->diretorio = $pai->diretorio;
        $this->privado = $pai->privado;
    }

    protected function getEquipe(): array
    {
        $equipe = jsonDecode($this->id_usuario_equipe, true, true);
        if (empty($this->id_upload_grupo)) {
            return $equipe;
        }

        $id = $this->id_upload_grupo;
        for ($i = 0; $i < 100; ++$i) {
            $dado = $this->campo(['id_upload_grupo', 'id_usuario_equipe'])->where(['id', $id])->primeiro();
            if (!$dado) {
                break;
            } else if (empty($dado->id_upload_grupo)) {
                $equipe = jsonDecode($dado->id_usuario_equipe, true, true);
                break;
            }
            $id = $dado->id_upload_grupo;
        }
        return $equipe;
    }
}
