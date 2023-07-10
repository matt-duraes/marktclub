<?php

namespace App\Classes\Saude;

use App\Classes\Saude\Operadoras\AbstractOperadora;
use App\Classes\Saude\Operadoras\Amil;
use App\Classes\Saude\Operadoras\CentralNacionalUnimed;
use Helpers\ValidarHelper;
use Modules\Data;

class PlanoSaude
{
    public ?int $codigoAcomodacao;
    public ?float $valor;

    /**
     * @param AbstractOperadora $operadora Operadora de Saúde
     */
    public function __construct(
        protected AbstractOperadora $operadora
    ) {
        $this->validarDados();
        $this->codigoAcomodacao = $this->operadora->pegarCodigoAcomodacao();
        $this->valor = $this->operadora->simularValor();
    }

    /**
     */
    private function validarDados(): void
    {
        (new ValidarHelper())
            ->valor(
                $this->operadora->pegarDados()['data_nascimento'],
                'Data de Nascimento',
                'Data de Nascimento não é um formato válido'
            )
            ->obrigatorio()
            ->vazio()
            ->valido()
            ->valor(
                $this->operadora->pegarDados()['acomodacao'],
                'Acomodação',
                'Acomodação não encontrada ou inválida'
            )
            ->inArray(array_keys($this->operadora->pegarDados()['acomodacoes']))
            ->obrigatorio()
            ->vazio();

        if (($this->operadora instanceof Amil) || ($this->operadora instanceof CentralNacionalUnimed)) {
            (new ValidarHelper())
                ->valor(
                    $this->operadora->pegarDados()['plano'],
                    'Plano',
                    'Plano não encontrado ou inválido'
                )
                ->obrigatorio()
                ->vazio()
                ->valido()
                ->valor(
                    $this->operadora->pegarDados()['regiao'],
                    'Região',
                    'Região não encontrada ou inválida'
                )
                ->obrigatorio()
                ->vazio()
                ->valido();
        }
    }

    /**
     * @param Data $dataNascimento Data de Nascimento do individuo
     *
     * @return float|null
     */
    public function simularValor(Data $dataNascimento): ?float
    {
        return $this->operadora->simularValor($dataNascimento);
    }
}
