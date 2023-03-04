<?php

namespace App\Classes\UsuarioCliente;

use Status\Status as StatusStatus;

final class Federacao extends StatusStatus
{
    const UF = 'UF';
    const AC = 'AC';
    const AL = 'AL';
    const AP = 'AP';
    const AM = 'AM';
    const BA = 'BA';
    const CE = 'CE';
    const DF = 'DF';
    const ES = 'ES';
    const GO = 'GO';
    const MA = 'MA';
    const MT = 'MT';
    const MS = 'MS';
    const MG = 'MG';
    const PA = 'PA';
    const PB = 'PB';
    const PR = 'PR';
    const PE = 'PE';
    const PI = 'PI';
    const RJ = 'RJ';
    const RN = 'RN';
    const RS = 'RS';
    const RO = 'RO';
    const RR = 'RR';
    const SC = 'SC';
    const SP = 'SP';
    const SE = 'SE';
    const TO = 'TO';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::UF => 'Funcionario',
                self::AC => 'Acre',
                self::AL => 'Alagoas',
                self::AP => 'Amapá',
                self::AM => 'Amazonas',
                self::BA => 'Bahia',
                self::CE => 'Ceará',
                self::DF => 'Distrito Federal',
                self::ES => 'Espírito Santo',
                self::GO => 'Goiás',
                self::MA => 'Maranhão',
                self::MT => 'Mato Grosso',
                self::MS => 'Mato Grosso do Sul',
                self::MG => 'Minas Gerais',
                self::PA => 'Pará',
                self::PB => 'Paraíba',
                self::PR => 'Paraná',
                self::PE => 'Pernambuco',
                self::PI => 'Piauí',
                self::RJ => 'Rio de Janeiro',
                self::RN => 'Rio Grande do Norte',
                self::RS => 'Rio Grande do Sul',
                self::RO => 'Rondônia',
                self::RR => 'Roraima',
                self::SC => 'Santa Catarina',
                self::SP => 'São Paulo',
                self::SE => 'Sergipe',
                self::TO => 'Tocantins',
            ],
        );
    }
}
