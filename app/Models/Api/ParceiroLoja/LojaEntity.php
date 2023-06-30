<?php

namespace App\Models\Api\ParceiroLoja;

use ORM\Entity;
use Modules\Data;
use App\Classes\ParceiroLoja\Status;

final class LojaEntity extends Entity
{
    protected string $ormTabela = TABELA_PARCEIRO_LOJA;
    protected array $ormBuscar = [
        'texto_desconto' => 'desconto_texto',
        'texto_voucher' => 'voucher_texto',
        'texto_procedimento' => 'procedimento_texto',
        'titulo', 'limite_voucher', 'prazo_voucher', 'prazo_voucher_fixo', 'data_contrato_inicio',
        'imagem', 'status'
    ];
    protected array $ormRetornoPadrao = ['id', 'titulo', 'link_logo'];

    public string $titulo;
    public ?int $limite_voucher;
    public ?int $prazo_voucher;
    public Data $prazo_voucher_fixo;
    public Data $data_contrato_inicio;
    public string $texto_desconto;
    public string $texto_voucher;
    public string $texto_procedimento;
    public string $imagem;
    public string $link_logo;
    public Status $status;

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
