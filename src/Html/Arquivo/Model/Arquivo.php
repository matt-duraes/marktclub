<?php

use ORM\Entity;

final class Arquivo extends Entity
{
    protected string $_tabela = TABELA_UPLOAD_ARQUIVO;
    protected array $_buscar = ['id_upload_grupo', 'nome', 'arquivo', 'extensao', 'privado', 'status'];
}
