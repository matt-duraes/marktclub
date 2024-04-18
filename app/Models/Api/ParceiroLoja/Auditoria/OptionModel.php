<?php

namespace App\Models\Api\ParceiroLoja\Auditoria;

use App\Classes\ParceiroLoja\Auditoria;
use App\Models\Api\ParceiroLoja\LojaEntity;

final class OptionModel
{
    public LojaEntity $parceiro;
    public Auditoria $auditoria;

    public function __construct(
        string $parceiro,
        string $auditoria,
        public string $mensagem
    ) {
        $this->auditoria = new Auditoria($auditoria);
        $this->validarDado($parceiro);
        $this->buscarParceiro($parceiro);
    }

    private function validarDado(string $parceiro)
    {
        if (empty($parceiro)) {
            mensagemErro('Campo obrigatório!', 'O campo parceiro é obrigatório.');
        } elseif ($this->auditoria->vazio()) {
            mensagemErro('Campo obrigatório!', 'O campo auditoria é obrigatório.');
        } elseif (!$this->auditoria->valido()) {
            mensagemErro('Campo invalido!', 'O campo auditoria não é valido.');
        } elseif (empty($this->mensagem)) {
            mensagemErro('Campo obrigatório!', 'O campo mensagem é obrigatório.');
        }
    }

    private function buscarParceiro(string $parceiro)
    {
        $this->parceiro = new LojaEntity();
        $this->parceiro->uuid($parceiro, mensagem: 'Não foi possível encontrar um parceiro pelo código enviado.');
    }
}
