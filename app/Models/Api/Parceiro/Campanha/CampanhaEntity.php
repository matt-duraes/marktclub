<?php

namespace App\Models\Api\Parceiro\Campanha;

use ORM\Entity;
use Modules\Data;
use Modules\ArquivoPrivado;
use App\Classes\Geral\Status;
use App\Models\Api\Parceiro\Loja\LojaHelper;

final class CampanhaEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_CAMPANHA;
    protected array $ormSalvar = [
        'id_parceiro_loja', 'titulo', 'texto', 'imagem_desktop', 'imagem_mobile',
        'link', 'data_inicio', 'data_final', 'status'
    ];
    protected array $ormBuscar = [
        'id_parceiro_loja', 'titulo', 'texto', 'imagem_desktop', 'imagem_mobile',
        'link', 'data_inicio', 'data_final', 'status'
    ];
    protected string $ormValidar = '
        parceiro|Parceiro|obrigatorio|vazio
        titulo|Título|obrigatorio|vazio
        texto|Texto|obrigatorio|vazio
        imagem_desktop|Imagem para desktop|obrigatorio|vazio
        link|Link|obrigatorio|vazio
        data_inicio|Data de início|obrigatorio|vazio|valido
        data_final|Data final|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_parceiro_loja;
    public string $parceiro;
    public string $titulo;
    public string $texto;
    public string $link;
    public ArquivoPrivado $imagem_desktop;
    public ArquivoPrivado $imagem_mobile;
    public Data $data_inicio;
    public Data $data_final;
    public Status $status;

    protected function regraSalvar()
    {
        if ($this->pExiste('parceiro')) {
            $this->id_parceiro_loja = (new LojaHelper())->pegarIdPeloUuid($this->parceiro);
        }
    }

    protected function regraPosBuscar()
    {
        $this->parceiro = (new LojaHelper())->pegarUuidPeloId($this->id_parceiro_loja);
    }
}
