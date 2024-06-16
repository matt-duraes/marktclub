<?php

namespace ApiModel\PainelHistorico\Trait;

use Modules\Data;

trait PropriedadeTrait
{
    protected string $relacionado;
    protected string $app;
    protected Data $data_de;
    protected Data $data_ate;
    protected string $pesquisa;
}
