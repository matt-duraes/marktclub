<?php

namespace ApiModel\Endereco;

use ORM\Entity;

final class DataEntity extends Entity
{
    protected string $ormTabela = TABELA_SISTEMA_DATA;
    protected array $ormBuscar = ['id_vinculo', 'local_principal', 'titulo'];
    protected array $ormInsert = ['id_vinculo', 'local_principal', 'titulo'];
    protected string $id_vinculo;

    public function __construct(
        string $vinculo,
        public string $local_principal,
        public string $titulo
    ) {
        $this->id_vinculo = $vinculo;
        $this->salvar();
    }
}
