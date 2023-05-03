<?php

namespace App\Classes\SolicitacaoAlfa;

use Status\Status;

class Tipo extends Status
{
    // Não apague. Pode ser utíl futuramente
    /*public const CREDITO = 'credito';
    public const PORTABILIDADE = 'portabilidade';
    public const VEICULO = 'veiculo';*/

    public const NOVO = 'novo';
    public const ENVIADO_EMPRESA = 'enviado_empresa';
    public const ENVIADO_USUARIO = 'enviado_usuario';
    public const PROBLEMA = 'problema';
    public const FINALIZADO = 'finalizado';

    public function __construct(
        protected string|int|null $valor = null
    ) {
        parent::__construct([
            self::NOVO            => 'Novo',
            self::ENVIADO_EMPRESA => 'Enviado p/ Empresa',
            self::ENVIADO_USUARIO => 'Enviado p/ Usuário',
            self::PROBLEMA        => 'Problema',
            self::FINALIZADO      => 'Finalizado'
        ]);
    }
}
