<?php

namespace App\Models\Api\Saude\Convenio;

final class TabelaModel
{
    private array $arquivoValor;
    public array $retorno = [];
    private array $idade = [
        '0 a 18', '19 a 23', '24 a 28', '29 a 33', '34 a 38', '39 a 43', '44 a 48', '49 a 53', '54 a 58', '59 ou mais'
    ];

    public function __construct(
        private readonly string $convenio,
        private readonly string $tabela
    )
    {
        $this->pegarArquivo();
        $this->montarRetorno();
    }

    private function montarRetorno(): void
    {
        $arquivo = array_values($this->arquivoValor);
        $quantidade = count($arquivo);
        foreach($this->idade as $ind => $idade) {
            $retorno[$ind][] = $idade;
            for($i=0; $i<$quantidade; ++$i) {
                $retorno[$ind][] = number_format($arquivo[$i][$ind], 2, ',', '.');
            }
        }
        $this->retorno = $retorno;
    }

    private function pegarArquivo(): void
    {
        $path = DIRETORIO_PRIVADO . '/saude_tabela/' . $this->convenio . '.' . $this->tabela . '.yaml';
        if(!file_exists($path)) {
            $this->erroPadrao('Path do arquivo não encontrado');
        }
        $valor = yaml_parse(file_get_contents($path));
        if(!is_array($valor) || empty($valor)) {
            $this->erroPadrao('Erro ao ler arquivo');
        }
        $this->arquivoValor = $valor;
    }

    private function erroPadrao(string $localhost)
    {
        mensagemErro(
            'Erro na simulação!',
            'Não foi possível pegar os valores do plano, por favor, tente novamente.',
            status: 500,
            localhost: $localhost
        );
    }
}
