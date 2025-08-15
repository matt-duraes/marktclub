<?php

use App\Classes\CampanhaVoucher\Ordem;
use App\Classes\CampanhaVoucher\Status;
use PainelConfig\Index;

$Painel = new Index('campanha_voucher', new Ordem());

$Painel
    ->campo('data_validade', 'Data Validade', Index::TIPO_NORMAL, Index::FORMATAR_DATA)
    ->campo('data_resgate', 'Data Resgate', Index::TIPO_NORMAL, Index::FORMATAR_DATAHORA)
    ->status('status', 'Status', new Status());

return $Painel;
