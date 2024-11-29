<?php

namespace System\Html\Postman\Models;

use System\Html\Postman\Models\Trait\TokenConstrutorTrait;

final class TokenSelect
{
    use TokenConstrutorTrait;

    private array $selectPadrao = [
        '<option value="sem_token">Sem token</option>',
        '<option value="token">Token</option>',
        '<option value="painel">Login Painel</option>',
    ];
    private array $selectUsuario = [];
    private array $arquivo = [];
    private array $classe = [];

    public function __toString()
    {
        $padrao = implode(PHP_EOL, $this->selectPadrao);
        $usuario = implode(PHP_EOL, $this->selectUsuario);
        if (empty($usuario)) {
            return $padrao;
        }
        return <<<HTML
        <optgroup label="Padrão">
            {$padrao}
        </optgroup>
        <optgroup label="Criado">
            {$usuario}
        </optgroup>
        HTML;
    }

    public function __construct()
    {
        $this->buscarPathArquivo();
        if (empty($this->arquivo)) {
            return;
        }
        $this->setarClasse();
        $this->pegarSelect();
    }

    private function pegarSelect()
    {
        foreach ($this->classe as $classe) {
            $this->selectUsuario[] = $classe->select;
        }
    }
}
