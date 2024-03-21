<?php

namespace App\Models\Api\Solicitacao\Link;

use stdClass;

final class HashModel
{
    public array $dado;
    public stdClass $parceiro;
    public stdClass $clube;
    public int $usuario;
    public int $empresa;

    public function __construct(
        private string $hash
    ) {
        $this->dado = base64Decode($hash, true);
        $this->validarDado();
        $this->parceiro = (object)($this->dado['parceiro'] ?? []);
        $this->clube = (object)($this->dado['clube'] ?? []);
        $this->usuario = $this->dado['usuario'];
        $this->empresa = $this->dado['empresa'];
    }

    private function validarDado()
    {
        if (empty($this->dado)) {
            mensagemStatus(404);
        } elseif (!chaveExiste(['parceiro.id', 'parceiro.limite', 'usuario', 'empresa', 'data'], $this->dado)) {
            mensagemErro('Erro!', 'Não foi possível achar o código, por favor, tente novamente.');
        } elseif ($this->dado['data'] <= agora()) {
            // mensagemErro('Vencido!', 'O link tem validade de 10 minutos, gere um novo link para continuar.');
        }
    }
}
