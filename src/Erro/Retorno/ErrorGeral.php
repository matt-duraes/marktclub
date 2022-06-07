<?php

namespace Erro\Retorno;

use Erro\Erro;
use Erro\Alerta;
use Erro\Retorno\SolucaoGeral;

abstract class ErrorGeral extends SolucaoGeral
{
    use LogTrait;

    private string $html = '';
    private int $alerta = 0;
    private array $alertaLista = [];
    private int $arquivoAlerta = 0;
    private string $arquivoInicial = '';
    private int $linhaInicial = 0;
    protected string $arquivo;
    protected int $linha;
    protected array $retorno = [];
    protected string $tipoErro;
    protected array $sugestao;
    protected string $root;
    protected string $mensagem;
    protected array $trace;
    protected array $traceString;

    private array $replace = [
        '?' => '&#63;',
        '<' => '&#60;',
        '\\' => '&#92;',
        '>' => '&#62;'
    ];

    public function __toString(): string
    {
        return $this->html();
    }

    public function __construct(Erro | Alerta | \Throwable $error)
    {
        if (SISTEMA == 'PRODUCAO') {
            $this->salvarLogErro(
                $error->getMessage(),
                $error->getCode(),
                $error->getFile(),
                $error->getLine(),
                $error->getTrace()
            );
        }

        $this->root = $this->montarRoot();
        $this->arquivoInicial = $error->getFile();
        $this->linhaInicial = $error->getLine();

        $trace = $error->getTrace();
        $this->mensagem = $error->getMessage();

        $this->pegarNomeLinhaArquivo($error->getFile(), $error->getLine(), $trace);
        $this->trace = $this->montarTrace($trace);
        $this->traceString = $this->montarTraceString($error->getTraceAsString());

        $this->sugestao = $this->pegarSolucaoGeral($error->getMessage());

        $this->imprimirHtml();
    }

    public function html()
    {
        return $this->html;
    }

    protected function pegarNomeLinhaArquivo(string $arquivo, int $linha, array $trace)
    {
        $arquivoRetorno = $this->retorno['arquivo'] ?? '';
        $traceIndice = str_replace('trace:', '', $arquivoRetorno);
        if (empty($arquivoRetorno) || !$trace) {
            $this->arquivo = $arquivo;
            $this->linha = $linha;
            return true;
        } elseif (
            preg_match("/^trace\:[0-9]+/", $arquivoRetorno) &&
            isset($trace[$traceIndice], $trace[$traceIndice]['file'], $trace[$traceIndice]['line'])
        ) {
            $this->alerta++;
            $this->arquivoAlerta = $this->alerta;
            $this->alertaLista[$this->alerta] = 'Mudamos o arquivo onde foi disparar o erro para apontar para o arquivo real do erro no trace #' . $traceIndice . '.';

            $this->arquivo = $trace[$traceIndice]['file'];
            $this->linha = $trace[$traceIndice]['line'];
            return false;
        }
        $i = 0;
        foreach ($trace as $rLinha) {
            if (isset($rLinha['file'], $rLinha['line']) && $rLinha['file'] == $arquivoRetorno) {
                $this->arquivo = $arquivoRetorno;
                $this->linha = $rLinha['file'];

                $this->arquivoAlerta = $this->alerta;
                $this->alertaLista[$this->alerta] = 'Mudamos o arquivo onde foi disparar o erro para apontar para o arquivo real do erro no trace #' . $i . '.';
                return true;
            }
            $i++;
        }
        $this->arquivo = $arquivo;
        $this->linha = $linha;
        return true;
    }

