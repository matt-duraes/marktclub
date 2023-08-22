<?php

namespace App\Models\Api\Automovel\Versao;

use ORM\Entity;
use Modules\Dinheiro;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;

final class VersaoEntity extends Entity
{
    protected string $ormTabela = TABELA_AUTOMOVEL_VERSAO;
    protected array $ormBuscar = ['titulo', 'cor', 'valor_de', 'valor_por', 'status'];
    protected array $ormInsert = ['id_automovel_modelo'];
    protected array $ormSalvar = ['titulo', 'cor', 'valor_de', 'valor_por', 'status'];
    protected string $ormValidarInsert = '
        titulo|Título|vazio|obrigatorio
        cor|Cor|obrigatorio
        modelo|Modelo|vazio|obrigatorio
        valor_por|Valor por|vazio|obrigatorio|valido
        status|Status|vazio|obrigatorio|valido
    ';
    public string $modelo;
    public string $titulo;
    public string $cor;
    public Dinheiro $valor_de;
    public Dinheiro $valor_por;
    public Status $status;
    protected int $id_automovel_modelo;

    protected function regraInsert()
    {
        $OrmHelper = new OrmHelper(TABELA_AUTOMOVEL_MODELO);
        $this->id_automovel_modelo = $OrmHelper->pegarIdPeloUuid(
            $this->modelo,
            erroTitulo: 'Não encontrado!',
            erroMensagem: 'O modelo passado não foi encontrado.'
        );
    }
}
