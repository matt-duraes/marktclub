<?php

namespace App\Models\Api\Saude\Convenio;

use ORM\ORM;
use Modules\EnderecoEstado;
use App\Models\Api\Auth\Token\TokenHelper;

abstract class PadraoModel extends ORM
{
    public EnderecoEstado $EnderecoEstado;
    public array $retorno = [];

    protected function validarEstado()
    {
        $this->EnderecoEstado->validar(campo: 'Estado do endereço', vazio: $this->eClube());
    }

    protected function ordenarRetorno(string $titulo)
    {
        if(empty($this->retorno)) {
            return;
        }
        ksort($this->retorno);
        $retorno = [
            '' => $titulo
        ];
        foreach($this->retorno as $ind => $val) {
            $retorno[$ind] = $val;
        }
        $this->retorno = $retorno;
    }

    protected function pegarEmpresa(): int
    {
        $Token = new TokenHelper();
        return $Token->pegarEmpresa();
    }

    protected function eClube(): bool
    {
        $Token = new TokenHelper();
        return $Token->eClube();
    }

    protected function whereClube(): array
    {
        $Token = new TokenHelper();
        return [
            ['id_admin_empresa', 'json', $Token->pegarEmpresa(erro: true)],
            ['status', 1]
        ];
    }
}
