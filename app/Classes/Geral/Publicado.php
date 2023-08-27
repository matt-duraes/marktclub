<?php

namespace App\Classes\Geral;

use Modules\Data;
use Modules\DataHora;
use Status\Status as StatusStatus;

final class Publicado extends StatusStatus
{
    public const SIM = 'sim';
    public const NAO = 'nao';

    public function __construct(
        protected Data|DataHora $inicio,
        protected Data|DataHora $final,
        protected bool $ativo
    ) {
        $dataInicio = $inicio->date();
        $dataFinal = $final->date();
        $dataInicioComparacao = $inicio instanceof Data ? hoje() : agora();
        $dataFinalComparacao = $final instanceof Data ? hoje() : agora();

        $this->valor =
            (empty($dataInicio) || $dataInicio <= $dataInicioComparacao) &&
            (empty($dataFinal) || $dataFinal >= $dataFinalComparacao) &&
            $ativo ? 'sim' : 'nao';

        parent::__construct([
            self::SIM   => 'Sim',
            self::NAO   => 'Não'
        ], [
            self::SIM   => 'verde',
            self::NAO   => 'vermelho'
        ]);
    }
}
