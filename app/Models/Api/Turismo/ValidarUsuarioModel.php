<?php

namespace App\Models\Api\Turismo;

use App\Models\Api\Analytics\HelperModel;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class ValidarUsuarioModel
{
    private string $ultimaData;
    public function __construct(
        private ClienteEntity $Usuario
    ) {
        $this->pegarUltimoRegistro();
        $this->validarDataDoRegistro();
    }

    private function pegarUltimoRegistro()
    {
        $Helper = new HelperModel();
        $registro = $Helper->pegarUltimoRegistroPeloUsuario($this->Usuario->get('id'), ['data_criacao']);
        if (!is_array($registro) || !array_key_exists('data_criacao', $registro)) {
            mensagemStatus(403);
        }
        $this->ultimaData = dataAdicionar($registro['data_criacao'], 20, 'minutos', 'Y-m-d H:i:s');
    }
    private function validarDataDoRegistro()
    {
        if ($this->ultimaData < agora()) {
            mensagemStatus(403);
        }
    }
}
