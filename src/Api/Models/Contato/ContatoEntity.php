<?php

namespace ApiModel\Contato;

use Modules\Email;
use Modules\Telefone;
use ORM\Entity;
use System\Classes\Contato\Local;
use System\Classes\Contato\Tipo;
use System\Classes\Contato\Nome;

final class ContatoEntity extends Entity
{
    protected $ormTabela = TABELA_SISTEMA_CONTATO;
    protected $ormBuscar = [
        'id_vinculo', 'contato', 'local', 'tipo',
        'outro', 'nome', 'documento', 'valor'
    ];
    protected $ormSalvar = [
        'id_vinculo', 'contato', 'local', 'tipo',
        'outro', 'nome', 'documento', 'valor'
    ];
    protected $ormValidarSalvar = '
        contato|Contato|obrigatorio|vazio
        local|Local|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio
        nome|Nome|obrigatorio|vazio
        documento|Documento|obrigatorio|vazio
        valor|Valor|obrigatorio|vazio
    ';
    public string $id_vinculo;
    public string $vinculo;
    public string $contato;
    public Local $local;
    public Tipo $tipo;
    public string $outro;
    public Nome $nome;
    public string $documento;
    public Telefone|Email $valor;

    protected function regraSalvar()
    {
        $this->id_vinculo = $this->vinculo;
    }

    protected function regraPosBuscar()
    {
        if ($this->tipo->indice() === Tipo::TELEFONE) {
            $this->valor = new Telefone($this->valor);
        } elseif ($this->tipo->indice() === Tipo::EMAIL) {
            $this->valor = new Email($this->valor);
        }

        $this->vinculo = $this->id_vinculo;
    }
}
