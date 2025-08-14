<?php

use App\Classes\CampanhaVoucher\Ordem;
use App\Classes\CampanhaVoucher\Status;
use PainelConfig\Index;

$Painel = new Index('campanha_voucher', new Ordem());

$Painel
    ->campo('documento_cpf', 'CPF', Index::TIPO_NORMAL, Index::FORMATAR_CPF)
    ->campo('data_validade', 'Data Validade', Index::TIPO_NORMAL, Index::FORMATAR_DATAHORA)
    ->dataCriacao()
    ->dataAtualizacao()
    ->status('status', 'Status', new Status());

return $Painel;
