<?php

namespace ApiModel\Contato;

use ORM\Entity;
use Modules\Cpf;
use Modules\Botao;
use System\Classes\Contato\Tipo;

final class ContatoEntity extends Entity
{
    protected string $ormTabela = TABELA_SISTEMA_CONTATO;
    protected array $ormBuscar = [
        'id_vinculo', 'local_principal', 'local_secundario', 'nome', 'cpf', 'tipo', 'valor',
        'whatsapp', 'principal'
    ];
    protected array $ormInsert = [
        'id_vinculo' => '->vinculo',
        'local_principal', 'local_secundario'
    ];
    protected array $ormSalvar = [
        'nome', 'cpf', 'tipo', 'valor', 'whatsapp', 'principal'
    ];
    protected string $ormValidarSalvar = '
        local_principal|Local principal|obrigatorio|vazio
        local_secundario|Local secundário|obrigatorio|vazio
        nome|Nome|obrigatorio|vazio
        cpf|CPF|valido
        tipo|Tipo|obrigatorio|vazio|valido
        whatsapp|WhatsApp|valido
        principal|Principal|valido
    ';
    public string $id_vinculo;
    public string $vinculo;
    public string $local_principal;
    public string $local_secundario;
    public string $nome;
    public Tipo $tipo;
    public Cpf $cpf;
    public string $valor;
    public Botao $whatsapp;
    public Botao $principal;
    private bool $atualizarPrincipal = false;

    protected function regraSalvar()
    {
        if ($this->principal->bool() && (!$this->ormEntityExiste || empty($this->prop('principal')))) {
            $this->atualizarPrincipal = true;
        }
    }

    protected function regraPosSalvar()
    {
        if ($this->atualizarPrincipal) {
            $this->atualizarPrincipal();
        }
    }

    private function atualizarPrincipal()
    {
        try {
            $this
                ->dado(['principal' => ''])
                ->where([
                    ['local_principal', $this->local_principal],
                    ['local_secundario', $this->local_secundario],
                    ['id_vinculo', $this->vinculo],
                    ['id', '!=', $this->prop('id')]
                ])
                ->update();
        } catch (\Throwable) {
        }
    }

    protected function regraPosBuscar()
    {
        $this->vinculo = $this->id_vinculo;
    }
}
