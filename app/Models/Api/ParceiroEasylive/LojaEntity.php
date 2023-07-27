<?php

namespace App\Models\Api\ParceiroEasylive;

use ORM\Entity;
use Modules\Data;
use App\Classes\Geral\Status;
use App\Classes\ParceiroEasylive\Tipo;

final class LojaEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_EASYLIVE;
    protected array $ormSalvar = [
        'id_admin_empresa' => '->empresa',
        'titulo', 'tipo', 'data_validade', 'status', 'imagem'
    ];
    protected array $ormBuscar = [
        'empresa' => 'id_admin_empresa',
        'titulo', 'tipo', 'data_validade', 'status', 'imagem'
    ];
    public string $titulo;
    public Tipo $tipo;
    public Data $data_validade;
    public Status $status;
    public string $imagem;
    public array $empresa;
    public string $link_imagem;

    protected function regraPosBuscar()
    {
        $this->link_imagem = arquivoPrivado($this->imagem);
    }
}
