<?php

namespace App\Models\Api\ParceiroCashback;

use ORM\Entity;
use Modules\Decimal;
use App\Classes\Geral\Status;

final class CashbackEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_CASHBACK;
    protected array $ormSalvar = [
        'id_admin_empresa' => '->empresa',
        'titulo', 'texto_descricao', 'texto_restricao', 'texto_outro', 'comissao_minima', 'comissao_maxima',
        'status', 'link_site', 'imagem', 'url'
    ];
    protected array $ormBuscar = [
        'empresa' => 'id_admin_empresa',
        'titulo', 'texto_descricao', 'texto_restricao', 'texto_outro', 'comissao_minima', 'comissao_maxima',
        'status', 'link_site', 'imagem', 'url'
    ];
    public string $titulo;
    public string $texto_descricao;
    public string $texto_restricao;
    public string $texto_outro;
    public Decimal $comissao_minima;
    public Decimal $comissao_maxima;
    public array $empresa;
    public string $link_site;
    public Status $status;
    public string $imagem;
    public string $link_logo;
    public string $url;

    public function __construct()
    {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $this->link_logo = arquivoPrivado($this->imagem);
    }
}
