<?php

namespace App\Classes\UsuarioCliente;

use Status\Status as StatusStatus;

final class Federacao extends StatusStatus
{
    public const FU = 'FU';
    public const AC = 'AC';
    public const AL = 'AL';
    public const AP = 'AP';
    public const AM = 'AM';
    public const BA = 'BA';
    public const CE = 'CE';
    public const DF = 'DF';
    public const ES = 'ES';
    public const GO = 'GO';
    public const MA = 'MA';
    public const MT = 'MT';
    public const MS = 'MS';
    public const MG = 'MG';
    public const PA = 'PA';
    public const PB = 'PB';
    public const PR = 'PR';
    public const PE = 'PE';
    public const PI = 'PI';
    public const RJ = 'RJ';
    public const RN = 'RN';
    public const RS = 'RS';
    public const RO = 'RO';
    public const RR = 'RR';
    public const SC = 'SC';
    public const SP = 'SP';
    public const SE = 'SE';
    public const TO = 'TO';

    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                self::FU => 'Funcionario',
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
