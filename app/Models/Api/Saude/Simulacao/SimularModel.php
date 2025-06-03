<?php

namespace App\Models\Api\Saude\Simulacao;

use DateTime;
use Modules\Data;

final class SimularModel
{
    private array $ordem;
    private array $arquivoValor = [];
    public array $valorDependente = [];
    public array $valorTitular = [];
    public float $valorTotal = 0;
    public array $retorno = [];

    public function __construct(
        private array $escolhido,
        private string $convenio,
        private Data $titular,
        private array $dependente
    )
    {
        $this->montarDependente();
        $this->validarCampo();
        $this->pegarArquivo();
        $this->pegarOrdemEscolha();
        $this->ordenarEscolhido();
        $this->setarListaValorEscolhido();
        $this->adicionarValorUsuario();
        $this->montarRetorno();
    }

    private function montarRetorno()
    {
        $this->retorno = [
            'id' => uuid(),
            'titular' => $this->valorTitular,
            'dependente' => $this->valorDependente,
            'total' => number_format($this->valorTotal, 2),
            'status' => 'novo'
        ];
    }

    private function adicionarValorUsuario()
    {
        $this->valorTitular = $this->pegarValor($this->titular);
        foreach($this->dependente as $Data) {
            $this->valorDependente[] = $this->pegarValor($Data);
        }
    }

    private function pegarValor(Data $Data)
    {
        $dataNascimento = $Data->date();
        $indice = $this->pegarIndicePorIdade($dataNascimento);
        if(!array_key_exists($indice, $this->arquivoValor)) {
            $this->erroPadrao('A idade não existe na lista de preços.');
        }
        $valor = $this->arquivoValor[$indice];
        $this->valorTotal += $valor;
        return [
            'valor' => number_format($valor, 2),
            'data_nascimento' => $dataNascimento
        ];
    }

    private function pegarIndicePorIdade(string $data)
    {
        $data = new DateTime($data);
        $hoje = new DateTime();
        $idade = $hoje->diff($data)->y;
        if($idade <= 18) {
            return 0;
        }
        return min(intdiv($idade - 19, 5) + 1, 9);
    }

    private function montarDependente()
    {
        $retorno = [];
        foreach($this->dependente as $data) {
            $retorno[] = new Data($data);
        }
        $this->dependente = $retorno;
    }

    private function validarCampo()
    {
        if(empty($this->escolhido) || empty($this->convenio)) {
            $this->erroPadrao('Escolhido ou convenio vazios.');
        } elseif(!$this->titular->valido()) {
            mensagemErro('Data nascimento inválida!', 'Data de nascimento do titular está inválida.');
        }

        foreach($this->dependente as $Data) {
            if(!$Data->valido()) {
                mensagemErro('Data nascimento inválida!', '!A data de nascimento (' . $Data->real() . ') do dependente está inválida.');
            }
        }
    }

    private function pegarArquivo()
    {
        $path = DIRETORIO_PRIVADO . '/saude_valor/' . $this->convenio . '.yaml';
        if(!file_exists($path)) {
            $this->erroPadrao('path do arquivo não encontrado');
        }
        $valor = yaml_parse(file_get_contents($path));
        if(!is_array($valor) || empty($valor)) {
            $this->erroPadrao('erro ao ler arquivo');
        }
        $this->arquivoValor = $valor;
    }

    private function pegarOrdemEscolha()
    {
        $ordem = (new OrdemEscolhaModel($this->convenio))->ordem;
        if(empty($ordem)) {
            $this->erroPadrao('Erro ao pegar ordem escolhida');
        }
        $this->ordem = $ordem;
    }

    private function ordenarEscolhido()
    {
        $escolhido = $this->escolhido;
        $ordem = [];
        foreach($this->ordem as $indice) {
            if(!array_key_exists($indice, $escolhido)) {
                $this->erroPadrao('Não foi possível ordenar as escolha do plano.');
            }
            $ordem[] = $escolhido[$indice];
        }
        $this->ordem = $ordem;
    }

    private function erroPadrao(string $localhost)
    {
        mensagemErro(
            'Erro na simulação!',
            'Não foi possível pegar os valores da sua simulação, por favor, tente novamente.',
            status: 500,
            localhost: $localhost
        );
    }

    private function setarListaValorEscolhido()
    {
        $valor = $this->arquivoValor;
        foreach($this->ordem as $indice) {
            if(!array_key_exists($indice, $valor)) {
                $this->erroPadrao('Não foi possível achar indice na lista de preços.');
            }
            $valor = $valor[$indice];
        }
        $this->arquivoValor = $valor;
    }
}
