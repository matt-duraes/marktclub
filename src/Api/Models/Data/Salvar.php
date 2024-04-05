<?php

namespace ApiModel\Data;

use ORM\Entity;

final class Salvar extends Entity
{
    protected string $ormTabela = TABELA_SISTEMA_DATA;
    protected array $ormBuscar = ['id_vinculo', 'id_usuario_equipe', 'indice', 'local_principal', 'mensagem'];
    protected array $ormInsert = ['id_vinculo', 'id_usuario_equipe', 'indice', 'local_principal', 'mensagem'];
    protected string $id_vinculo;
    protected string $local_principal;
    protected int $id_usuario_equipe;

    public function __construct(
        string $vinculo,
        string $local,
        protected string $indice,
        protected string $mensagem
    ) {
        parent::__construct();

        $this->id_vinculo = $vinculo;
        $this->id_usuario_equipe = TOKEN['usuario']->id;
        $this->local_principal = $local;
        $this->salvar();
    }
}
