<?php

namespace App\Models\Api\ConvenioParceiro;

use ORM\Entity;

final class ParceiroEntity extends Entity
{
    protected string $_tabela = TABELA_PARCEIRO_NOVO;
    protected array $_buscar = ['limite_voucher', 'prazo_voucher'];

    public ?int $limite_voucher;
    public ?int $prazo_voucher;

    protected function regraPosBuscar()
    {
        if (empty($this->prazo_voucher) || !preg_match("/^[1-9]{1}[0-9]{0,}$/", $this->prazo_voucher)) {
            $this->prazo_voucher = 10;
        }
    }
    protected function getId()
    {
        return $this->prop('id');
    }
}
