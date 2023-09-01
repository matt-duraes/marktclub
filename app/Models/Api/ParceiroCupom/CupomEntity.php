<?php

namespace App\Models\Api\ParceiroCupom;

use App\Classes\ParceiroCupom\Auditado;
use App\Classes\ParceiroLoja\Categoria;
use Helpers\OrmHelper;
use Modules\Data;
use Modules\DataHora;
use ORM\Entity;

class CupomEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_CUPOM;
    protected array $ormBuscar = [
        'id_parceiro_loja', 'descricao', 'cupom', 'desconto',
        'categoria', 'link', 'validade', 'auditado'
    ];
    protected array $ormSalvar = [
        'auditado'
    ];
    protected string $ormValidarSalvar = '
        auditado|Auditado|obrigatorio|vazio|valido
    ';
    protected int $id_parceiro_loja;
    public array|string $parceiro;
    public string $descricao;
    public string $cupom;
    public string $desconto;
    public Categoria $categoria;
    public string $link;
    public DataHora $validade;
    public Auditado $auditado;

    protected function regraPosBuscar()
    {
        $this->buscarParceiro();
    }

    private function buscarParceiro()
    {
        $parceiro = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarUltimoRegistro(
            where: ['id', $this->id_parceiro_loja],
            campo: ['uuid', 'titulo', 'imagem', 'site', 'url'],
            retorno: 'object'
        );
        if (!$parceiro) {
            return $this->parceiro = [
                'id'     => '',
                'nome' => 'Sem parceiro',
                'imagem' => '',
                'site' => '',
                'url' => ''
            ];
        }
        $this->parceiro = [
            'id'     => $parceiro->uuid,
            'nome' => $parceiro->titulo,
            'imagem' => $parceiro->imagem,
            'site' => $parceiro->site,
            'url' => $parceiro->url
        ];
    }
}
