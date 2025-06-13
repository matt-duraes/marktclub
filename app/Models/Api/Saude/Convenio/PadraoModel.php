<?php

namespace App\Models\Api\Saude\Convenio;

use ORM\ORM;
use stdClass;
use Modules\EnderecoEstado;
use App\Models\Api\Auth\Token\TokenHelper;

abstract class PadraoModel extends ORM
{
    public EnderecoEstado $EnderecoEstado;
    public array|stdClass $retorno = [];
    protected bool $painel;
    protected bool $clube;

    public function __construct()
    {
        parent::__construct();
        $this->eClube();
        $this->ePainel();
    }

    protected function validarEstado()
    {
        $this->EnderecoEstado->validar(campo: 'Estado do endereço', vazio: $this->clube);
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

    protected function ePainel(): void
    {
        $Token = new TokenHelper();
        $this->painel = $Token->ePainel();
    }
    protected function eClube(): void
    {
        $Token = new TokenHelper();
        $this->clube = $Token->eClube();
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