    protected function imprimirHtml()
    {
        $S = (object)$_SERVER;

        $_arquivo = str_replace($this->root, '', $this->arquivo);
        $_arquivoAlerta = $this->arquivoAlerta;
        $_editor = $this->pegarUrlDoEditor($_arquivo);
        $_mensagem = $this->mensagem;
        $_linha = $this->linha;

        $_sugestao = $this->sugestao;

        $_alerta = $this->alertaLista;

        $_tipo = $this->tipoErro;

        $_trace = $this->trace;
        $_traceString = $this->traceString;
        $_GET = [];
        $_POST = [];
        $_PUT = [];
        $_FILES = [];
        $_metodo = $S->REQUEST_METHOD ?? '';
        $_url_dominio = $S->HTTP_HOST ?? 'Erro ao pegar domínio';
        $_url_uri = explode('?', $S->REQUEST_URI ?? 'Erro ao pegar URI')[0];
        $_url_porta = $S->SERVER_PORT ?? 'Erro ao pegar porta';
        $_url_protocolo = 'Erro ao pegar protocolo';
        if (isset($S->HTTPS)) {
            $_url_protocolo = $S->HTTPS == 'on' ? 'https' : 'http';
        }

        $_server = $this->limparListaHtml($S);
        $_header = $this->limparListaHtml(getallheaders());

        ob_start();
        require ROOT . '/src/Html/Erro/erro.php';
        $this->html = ob_get_clean();
    }

    private function limparListaHtml($lista)
    {
        if (!$lista) {
            return [];
        }
        $array = [];
        foreach ($lista as $ind => $val) {
            $array[$ind] = str_replace(
                array_keys($this->replace),
                array_values($this->replace),
                $val
            );
        }
        return $array;
    }

    protected function montarRoot(): string
    {
        $root = explode('/', $_SERVER['DOCUMENT_ROOT']);
        array_pop($root);
        return implode('/', $root);
    }

    protected function montarTrace($trace)
    {
        if (!$trace) {
            return [];
        }

        $root = $this->root;
        $lista = [];
        $arquivoPrincipalExiste = false;
        $arquivoInicialExiste = false;
        $arquivoPrincipal = str_replace($this->root, '', $this->arquivo);
        $arquivoInicial = str_replace($this->root, '', $this->arquivoInicial);
        foreach ($trace as $r) {
            if (!isset($r['file']) || !isset($r['line'])) {
                continue;
            }

            $classeFuncao = '';
            $classeFuncaoHtml = '';
            $classe = '';
            $funcao = '';

            if (
                isset($r['function']) &&
                !empty($r['function']) &&
                isset($r['class']) &&
                !empty($r['class'])
            ) {
                $classe = $r['class'];
                $funcao = $r['function'] . '()';
                $classeFuncao = $r['class'] . '::' . $r['function'] . '()';
                $classeFuncaoHtml = '
                    <span class="trace_classe">' . $r['class'] . '</span>
                    <span class="trace_ponto">::</span>
                    <span class="trace_metodo">' . $r['function'] . '</span>
                    <span class="trace_parente">()</span>
                ';
            } elseif (
                isset($r['function']) &&
                !empty($r['function'])
            ) {
                $funcao = $r['function'] . '()';
                $classeFuncao = $r['function'] . '()';
                $classeFuncaoHtml = '
                    <span class="trace_metodo">' . $r['function'] . '</span>
                    <span class="trace_parente">()</span>
                ';
            }

            $arquivo = str_replace($root, '', $r['file']);
            if ($arquivo == $arquivoPrincipal) {
                $arquivoPrincipalExiste = true;
            }
            if ($arquivo == $arquivoInicial) {
                $arquivoInicialExiste = true;
            }
            $lista['id_' . md5(uniqid(time()))] = [
                'alerta' => '',
                'arquivo' => $arquivo,
                'editor' => $this->pegarUrlDoEditor($arquivo),
                'linha' => $r['line'],
                'codigo' => $this->montarArrayDoCodigo($r['file'], $r['line']),
                'classeFuncao' => [
                    'valor' => $classeFuncao,
                    'html' => $classeFuncaoHtml
                ],
                'classe' => $classe,
                'funcao' => $funcao
            ];
        }
        if (!$arquivoPrincipalExiste && !empty($arquivoPrincipal)) {
            $this->alerta++;
            $this->alertaLista[$this->alerta] = 'Por algum motivo, o arquivo não está na pilha de arquivos, adicionamos ele no topo para facilitar o debug.';
            $lista = array_merge([
                'id_' . md5(uniqid(time())) => [
                    'alerta' => $this->alerta,
                    'arquivo' => $arquivoPrincipal,
                    'editor' => $this->pegarUrlDoEditor($arquivoPrincipal),
                    'linha' => $this->linha,
                    'codigo' => $this->montarArrayDoCodigo($this->arquivo, $this->linha),
                    'classeFuncao' => [
                        'valor' => '',
                        'html' => ''
                    ],
                    'classe' => '',
                    'funcao' => ''
                ]
            ], $lista);
        }
        if (!$arquivoInicialExiste && !empty($arquivoInicial) && $arquivoInicial != $arquivoPrincipal) {
            $this->alerta++;
            $this->alertaLista[$this->alerta] = 'Por algum motivo, o arquivo inicial do erro não está no trace, adicionamos ele no topo pra facilitar o debug.';
            $lista = array_merge([
                'id_' . md5(uniqid(time())) => [
                    'alerta' => $this->alerta,
                    'arquivo' => $arquivoInicial,
                    'editor' => $this->pegarUrlDoEditor($arquivoInicial),
                    'linha' => $this->linhaInicial,
                    'codigo' => $this->montarArrayDoCodigo($this->arquivoInicial, $this->linhaInicial),
                    'classeFuncao' => [
                        'valor' => '',
                        'html' => ''
                    ],
                    'classe' => '',
                    'funcao' => ''
                ]
            ], $lista);
        }
        return $lista;
    }

