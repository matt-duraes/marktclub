<?php

namespace App\Classes\Saude;

use Status\Status;

class Plano extends Status
{
    public const ABSOLUTO = 'absoluto';
    public const AMIL_S60QC_RJ = 'amil_s60qc_rj';
    public const AMIL_S80QC = 'amil_s80qc';
    public const AMIL_S380QC = 'amil_s380qc';
    public const AMIL_S450QC = 'amil_s450qc';
    public const AMIL_S60QC_JUNDIAI = 'amil_s60qc_jundiai';
    public const AMIL_S60QC_SP = 'amil_s60qc_sp';
    public const AMIL_S80QP = 'amil_s80qp';
    public const AMIL_S380QP = 'amil_s380qp';
    public const AMIL_S450QP = 'amil_s450qp';
    public const AMIL_S750R1 = 'amil_s750r1';
    public const AMIL_S750R2 = 'amil_s750r2';
    public const AMIL_S580QP = 'amil_s580qp';
    public const AMIL_S75QC = 'amil_s75qc';
    public const AMIL_S75QP = 'amil_s75qp';
    public const REGIONAL = 'regional';
    public const CLASSICO_REGIONAL = 'classico_regional';
    public const ESTILO_NACIONAL = 'estilo_nacional';
    public const EXCLUSIVO_NACIONAL = 'exclusivo_nacional';
    public const SUPERIOR_NACIONAL = 'superior_nacional';
    public const ESTADUAL = 'estadual';
    public const NACIONAL = 'nacional';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CLASSICO_REGIONAL  => 'Clássico Regional',
            self::ESTILO_NACIONAL    => 'Estilo Nacional',
            self::ABSOLUTO           => 'Absoluto',
            self::SUPERIOR_NACIONAL  => 'Superior Nacional',
            self::EXCLUSIVO_NACIONAL => 'Exclusivo Nacional',
            self::AMIL_S60QC_RJ      => 'Amil Fácil S60 QC RJ',
            self::AMIL_S80QC         => 'Amil Fácil S80 QC',
            self::AMIL_S380QC        => 'Amil S380 QC',
            self::AMIL_S450QC        => 'Amil S450 QC',
            self::AMIL_S60QC_JUNDIAI => 'Amil Fácil S60 QC Jundiai',
            self::AMIL_S60QC_SP      => 'Amil Fácil S60 QC SP',
            self::AMIL_S80QP         => 'Amil Fácil S80 QP',
            self::AMIL_S380QP        => 'Amil S380 QP',
            self::AMIL_S450QP        => 'Amil S450 QP',
            self::AMIL_S750R1        => 'Amil S750 R1',
            self::AMIL_S750R2        => 'Amil S750 R2',
            self::AMIL_S580QP        => 'Amil Fácil S580 QP',
            self::AMIL_S75QC         => 'Amil Fácil S75 QC',
            self::AMIL_S75QP         => 'Amil Fácil S75 QP',
            self::REGIONAL           => 'Regional',
            self::ESTADUAL           => 'Estadual',
            self::NACIONAL           => 'Nacional'
        ]);
    }
}
