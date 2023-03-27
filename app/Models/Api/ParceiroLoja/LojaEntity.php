<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\Entity;
use Modules\Data;

final class LojaEntity extends Entity
{
    protected string $_tabela = TABELA_PARCEIRO_NOVO;
    protected array $_buscar = [
        'texto_desconto' => 'desconto_texto',
        'texto_voucher' => 'voucher_texto',
        'texto_procedimento' => 'procedimento_texto',
        'titulo', 'limite_voucher', 'prazo_voucher', 'prazo_voucher_fixo', 'data_contrato_inicio', 'imagem'
    ];
    protected array $_retornoPadrao = ['id', 'titulo', 'link_logo'];

    public ?int $limite_voucher;
    public ?int $prazo_voucher;
    public Data $prazo_voucher_fixo;
    public Data $data_contrato_inicio;
    public string $texto_desconto;
    public string $texto_voucher;
    public string $texto_procedimento;
    public string $imagem;
    public string $link_logo;

    protected function regraPosBuscar()
    {
        if (empty($this->prazo_voucher) || !preg_match("/^[1-9]{1}[0-9]{0,}$/", $this->prazo_voucher)) {
            $this->prazo_voucher = 10;
        }
        $this->link_logo = !empty($this->imagem) ? LINK_ARQUIVO . '/parceiro/' . $this->imagem : '';
    }
    protected function getId()
    {
        return $this->prop('id');
    }
}
