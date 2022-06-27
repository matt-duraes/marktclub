<?php

use ORM\Entity;

final class Grupo extends Entity
{
    protected string $_tabela = TABELA_UPLOAD_GRUPO;
    protected array $_buscar = ['id_upload_grupo', 'id_usuario_equipe', 'extensao', 'diretorio', 'privado'];
    public array $extensao;

    protected function getEquipe(): array
    {
        return jsonDecode($this->id_usuario_equipe, true, true);
    }
}
