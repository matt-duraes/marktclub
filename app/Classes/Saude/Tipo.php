<?php

namespace App\Classes\Saude;

use Status\Status;

class Tipo extends Status
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

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::CLASSICO_REGIONAL  => 'Clássico Regional',
            self::ESTILO_NACIONAL    => 'Estilo Nacional',
            self::ABSOLUTO           => 'Absoluto',
            self::SUPERIOR_NACIONAL  => 'Superior Nacional',
            self::EXCLUSIVO_NACIONAL => 'Exclusivo Nacional',
            self::AMIL_S60QC_RJ      => 'Amil S60QC RJ',
            self::AMIL_S80QC         => 'Amil S80QC',
            self::AMIL_S380QC        => 'Amil S380QC',
            self::AMIL_S450QC        => 'Amil S450QC',
            self::AMIL_S60QC_JUNDIAI => 'Amil S60QC Jundiai',
            self::AMIL_S60QC_SP      => 'Amil S60QC SP',
            self::AMIL_S80QP         => 'Amil S80QP',
            self::AMIL_S380QP        => 'Amil S380QP',
            self::AMIL_S450QP        => 'Amil S450QP',
            self::AMIL_S750R1        => 'Amil S750R1',
            self::AMIL_S750R2        => 'Amil S750R2',
            self::AMIL_S580QP        => 'Amil S580QP',
            self::AMIL_S75QC         => 'Amil S75QC',
            self::AMIL_S75QP         => 'Amil S75QP',
            self::REGIONAL           => 'Regional'
        ]);
    }
}
