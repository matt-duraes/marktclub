<?php

namespace App\Classes\Saude\Operadoras;

use App\Classes\Saude\Interface\OperadoraInterface;
use Exception;
use JetBrains\PhpStorm\ArrayShape;
use Modules\Data;

abstract class AbstractOperadora implements OperadoraInterface
{
    protected array $planos = [];
    protected array $acomodacoes = [];
    protected array $acomodacaoCodigo = [];
    protected int $idade;
    protected array|int $valores;

    /**
     * @param Data|null   $titular               Data de nascimento do titular
     * @param string|null $regiaoSelecionada     Região do plano
     * @param string|null $planoSelecionado      Plano de saúde
     * @param string|null $acomodacaoSelecionada Acomodação do plano
     *
     * @throws Exception
     */
    public function __construct(
        protected readonly ?Data $titular = null,
        protected readonly ?string $regiaoSelecionada = null,
        protected readonly ?string $planoSelecionado = null,
        protected string|null $acomodacaoSelecionada = null
    ) {
        if ($this->titular === null) {
            return;
        }
        $this->acomodacaoSelecionada = $this->acomodacaoSelecionada ?? $this->pegarAcomodacoes();
        $this->idade = $this->pegarIdade($this->titular);
    }

    /**
     * @param bool $all Pegar todas as acomodações independente do plano selecionado
     *
     * @return array|string Acomodações disponíveis no plano
     */
    abstract public function pegarAcomodacoes(bool $all = false): array|string;

    /**
     * @param Data $dataNascimento Data de Nascimento
     *
     * @return int Idade
     * @throws Exception
     */
    protected function pegarIdade(Data $dataNascimento): int
    {
        return (dataIdade($dataNascimento->valor()) === false) ? 0 : dataIdade($dataNascimento->valor());
    }

    /**
     * @return array
     */
    #[ArrayShape([
        'titular'     => "\\Modules\\Data",
        'regiao'      => 'string|null',
        'regioes'     => 'array',
        'plano'       => 'string|null',
        'planos'      => 'array',
        'acomodacao'  => 'string|null',
        'acomodacoes' => 'array'
    ])]
    public function pegarDados(): array
    {
        return [
            'titular'     => $this->titular,
            'regiao'      => $this->regiaoSelecionada,
            'regioes'     => $this->pegarRegioes(),
            'plano'       => $this->planoSelecionado,
            'planos'      => $this->pegarPlanos()[$this->regiaoSelecionada] ?? [],
            'acomodacao'  => $this->acomodacaoSelecionada,
            'acomodacoes' => is_string($this->pegarAcomodacoes())
                ? [$this->pegarAcomodacoes()]
                : $this->pegarAcomodacoes()
        ];
    }

    /**
     * @return array Regiões disponíveis
     */
    abstract public function pegarRegioes(): array;

    /**
     * @return array Planos disponíveis na região
     */
    abstract public function pegarPlanos(): array;

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
