<?php

namespace App\Models\Api\View\Tabela;

final class BuscarModel
{
    private array $arquivoValor;
    private string $path = '';
    private string $diretorio = '';
    private string $arquivo = '';
    public array $retorno = [];

    public function __construct(
        private string $tabela
    )
    {
        $this->setarDiretorioArquivo();
        $this->validarDiretorioArquivo();
        $this->setarPath();
        $this->pegarConteudo();
        $this->montarRetorno();
    }

    private function setarDiretorioArquivo()
    {
        $explode = explode('.', $this->tabela);
        $this->diretorio = $explode[0] ?? '';
        $this->arquivo = $explode[1] ?? '';
    }

    private function validarDiretorioArquivo()
    {
        if(empty($this->diretorio)) {
            $this->erroPadrao('Diretório não foi setado.');
        } elseif(empty($this->arquivo)) {
            $this->erroPadrao('Arquivo não foi setado.');
        }
    }

    private function setarPath()
    {
        $path = DIRETORIO_PRIVADO . '/view_tabela/' . $this->diretorio . '/' . $this->arquivo . '.yaml';
        if(!file_exists($path)) {
            $this->erroPadrao('Path do arquivo não encontrado.');
        }
        $this->path = $path;
    }

    private function montarRetorno(): void
    {
        $retorno = [];
        foreach($this->arquivoValor as $linha) {
            $item = [];
            foreach($linha as $r) {
                $br = $r['br'] ?? '';
                $en = $r['en'] ?? '';
                $es = $r['es'] ?? '';
                $item[] = [
                    'br' => $br,
                    'en' => $en,
                    'es' => $es,
                ];
            }
            $retorno[] = $item;
        }
        $this->retorno = $retorno;
    }

    private function pegarConteudo(): void
    {
        $conteudo = file_get_contents($this->path);
        if(empty($conteudo)) {
            $this->erroPadrao('O arquivo está vazio.');
        }
        try {
            $valor = yaml_parse($conteudo);
        } catch (\Throwable $th) {
            $this->erroPadrao('O conteúdo não é um yaml válido.');
        }

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
