<?php

namespace App\Models\Api\SolicitacaoVoucher\Trait;

use Modules\Data;

trait TextoTrait
{
    private function montarTexto()
    {
        $this->montarTextoJuridico();
        $this->montarTextoDesconto();
        $this->montarTextoVoucher();
        $this->montarTextoValidar();
    }

    private function montarTextoJuridico()
    {
        $dataContrato = $this->Parceiro->data_contrato_inicio;
        if (!$dataContrato->valido()) {
            $dataContrato = new Data('2022-01-01');
        }

        // @codingStandardsIgnoreStart
        $this->texto_juridico = 'Este convênio é administrado pela empresa Markt Tec Serviços em Tecnologia da Informação, CNPJ. 14.150.830/0001-00 - (Markt Club), com contrato firmado no dia ' . $dataContrato->data() . ' e sua vigência é por prazo indeterminado. Caso tenha algum problema no ato da utilização, favor entrar em contato pelo meios abaixo:';
        // @codingStandardsIgnoreEnd

        if ($this->Construtor->contato_telefone->valido()) {
            $this->texto_juridico .=
                '<br>Telefone: '
                . $this->Construtor->contato_telefone->telefone() . ' (Apenas telefone fixo)';
        }
        if ($this->Construtor->contato_whatsapp->valido()) {
            $this->texto_juridico .=
                '<br>Whatsapp: '
                . $this->Construtor->contato_whatsapp->telefone() . ' (apenas WhatsApp)';
        }
        if ($this->Construtor->contato_email->valido()) {
            $this->texto_juridico .= '<br>E-mail: ' . $this->Construtor->contato_email->email();
        }
    }
    private function montarTextoDesconto()
    {
        $this->texto_desconto = $this->Parceiro->texto_desconto;

        if (in_array($this->Parceiro->id, ['890713a200a9e45aa85e2ae67aa41e74', 'e8b13d7a399bb1124ac7ddd69b5438bd'])) {
            // @codingStandardsIgnoreStart
            $this->texto_desconto = 'O TITULAR terá o direito ao uso, SEM CUSTOS, da SALA VIP DO AEROPORTO INTERNACIONAL DE BRASÍLIA – PRESIDENTE JUSCELINO KUBITSCHEK no primeiro acesso do mês. À partir do segundo acesso, o TITULAR terá desconto de 50% na tarifa vigente, devendo pagar a diferença no balcão. Os DEPENDENTES terão 50% de desconto da tarifa vigente em todos os acessos.';
            // @codingStandardsIgnoreEnd
        }
    }
    private function montarTextoVoucher()
    {
        if (!empty($this->Parceiro->texto_voucher)) {
            $this->texto_voucher = $this->Parceiro->texto_voucher;
        } elseif (!empty($this->Parceiro->texto_procedimento)) {
            $this->texto_voucher = $this->Parceiro->texto_procedimento;
        }
    }
    private function montarTextoValidar()
    {
        $this->texto_validar = 'Para validação, acesse voucher.marktclub.com.br ou utilize o QR Code.';
        if (in_array($this->Parceiro->id, ['890713a200a9e45aa85e2ae67aa41e74', 'e8b13d7a399bb1124ac7ddd69b5438bd'])) {
            $this->texto_validar = '';
        }
    }
}
