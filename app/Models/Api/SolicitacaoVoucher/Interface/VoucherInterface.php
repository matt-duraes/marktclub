<?php

namespace App\Models\Api\SolicitacaoVoucher\Interface;

interface VoucherInterface
{
    public function salvar();
    public function uuid(string $id, bool $erro = true, ?string $mensagem = null, ?string $titulo = null);
}
