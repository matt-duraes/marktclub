<?php

namespace App\Helpers\Cfm;

final class ConselhoHelper
{
    public function lista(string $titulo = '')
    {
        $conselho = [
            'BR' => 'CFM',
            'AC' => 'CRM-AC',
            'AL' => 'CRM-AL',
            'AP' => 'CRM-AP',
            'AM' => 'CRM-AM',
            'BA' => 'CRM-BA',
            'CE' => 'CRM-CE',
            'DF' => 'CRM-DF',
            'ES' => 'CRM-ES',
            'GO' => 'CRM-GO',
            'MA' => 'CRM-MA',
            'MT' => 'CRM-MT',
            'MS' => 'CRM-MS',
            'MG' => 'CRM-MG',
            'PA' => 'CRM-PA',
            'PB' => 'CRM-PB',
            'PR' => 'CRM-PR',
            'PE' => 'CRM-PE',
            'PI' => 'CRM-PI',
            'RJ' => 'CRM-RJ',
            'RN' => 'CRM-RN',
            'RS' => 'CRM-RS',
            'RO' => 'CRM-RO',
            'RR' => 'CRM-RR',
            'SC' => 'CRM-SC',
            'SP' => 'CRM-SP',
            'SE' => 'CRM-SE',
            'TO' => 'CRM-TO'
        ];
        return !empty($titulo) ? array_merge(['' => $titulo], $conselho) : $conselho;
    }
}
