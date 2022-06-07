<?php

namespace Erro\Retorno;

final class ErroLegadoRetorno extends ErrorGeral
{

    use LogTrait;

    public function __construct(int $tipo, string $mensagem, string $arquivo, int $linha, $trace, $traceString)
    {
        if (SISTEMA == 'PRODUCAO') {
            $this->salvarLogErro(
                $mensagem,
                500,
                $arquivo,
                $linha,
                $trace
            );
        }

        $this->tipoErro = 'alerta';
        if (in_array($tipo, [1, 16, 64, 256, 4096])) {
            $this->tipoErro = 'fatal';
        } elseif (in_array($tipo, [8192, 16384])) {
            $this->tipoErro = 'depreciado';
        }

        $this->retorno = [];
        $this->sugestao = $this->pegarSolucaoGeral($mensagem);

        $this->pegarNomeLinhaArquivo($arquivo, $linha, $trace);
        $this->arquivoInicial = $arquivo;
        $this->linhaInicial = $linha;

        $this->root = $this->montarRoot();
        $this->mensagem = $mensagem;
        $this->trace = $this->montarTrace($this->montarTraceLegado($trace, $arquivo, $linha));
        $this->traceString = $this->montarTraceString($this->montarTraceStringLegado($traceString));

        $this->imprimirHtml();
    }

    public function retorno()
    {
        return $this->html();
    }

    private function montarTraceLegado($trace, $arquivo, $linha)
    {
        if (empty($trace)) {
            return [];
        }
        $arquivoContar = 0;
        foreach ($trace as $r) {
            if (isset($r['file'], $r['line']) && $r['file'] == $arquivo && $r['line'] == $linha) {
                $arquivoContar++;
            }
        }
        if ($arquivoContar <= 1) {
            return $trace;
        }
        $lista = [];
        foreach ($trace as $r) {
            if (isset($r['file'], $r['line'], $r['function']) && $r['file'] == $arquivo && $r['line'] == $linha && $r['function'] == 'error_handler') {
                continue;
            }
            $lista[] = $r;
        }
        return $lista;
    }

    private function montarTraceStringLegado($trace)
    {
        $trace = explode(PHP_EOL, $trace);
        if (empty($trace)) {
            return '';
        }
        $lista = [];
        $i = 0;
        foreach ($trace as $linha) {
            if (preg_match('/^\#[0-9]+\ {1,}error_handler/', $linha)) {
                continue;
            }
            if (!empty($linha)) {
                $lista[] = '#' . $i . '  ' . preg_replace('/^\#[0-9]+\ {1,}/', '', $linha);
            }
            $i++;
        }
        $lista[] = '#' . $i - 1 . '  {main}';
        return implode(PHP_EOL, $lista);
    }
}
