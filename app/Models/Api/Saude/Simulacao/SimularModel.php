<?php

namespace App\Models\Api\Saude\Simulacao;

use DateTime;
use Modules\Data;
use Modules\EnderecoEstado;

final class SimularModel
{
    private array $arquivo = [];
    private int $indiceTitular = 0;
    private array $indiceDependente = [];

    public array $retorno = [];
    public float $valorTitular = 0;
    public float $valorTotal = 0;
    public array $valorDependente = [];

    public function __construct(
        private string $convenio,
        private Data $titular,
        private array $dependente,
        private EnderecoEstado $estado,
        private ?string $cidade
    )
    {
        $this->montarDependente();
        $this->validarCampo();
        $this->pegarArquivo();
        $this->setarIdade();
        $this->montarRetorno();
    }

    private function setarIdade(): void
    {
        $this->indiceTitular = $this->pegarIndicePorIdade($this->titular->date());
        foreach($this->dependente as $Data) {
            $this->indiceDependente[$Data->data()] = $this->pegarIndicePorIdade($Data->date());
        }
    }

    private function pegarIndicePorIdade(string $data): int
    {
        $data = new DateTime($data);
        $hoje = new DateTime();
        $idade = $hoje->diff($data)->y;
        if($idade <= 18) {
            return 0;
        }
        return min(intdiv($idade - 19, 5) + 1, 9);
    }

    private function montarRetorno(): void
    {
        $retorno = [];
        foreach($this->arquivo as $plano => $r) {
            if(array_key_exists('filtro', $r) && !$this->validarFiltro($r['filtro'])) {
                continue;
            }
            $valor = $this->pegarValor($r['valor']);
            $retorno[] = [
                'titulo' => $r['titulo'] ?? '',
                'plano' => $plano,
                'item' => $r['item'] ?? [],
                'link' => $r['link'] ?? '',
                'target' => $r['target'] ?? '',
                'valor_total' => $valor['total'],
                'valor_lista' => $valor['lista'],
                'valor_detalhe' => $valor['detalhe']
            ];
        }
        $this->retorno = $retorno;
    }
    private function validarFiltro($filtro): bool {
        foreach($filtro as $r) {
            $indice = $r[0] ?? '';
            $condicao = $r[1] ?? '';
            $valor = $r[2] ?? '';
            if(empty($indice) || empty($condicao) || !in_array($indice, ['estado', 'cidade'])) {
                return false;
            }
            $valorComparar = '';
            if($indice === 'cidade') {
                $valorComparar = $this->cidade;
            } elseif($indice === 'estado') {
                $valorComparar = $this->estado->uf();
            }
            $validar =
                $condicao === 'igual' && $valor === $valorComparar ||
                $condicao === 'diferente' && $valor !== $valorComparar ||
                $condicao === 'valor' && in_array($valorComparar, $valor);
            if($validar) {
                continue;
            }
            return false;
        }
        return true;
    }

    private function pegarValor(array $valor): array
    {
        $total = number_format($valor[$this->indiceTitular], 2, '.', '');
        $lista = [
            [
                'nome' => 'Titular',
                'data' => dataBr($this->titular),
                'valor' => $total
            ]
        ];
        $titular = $total;
        $dependente = 0;
        $dependenteNumero = 0;
        foreach($this->indiceDependente as $data => $indice)
        {
            if(!array_key_exists($indice, $valor)) {
                continue;
            }
            $dependenteNumero++;
            $valorDependente = $valor[$indice];
            $lista[] = [
                'nome' => 'Dependente ' . $dependenteNumero,
                'data' => dataBr($data),
                'valor' => number_format($valorDependente, 2, '.', '')
            ];

            $dependente += $valorDependente;
            $total += $valorDependente;
        }

        return [
            'total' => number_format($total, 2, '.', ''),
            'lista' => $lista,
            'detalhe' => [
                'titular' => $titular,
                'dependente' => number_format($dependente, 2, '.', '')
            ]
        ];
    }

    private function montarDependente(): void
    {
        $retorno = [];
        foreach($this->dependente as $data) {
            $Valor = new Data($data);
            if($Valor->vazio()) {
                continue;
            }
            $retorno[] = $Valor;
        }
        $this->dependente = $retorno;
    }

    private function validarCampo(): void
    {
        if(empty($this->convenio)) {
            $this->erroPadrao('Não foi passado um plano para simular.');
        } elseif(!$this->titular->valido()) {
            mensagemErro('Data nascimento inválida!', 'Data de nascimento do titular está inválida.');
        }

        foreach($this->dependente as $Data) {
            if(!$Data->valido()) {
                mensagemErro('Data nascimento inválida!', '!A data de nascimento (' . $Data->real() . ') do dependente está inválida.');
            }
        }
    }

    private function pegarArquivo(): void
    {
        $path = DIRETORIO_PRIVADO . '/plano_valor/' . $this->convenio . '.yaml';
        if(!file_exists($path)) {
            $this->erroPadrao('path do arquivo não encontrado');
        }
        $valor = yaml_parse(file_get_contents($path));
        if(!is_array($valor) || empty($valor)) {
            $this->erroPadrao('erro ao ler arquivo');
        }
        $this->arquivo = $valor;
    }

    private function erroPadrao(string $localhost): void
    {
        mensagemErro(
            'Erro na simulação!',
            'Não foi possível pegar os valores da sua simulação, por favor, tente novamente.',
            status: 500,
            localhost: $localhost
        );
    }
}
