<?php

namespace Modules;

use Modules\Trait\ValidarTrait;
use Modules\Trait\ValorRealTrait;

final class EnderecoEstado implements ModuleInterface
{
    use ValidarTrait;
    use ValorRealTrait;

    private array $listaIndiceNome = [
        'AC' => 'Acre',
        'AL' => 'Alagoas',
        'AP' => 'Amapá',
        'AM' => 'Amazonas',
        'BA' => 'Bahia',
        'CE' => 'Ceará',
        'DF' => 'Distrito Federal',
        'ES' => 'Espírito Santo',
        'GO' => 'Goiás',
        'MA' => 'Maranhão',
        'MT' => 'Mato Grosso',
        'MS' => 'Mato Grosso do Sul',
        'MG' => 'Minas Gerais',
        'PA' => 'Pará',
        'PB' => 'Paraíba',
        'PR' => 'Paraná',
        'PE' => 'Pernambuco',
        'PI' => 'Piauí',
        'RJ' => 'Rio de Janeiro',
        'RN' => 'Rio Grande do Norte',
        'RS' => 'Rio Grande do Sul',
        'RO' => 'Rondônia',
        'RR' => 'Roraima',
        'SC' => 'Santa Catarina',
        'SP' => 'São Paulo',
        'SE' => 'Sergipe',
        'TO' => 'Tocantins'
    ];

    public function __toString()
    {
        return $this->estado;
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return $this->estado;
    }

    // doc
    /**
     * Valor que deve ser enviado para o banco de dados
     *
     * @return mixed
     */
    public function banco(): mixed
    {
        return $this->estado;
    }

    // doc
    /**
     * Modulo para Estado
     *
     * @param null|string $estado Estado para o modulo
     */
    public function __construct(
        private ?string $estado = null
    ) {
        $this->valor_real = $estado;
        if (empty($this->estado)) {
            $this->vazio = true;
            $this->valido = false;
            $this->estado = '';
            return;
        } elseif (!$this->validarEstado()) {
            $this->valido = false;
            $this->estado = '';
            return;
        }

        $this->estado = mb_strtoupper($this->estado, 'UTF-8');
    }

    // doc
    /**
     * Pega o Estado do modulo por extenso
     *
     * @return string Retorna o estado
     */
    public function estado(): string
    {
        return $this->listaIndiceNome[$this->estado] ?? '';
    }

    // doc
    /**
     * Pega o Estado do modulo como UF
     *
     * @return string Retorna o estado
     */
    public function uf(): string
    {
        return $this->estado;
    }

    private function validarEstado(): bool
    {
        $estado = mb_strtoupper($this->estado, 'UTF-8');
        $lista = [
            'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG', 'PA', 'PB', 'PR', 'PE',
            'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
        ];
        return in_array($estado, $lista);
    }

    /**
     * Pega um array com a lista de valores válidos no formato indice => nome
     *
     * @param  null|string $titulo Um titulo para o select
     * @return array       Array com os dados
     */
    public function select(?string $titulo = null): array
    {
        if (!empty($titulo)) {
            return ['' => $titulo] + $this->listaIndiceNome;
        }
        return $this->listaIndiceNome;
    }
}
