<?php

namespace ApiModel\Endereco;

use ORM\Entity;
use Modules\Botao;
use Modules\EnderecoCep;
use Modules\EnderecoEstado;

final class EnderecoEntity extends Entity
{
    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    protected array $ormBuscar = [
        'id_vinculo', 'local_principal', 'local_secundario', 'titulo', 'cep', 'logradouro', 'complemento',
        'referencia', 'numero', 'bairro', 'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
    ];
    protected array $ormInsert = [
        'id_vinculo' => '->vinculo',
        'local_principal', 'local_secundario'
    ];
    protected array $ormSalvar = [
        'titulo', 'cep', 'logradouro', 'complemento', 'referencia', 'numero', 'bairro',
        'cidade', 'estado', 'pais', 'latitude', 'longitude', 'principal'
    ];
    public string $id_vinculo;
    public string $vinculo;
    public string $local_principal;
    public string $local_secundario;
    public string $titulo;
    public string|EnderecoCep $cep;
    public string $logradouro;
    public string $numero;
    public string $complemento;
    public string $referencia;
    public string $bairro;
    public string $cidade;
    public string|EnderecoEstado $estado;
    public string $pais;
    public float $latitude;
    public float $longitude;
    public Botao $principal;
    private bool $atualizarPrincipal = false;

    protected function regraSalvar()
    {
        $this->validarEnderecoEstrangeiro();
        if ($this->pais == 'BR') {
            $this->cep = new EnderecoCep($this->cep);
            $this->estado = new EnderecoEstado($this->estado);
            $this->validarEnderecoBrasil();
        }
        if ($this->principal->bool() && (!$this->ormEntityExiste || empty($this->prop('principal')))) {
            $this->atualizarPrincipal = true;
        }
    }

    private function validarEnderecoBrasil()
    {
        $this->ormValidarSalvar = '
            local_principal|Local principal|obrigatorio|vazio
            local_secundario|Local secundário|obrigatorio|vazio
            titulo|Título|obrigatorio|vazio
            cep|CEP|obrigatorio|vazio|valido
            logradouro|Logradouro|obrigatorio|vazio
            bairro|Bairro|obrigatorio|vazio
            cidade|Cidade|obrigatorio|vazio
            estado|Estado|obrigatorio|vazio|valido
            latitude|Latitude|obrigatorio|vazio|valido
            longitude|Longitude|obrigatorio|vazio|valido
            principal|Principal|valido
        ';
    }

    private function validarEnderecoEstrangeiro()
    {
        $this->ormValidarSalvar = '
            local_principal|Local principal|obrigatorio|vazio
            local_secundario|Local secundário|obrigatorio|vazio
            titulo|Título|obrigatorio|vazio
            cep|CEP|obrigatorio|vazio
            logradouro|Logradouro|obrigatorio|vazio
            bairro|Bairro|obrigatorio|vazio
            cidade|Cidade|obrigatorio|vazio
            estado|Estado|vazio
            latitude|Latitude|obrigatorio|vazio|valido
            longitude|Longitude|obrigatorio|vazio|valido
            principal|Principal|valido
        ';
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
