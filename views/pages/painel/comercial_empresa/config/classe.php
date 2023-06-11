<?php

use App\Classes\ComercialEmpresa\FinalidadePrivada;
use App\Classes\ComercialEmpresa\FinalidadePublica;

if ($acao == 'finalidade_publica') {
    return (new FinalidadePublica())->select('Escolha uma finalidade');
} elseif ($acao == 'finalidade_privada') {
    return (new FinalidadePrivada())->select('Escolha uma finalidade');
}
return [];
