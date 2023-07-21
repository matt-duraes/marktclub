<?php

namespace App\Classes\Saude\Operadoras;

use App\Classes\Saude\Interface\OperadoraInterface;
use App\Classes\Saude\Plano;
use App\Classes\Saude\Regiao;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Data;

abstract class AbstractOperadora implements OperadoraInterface
{
    protected array $acomodacoes = [];
    protected int $idade;
    protected array|int $valores;

    /**
     * @param Data        $dataNascimento Data de Nascimento do Cliente
     * @param string      $acomodacao     Acomodação
     * @param Plano|null  $plano          Plano de Saúde
     * @param Regiao|null $regiao         Região
     *
     * @throws Exception
     */
    public function __construct(
        protected readonly Data $dataNascimento,
        protected readonly string $acomodacao,
        protected readonly ?Plano $plano = null,
        protected readonly ?Regiao $regiao = null
    ) {
        $this->idade = $this->pegarIdade($this->dataNascimento) ?? 0;
    }

    /**
     * @param Data $dataNascimento Data de Nascimento
     *
     * @return int|null Idade, NULL caso valor inválido
     * @throws Exception
     */
    protected function pegarIdade(Data $dataNascimento): ?int
    {
        return (dataIdade($dataNascimento->valor()) === false) ?: dataIdade($dataNascimento->valor());
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'data_nascimento' => "\Modules\Data",
        'acomodacao'      => 'string',
        'acomodacoes'     => 'array',
        'plano'           => "\App\Classes\Saude\Plano|null",
        'regiao'          => "\App\Classes\Saude\Regiao|null"
    ])]
    public function pegarDados(): array
    {
        $acomodacoes = $this->acomodacoes;
        if ($this->plano !== null) {
            $acomodacoes = $this->acomodacoes[$this->plano->indice()] ?? $this->acomodacoes;
        }
        return [
            'data_nascimento' => $this->dataNascimento,
            'acomodacao'      => $this->acomodacao,
            'acomodacoes'     => $acomodacoes,
            'plano'           => $this->plano,
            'regiao'          => $this->regiao,
        ];
    }

    /**
     * @return int|null Código da Acomodação para o Banco de Dados, NULL caso não encontrado há acomodação
     */
    abstract public function pegarCodigoAcomodacao(): ?int;

    /**
     * @param Data|null $dataNascimento Data de Nascimento (opcional)
     *
     * @return float|null Valor da simulação, NULL caso error ao simular
     */
    abstract public function simularValor(Data $dataNascimento = null): ?float;
}