    protected function montarTraceString($trace)
    {
        $trace = explode(PHP_EOL, $trace);
        if (empty($trace)) {
            return $this->retornarTraceStringVazio();
        }

        $array = [];
        $i = 0;
        foreach ($trace as $linha) {
            $array[] = '#' . $i . preg_replace('/^\#[0-9]+/', '', $linha);
            $i++;
        }
        return $array;
    }

    private function retornarTraceStringVazio()
    {
        return [
            '#0  ' . $this->arquivo . '(' . $this->linha . ')',
            '#1  {main}'
        ];
    }

    protected function montarArrayDoCodigo(string $arquivo, int $linha): string
    {
        if (empty($arquivo)) {
            return '';
        }

        $conteudo = [];
        try {
            $conteudo = explode(PHP_EOL, str_replace(array_keys($this->replace), array_values($this->replace), file_get_contents($arquivo)));
        } catch (\Throwable) {
            return '';
        }

        $quantidadeLinha = count($conteudo);
        $linhaInicial = $linha - 7;
        $linhaInicialHtml = false;
        $linhaFinal = $linha + 8;
        $linhaFinalHtml = false;

        if ($linhaInicial < 0) {
            $linhaInicial = 0;
        } elseif ($linhaInicial > 0) {
            $linhaInicialHtml = true;
        }
        if ($linhaFinal > $quantidadeLinha) {
            $linhaFinal = $quantidadeLinha;
        } elseif ($linhaFinal < $quantidadeLinha) {
            $linhaFinalHtml = true;
        }

        $html = [];
        if ($linhaInicialHtml) {
            $html[] = '<div class="linha"><div class="numero">' . ($linhaInicial - 1) . '</div><p class="conteudo_mais">[...]</p></div>';
        }
        for ($i = $linhaInicial; $i < $linhaFinal; ++$i) {
            $classe = $i == ($linha - 1) ? 'ativo' : '';
            $html[] = '<div class="linha ' . $classe . '"><div class="numero">' . ($i + 1) . '</div><p>' . $conteudo[$i] . '</p></div>';
        }
        if ($linhaFinalHtml) {
            $html[] = '<div class="linha"><div class="numero">' . ($i + 1) . '</div><p class="conteudo_mais">[...]</p></div>';
        }
        return implode(PHP_EOL, $html);
    }

    private function pegarUrlDoEditor($arquivo)
    {
        if (function_exists('ENV')) {
            $editor = env('DEV_EDITOR', '');
            $workspace = env('DEV_WORKSPACE', '');
            if (!empty($editor) && !empty($workspace)) {
                return $editor . '://' . str_replace('//', '/', 'file/' . $workspace . '/' . $arquivo);
            }
        }
        return '';
    }
}
