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
    protected array $ormSalvar = [];
    protected array $ormInsert = [];
    protected array $ormSalvar = [];

    protected function regraPosBuscar()
    {
        if (empty($this->prazo_voucher) || !preg_match('/^[1-9]{1}[0-9]{0,}$/', $this->prazo_voucher)) {
            $this->prazo_voucher = 10;
        }
        $this->imagem_logo = arquivoPrivado($this->imagem_logo);
        $this->imagem_capa_desktop = arquivoPrivado($this->imagem_capa_desktop);
        $this->imagem_capa_mobile = arquivoPrivado($this->imagem_capa_mobile);
        $this->link_site = (new LinkSiteModel($this))->link;
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
