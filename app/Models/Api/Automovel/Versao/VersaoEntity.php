<?php

namespace App\Models\Api\Automovel\Versao;

use Http\Request;
use ORM\Entity;
use App\Classes\Automovel\Automovel\TipoProcedimento;

final class VersaoEntity extends Entity
{
    public string $tipo;
    protected string $ormTabela = TABELA_CARRO_MODELO;
    protected array $ormBuscar = [
        'uuid', 'titulo', 'vinculo', 'detalhe', 'cor', 'valor', 'valor_off', 'tipo', 'status'
    ];
    protected array $ormSalvar = [
        'titulo', 'detalhe', 'vinculo', 'cor', 'valor', 'valor_off', 'tipo', 'status'
    ];
    protected string $ormValidarSalvar = '
        detalhe|Detalhe|obrigatorio|vazio
        titulo|Titulo|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio
        valor|Valor|obrigatorio|vazio
    ';
    public string $uuid;
    public string $titulo;
    public string $vinculo;

    public function __construct(
        private ?Request $request = null,
    ) {
        parent::__construct();
    }

    protected function regraPosBuscar()
    {
        $TipoProcedimento = new TipoProcedimento();
        $this->tipo = $TipoProcedimento->indice($this->tipo);
        $this->valor = preg_match('/[a-zA-Z]/', $this->valor) == 0 ? strDinheiro($this->valor) : $this->valor;
        $this->valor_off = preg_match('/[a-zA-Z]/', $this->valor_off) == 0 ? strDinheiro($this->valor_off) : $this->valor_off;
    }
}
