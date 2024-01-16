<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\Entity;
use Modules\Data;
use Modules\Botao;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Procedimento;

final class LojaEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $ormBuscar = [
        'texto_desconto'     => 'desconto_texto',
        'texto_voucher'      => 'voucher_texto',
        'texto_procedimento' => 'procedimento_texto',
        'texto_descricao'    => 'texto',
        'titulo', 'limite_voucher', 'prazo_voucher', 'prazo_voucher_fixo', 'data_contrato_inicio',
        'imagem', 'capa', 'procedimento', 'url', 'status', 'link_site', 'tipo', 'arquivo'
    ];
    protected array $ormRetornoPadrao = ['id', 'titulo', 'link_logo'];
    protected string $capa;
    public string $titulo;
    public ?int $limite_voucher;
    public ?int $prazo_voucher;
    public Data $prazo_voucher_fixo;
    public Data $data_contrato_inicio;
    public string $texto_desconto;
    public string $texto_voucher;
    public string $texto_procedimento;
    public string $texto_descricao;
    public Procedimento $procedimento;
    public string $imagem;
    public string $link_capa_desktop;
    public string $link_capa_mobile;
    public string $link_logo;
    public string $link_site;
    public array $arquivo;
    public Botao $favorito;
    public Status $status;
    public string $tipo;
    public string $url;

    protected function regraPosBuscar()
    {
        if (empty($this->prazo_voucher) || !preg_match('/^[1-9]{1}[0-9]{0,}$/', $this->prazo_voucher)) {
            $this->prazo_voucher = 10;
        }
        $this->link_logo = !empty($this->imagem) ? LINK_ARQUIVO . '/parceiro/' . $this->imagem : '';
        $this->link_capa_desktop = !empty($this->capa) ? LINK_ARQUIVO . '/parceiro/' . $this->capa : '';
        $this->link_capa_mobile = !empty($this->capa) ? LINK_ARQUIVO . '/parceiro/' . $this->capa : '';
        $this->favorito = new Botao('nao');
        $this->link_site = (new LinkSiteModel($this))->link;
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
