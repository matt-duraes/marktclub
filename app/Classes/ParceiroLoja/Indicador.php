<?php

namespace App\Classes\ParceiroLoja;

use Status\Status;

final class Indicador extends Status
{
    public const AC = 'AC';
    public const AL = 'AL';
    public const AM = 'AM';
    public const AP = 'AP';
    public const BA = 'BA';
    public const BAII = 'BA II';
    public const CE = 'CE';
    public const DFI = 'DF I';
    public const DFII = 'DF II';
    public const DFIII = 'DF III';
    public const ES = 'ES';
    public const GO = 'GO';
    public const MA = 'MA';
    public const MGI = 'MG I';
    public const MGII = 'MG II';
    public const MS = 'MS';
    public const MT = 'MT';
    public const PA = 'PA';
    public const PB = 'PB';
    public const PE = 'PE';
    public const PI = 'PI';
    public const PR = 'PR';
    public const RJI = 'RJ I';
    public const RJII = 'RJ II';
    public const RJIII = 'RJ III';
    public const RN = 'RN';
    public const RO = 'RO';
    public const RR = 'RR';
    public const RS = 'RS';
    public const SC = 'SC';
    public const SE = 'SE';
    public const SPI = 'SPI';
    public const SPII = 'SP II';
    public const SPIII = 'SP III';
    public const SPIV = 'SP IV';
    public const SPV = 'SP V';
    public const SPVI = 'SP VI';
    public const SPVII = 'SP VII';
    public const SPVIII = 'SP VIII';
    public const TO = 'TO';
    public const VIRIN = 'VIRIN';
    public const DIREG = 'DIREG';

    /**
     * @param string|int|null $valor
     */
    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::AC => 'AC',
            self::AL => 'AL',
            self::AM => 'AM',
            self::AP => 'AP',
            self::BA => 'BA',
            self::BAII => 'BA II',
            self::CE => 'CE',
            self::DFI => 'DF I',
            self::DFII => 'DF II',
            self::DFIII => 'DF III',
            self::ES => 'ES',
            self::GO => 'GO',
            self::MA => 'MA',
            self::MGI => 'MG I',
            self::MGII => 'MG II',
            self::MS => 'MS',
            self::MT => 'MT',
            self::PA => 'PA',
            self::PB => 'PB',
            self::PE => 'PE',
            self::PI => 'PI',
            self::PR => 'PR',
            self::RJI => 'RJ I',
            self::RJII => 'RJ II',
            self::RJIII => 'RJ III',
            self::RN => 'RN',
            self::RO => 'RO',
            self::RR => 'RR',
            self::RS => 'RS',
            self::SC => 'SC',
            self::SE => 'SE',
            self::SPI => 'SP I',
            self::SPII => 'SP II',
            self::SPIII => 'SP III',
            self::SPIV => 'SP IV',
            self::SPV => 'SP V',
            self::SPVI => 'SP VI',
            self::SPVII => 'SP VII',
            self::SPVIII => 'SP VIII',
            self::TO => 'TO',
            self::VIRIN => 'VIRIN',
            self::DIREG => 'DIREG',
        ]);
    }
}
