<?php

namespace ApiModel\Endereco;

use ORM\Entity;
use Modules\Botao;
use Modules\Telefone;
use Modules\EnderecoCep;
use Modules\EnderecoEstado;
use System\Classes\Endereco\Local;

final class EnderecoEntity extends Entity
{
    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    protected array $ormBuscar = [
        'id_vinculo', 'tipo', 'local', 'titulo', 'telefone', 'cep', 'logradouro', 'complemento',
        'referencia', 'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
    ];
    protected array $ormSalvar = [
        'id_vinculo', 'tipo', 'local', 'titulo', 'telefone', 'cep', 'logradouro', 'complemento',
        'referencia', 'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
    ];
    protected string $ormValidarSalvar = '
        tipo|Tipo|obrigatorio|vazio
        local|Local|obrigatorio|vazio|valido
        telefone|Telefone|valido
        cep|CEP|obrigatorio|vazio|valido
        logradouro|Logradouro|obrigatorio|vazio
        bairro|Bairro|obrigatorio|vazio
        cidade|Cidade|obrigatorio|vazio
        estado|Estado|obrigatorio|vazio|valido
        principal|Principal|valido
    ';
    public string $id_vinculo;
    public string $vinculo;
    public string $tipo;
    public Local $local;
    public string $titulo;
    public Telefone $telefone;
    public EnderecoCep $cep;
    public string $logradouro;
    public string $numero;
    public string $complemento;
    public string $referencia;
    public string $bairro;
    public string $cidade;
    public EnderecoEstado $estado;
    public string $pais;
    public ?float $latitude;
    public ?float $longitude;
    public Botao $principal;

    protected function regraSalvar()
    {
        $this->id_vinculo = $this->vinculo;
    }

    protected function regraPosBuscar()
    {
        if (empty($this->latitude)) {
            $this->latitude = null;
        }
        if (empty($this->longitude)) {
            $this->longitude = null;
        }
        $this->vinculo = $this->id_vinculo;
    }
}
