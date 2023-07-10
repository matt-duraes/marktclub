<?php

namespace DocumentacaoConfig;

final class Fw
{
    private array $html = [];
    private int $numero = 0;

    public function __toString()
    {
        $this->html[] = '</article>';
        return implode('', $this->html);
    }

    public function __construct(
        private string $titulo,
        private string $descricao,
        string $classe = ''
    ) {
        $this->html[] = '<article class="' . $classe . '">';
        $this->html[] = '<header>';
        $this->html[] = '<h1>' . $titulo . '</h1>';
        $this->html[] = '<p>' . $descricao . '</p>';
        $this->html[] = '</header>';
    }

    public function margin(int $margin)
    {
        $this->html[] = '<div style="margin-top: ' . $margin . 'px"></div>';
        return $this;
    }

    public function paragrafo(string $texto)
    {
        $this->html[] = '<p class="paragrafo_geral">' . $texto . '</p>';
        return $this;
    }

    public function codigo(string $codigo)
    {
        $explode = explode(PHP_EOL, $codigo);
        if (empty(trim($explode[0]))) {
            unset($explode[0]);
        }

        $quantidadeCodigo = count($explode);
        if (array_key_exists($quantidadeCodigo, $explode) && empty(trim($explode[$quantidadeCodigo]))) {
            unset($explode[$quantidadeCodigo]);
            $quantidadeCodigo--;
        }
        $numeroHtml = '<div class="codigo_linha">';
        $codigoFinal = '';
        $i = 1;
        foreach ($explode as $linha) {
            $numeroHtml .= $i . PHP_EOL;
            $codigoFinal .= htmlentities($linha) . PHP_EOL;
            $i++;
        }
        $numeroHtml .= '</div>';

        $this->html[] = '
            <pre class="codigo_geral">' . $numeroHtml . '<div class="botao_copiar">'
            . iconeCopiar(20) . '</div><code>' . $codigoFinal . '</code></pre>';
        return $this;
    }

    public function tabela(\Closure $callback)
    {
        $this->html[] = '<table class="bloco_tabela">';
        call_user_func($callback);
        $this->html[] = '</table>';
        return $this;
    }

    public function tr(array $lista)
    {
        $this->html[] = '<tr>';
        foreach ($lista as $valor) {
            if (validarUrl($valor)) {
                $this->html[] = '<td><a class="botao_abrir" href="' . $valor . '" target="_blank">Abrir</a></td>';
                continue;
            }
            $this->html[] = '<td>' . htmlentities($valor) . '</td>';
        }
        $this->html[] = '</tr>';
        return $this;
    }

    public function trTitulo(array $lista)
    {
        $this->html[] = '<tr class="tr_titulo">';
        foreach ($lista as $valor) {
            $this->html[] = '<td>' . $valor . '</td>';
        }
        $this->html[] = '</tr>';
        return $this;
    }

    public function exemploPhp(string $texto = '', string $funcao = '', array $parametro = [])
    {
        $resultado = call_user_func_array($funcao, $parametro);
        if (is_null($resultado)) {
            $resultado = 'null';
        } elseif (true === $resultado) {
            $resultado = 'true';
        } elseif (false === $resultado) {
            $resultado = 'false';
        }
        $this->html[] = '
            <div class="item">
                <pre class="texto">' . htmlentities('<?php') . PHP_EOL . htmlentities($texto) . '</pre>
                <div class="resultado"><span>Resultado: </span>' . $resultado . '</div>
            </div>
        ';
        return $this;
    }

    public function exemploBotao(string $descricao = '', string $codigo = '', bool $promise = false)
    {
        $id = 'id_botao_' . $this->numero;
        $this->numero++;

        $async = $promise ? 'async ' : '';

        $this->html[] = '
            <div class="item">
                <div class="descricao">' . $descricao . '</div>
                <pre class="texto">' . $codigo . '</pre>
                <div class="botao" id="' . $id . '">Testar</div>
            </div>
            <script>
                document.querySelector("#' . $id . '").addEventListener("click", ' . $async . '() => {
                    ' . $codigo . '
                });
            </script>
        ';
        return $this;
    }

    public function systemCssJs(string|array $plugin)
    {
        if (is_array($plugin)) {
            $ultimo = array_pop($plugin);
            $nome = implode(', ', $plugin) . ' e ' . $ultimo;
            $css = '@system "' . implode('"' . PHP_EOL . '@system "', $plugin) . '"' .
                PHP_EOL . '@system "' . $ultimo . '"' . PHP_EOL;
            $js = '// @system "' . implode('"' . PHP_EOL . '// @system "', $plugin) . '"' .
                PHP_EOL . '// @system "' . $ultimo . '"' . PHP_EOL;
            $doPlugin = 'dos plugins';
            $oScript = 'os scripts';
        } else {
            $nome = $plugin;
            $css = '@system "' . $plugin . '"';
            $js = '// @system "' . $plugin . '"';
            $doPlugin = 'do plugin';
            $oScript = 'o script';
        }

        $this
            ->paragrafo('Para usar os dados abaixo, você precisa incorporar os scripts CSS e JS '
                . $doPlugin . ' ' . $nome . '.')
            ->paragrafo('Para adicionar ' . $oScript . ' CSS basta importar no seu arquivo styl assim:')
            ->codigo($css, 'body' . PHP_EOL . '// ... Código CSS</span>')
            ->paragrafo('Para incorporar ' . $oScript . ' script JS basta importar no seu arquivo assim:')
            ->codigo($js, 'window.addEventListener("load", () => {' . PHP_EOL . '// ... Código JS');
        return $this;
    }

    public function bloco(\Closure $callback)
    {
        $this->html[] = '<div class="bloco_codigo">';
        call_user_func($callback);
        $this->html[] = '</div>';
        return $this;
    }

    public function blocoParametro(\Closure $callback)
    {
        $this->html[] = '<div class="parametro">';
        $this->html[] = '<div class="subtitulo">Parametros:</div>';
        $this->html[] = '<div class="scroll">';
        $this->html[] = '<table>';
        call_user_func($callback);
        $this->html[] = '</table>';
        $this->html[] = '</div>';
        $this->html[] = '</div>';
        return $this;
    }

    public function blocoExemplo(\Closure $callback, string $tipo = 'php')
    {
        $this->html[] = '<div class="exemplo_geral exemplo_' . $tipo . '">';
        $this->html[] = '<div class="subtitulo">Exemplo:</div>';
        call_user_func($callback);
        $this->html[] = '</div>';
        return $this;
    }

    public function titulo(string $titulo)
    {
        $this->html[] = '<div class="titulo">' . $titulo . '</div>';
        return $this;
    }

    public function subtitulo(string $subtitulo)
    {
        $this->html[] = '<div class="subtitulo">' . $subtitulo . '</div>';
        return $this;
    }

    public function link(string $texto, string $link, bool $self = true)
    {
        $target = $self ? '_self' : '_blank';
        $this->html[] = '<a class="link" href="' . $link . '" ' . $target . '>' . $texto . '</a>';
        return $this;
    }

    public function descricao(string $descricao)
    {
        $this->html[] = '<div class="descricao">' . $descricao . '</div>';
        return $this;
    }

    public function retorno($tipo, $descricao)
    {
        $this->html[] = '
            <div class="bloco_retorno">
                <div class="subtitulo">Retorno:</div>
                <div class="tipo">'
                    . implode('</div><span class="barra">|</span><div class="tipo">', explode('|', $tipo)) . '</div>
                <div class="descricao">' . $descricao . '</div>
            </div>
        ';
        return $this;
    }

    public function throw($tipo, $descricao)
    {
        $this->html[] = '
            <div class="bloco_retorno bloco_erro">
                <div class="subtitulo">Erros:</div>
                <div class="tipo">' .
                    implode('</div><span class="barra">|</span><div class="tipo">', explode('|', $tipo)) . '</div>
                <div class="descricao">' . $descricao . '</div>
            </div>
        ';
        return $this;
    }

    public function parametro($tipo, $campo, $descricao)
    {
        $tipoHtml = '';
        if (!empty($tipo)) {
            $tipo = explode('|', str_replace(' ', '', $tipo));
            $tipoHtml = '<td class="tipo">(<span class="rosa">'
                . implode('</span><span class="barra">|</span><span class="rosa">', $tipo) . ')</span></td>';
        }
        $this->html[] = '<tr>' . $tipoHtml . '<td class="var">'
            . $campo . '</td><td class="descricao">' . $descricao . '</td></tr>';
        return $this;
    }

    public function funcao(string $arquivo)
    {
        $class = arquivoNome($arquivo);
        $arquivo = explode(PHP_EOL, file_get_contents($arquivo));
        $funcaoAberta = false;
        $codigoContinuar = false;

        $tituloHtml = '';
        $descricaoHtml = '';
        $exemploHtml = [];
        $parametroHtml = [];
        $codigoPropriedadeHtml = [];
        $codigoHtml = '';
        $retornoHtml = '';
        $throwsHtml = '';

        $lista = [];
        $doc = false;
        $exemplo = false;

        foreach ($arquivo as $linha) {
            $linha = trim($linha);
            $limpo = str_replace(' ', '', $linha);

            if (empty($linha) || $limpo == '*/') {
                continue;
            } elseif ($limpo == '/**') {
                $exemplo = false;
                continue;
            } elseif ($limpo == '//doc') {
                $doc = true;
                continue;
            } elseif ($limpo == '//exemplo') {
                $exemplo = true;
                continue;
            }

            if (!$doc) {
                continue;
            } elseif ($exemplo && !str_contains($linha, 'function')) {
                $linha = explode(' ', preg_replace('/\/\/\ ?/', '', $linha));
                $funcaoParametro = array_key_exists(2, $linha) && !empty($linha[2]) ?
                    str_replace('_', ' ', $linha[2]) : '';
                $funcaoParametroTexto = '';
                $funcaoParametroValor = [];
                if (!empty($funcaoParametro)) {
                    $funcaoParametroTexto = explode(',', $funcaoParametro);
                    $funcaoParametro = str_replace(["'", '"'], '', $funcaoParametro);
                    $i = 0;
                    foreach (explode(',', $funcaoParametro) as $val) {
                        $ind = $i;
                        $i++;
                        if (preg_match('/^[a-zA-Z0-9\_]+\:/', $val)) {
                            $ind = explode(':', $val)[0];
                            $val = explode(':', $val)[1];
                        }
                        if ($val == 'null') {
                            $funcaoParametroValor[$ind] = null;
                            continue;
                        } elseif ($val == 'true') {
                            $funcaoParametroValor[$ind] = true;
                            continue;
                        } elseif ($val == 'false') {
                            $funcaoParametroValor[$ind] = false;
                            continue;
                        } elseif (str_contains($val, '|')) {
                            $val = explode('|', $val);
                        }
                        $funcaoParametroValor[$ind] = is_string($val) && preg_match('/^[1-9]{1}[0-9]*$/', $val)
                            ? (int) $val : $val;
                    }
                }
                $exemploHtml[] = [$linha[0], $linha[1] ?? '', $funcaoParametroTexto, $funcaoParametroValor];
                continue;
            }

            if (preg_match('/^(public\ )?function\ [a-zA-Z0-9\_]+\($/', $linha)) {
                $linha = preg_replace('/^(public\ )?function\ /', '', $linha);
                $funcao = $linha == '__construct(' && !empty($class) ?
                    str_replace('__construct', 'new ' . $class, $linha) : $linha;
                $codigoHtml = '<pre class="codigo_geral"><div class="codigo_linha">1</div><div class="botao_copiar">'
                    . iconeCopiar(20) . '</div><code>' . $funcao;
                $tituloHtml = explode('(', $linha)[0] ?? '';
                $codigoContinuar = true;
            } elseif ($codigoContinuar && !str_contains($linha, ')')) {
                $codigoPropriedadeHtml[] = trim(str_replace(['private ', 'protected ', 'public ', ','], '', $linha));
            } elseif ($codigoContinuar && str_contains($linha, ')')) {
                if (str_contains($linha, '$')) {
                    $codigoPropriedadeHtml[] = trim(str_replace([')', ','], '', $linha));
                    $linha = preg_replace('/^\( |,)*\$[a-zA-Z0-9\_]+(\ |,)*/', '', $linha);
                }
                $codigoHtml .= implode(', ', $codigoPropriedadeHtml) . str_replace('{', '', $linha) . '</code></pre>';
                $codigoContinuar = false;
            } elseif (preg_match('/^(public\ )?function\ /', $linha)) {
                $linha = preg_replace('/^(public\ )?function\ /', '', $linha);
                $tituloHtml = explode('(', $linha)[0] ?? '';
                $funcao = $linha == '__construct' && !empty($class)
                    ? str_replace('__construct', $class, $linha) : $linha;
                $codigoHtml = '<pre class="codigo_geral"><div class="codigo_linha">1</div><div class="botao_copiar">'
                    . iconeCopiar(20) . '</div><code>'
                    . str_replace(['private ', 'protected ', 'public '], '', $funcao) . '</code></pre>';
            } elseif (str_starts_with($limpo, '*@return')) {
                $retorno = preg_replace('/^\*\ ?\@return\ */', '', $linha);
                preg_match('/^[a-zA-Z0-9\\\|\{\}\,]+/', $retorno, $tipo);
                $tipo = explode('|', $tipo[0] ?? '');
                $descricao = preg_replace('/^[a-zA-Z0-9\\\|\{\}\,]+\ ?/', '', $retorno);
                if (empty($tipo) && empty($descricao)) {
                    continue;
                }
                $retornoHtml = '<div class="bloco_retorno"><div class="subtitulo">Retorno:</div>';
                $retornoHtml .= '<div class="tipo">' .
                    implode('</div><span class="barra">|</span><div class="tipo">', $tipo) . '</div>';
                $retornoHtml .= '<div class="descricao">' . $descricao . '</div>';
                $retornoHtml .= '</div>';
            } elseif (str_starts_with($limpo, '*@throws')) {
                $throws = preg_replace('/^\*\ ?\@throws\ */', '', $linha);
                preg_match('/^[a-zA-Z0-9\\\|]+/', $throws, $tipo);
                $tipo = explode('|', $tipo[0] ?? '');
                $descricao = preg_replace('/^[a-zA-Z0-9\\\|]+\ ?/', '', $throws);
                if (empty($tipo) && empty($descricao)) {
                    continue;
                }
                $throwsHtml = '<div class="bloco_retorno bloco_erro"><div class="subtitulo">Erros:</div>';
                $throwsHtml .= '<div class="tipo">'
                    . implode('</div><span class="barra">|</span><div class="tipo">', $tipo) . '</div>';
                $throwsHtml .= '<div class="descricao">' . $descricao . '</div>';
                $throwsHtml .= '</div>';
            } elseif (preg_match('/\*\ ?\@/', $linha)) {
                $parametro = preg_replace('/^\*\ +\@param\ +/', '', $linha);
                preg_match('/^[a-zA-Z0-9\|]+/', $parametro, $tipo);
                $tipo = !empty($tipo[0] ?? '') ? '<span class="rosa">'
                    . implode(
                        '</span><span class="barra">|</span><span class="rosa">',
                        explode('|', $tipo[0])
                    ) . '</span>' : '';
                preg_match('/\$[a-zA-Z0-9\_]+/', $parametro, $var);
                $var = !empty($var[0] ?? '') ? $var[0] : '';
                $descricao = preg_replace('/^[a-zA-Z0-9\|]+\ {1,}\$[a-zA-Z0-9\_]+\ {1,}/', '', $parametro);
                $descricao = !empty($descricao) ? $descricao : '';
                $parametroHtml[] = '<tr><td class="tipo">(' . $tipo . ')</td><td class="var">'
                    . $var . '</td><td class="descricao">' . $descricao . '</td></tr>';
            } elseif (preg_match('/\*\ [a-zA-Z0-9]+?/', $linha)) {
                $funcaoAberta = true;
                $descricaoHtml = preg_replace('/^\*\ ?/', '', $linha);
            } elseif ($limpo == '}' && $funcaoAberta) {
                $parametroFinal = '';
                if (!empty($parametroHtml)) {
                    $parametroFinal = '
                        <div class="parametro">
                            <div class="subtitulo">Parametros:</div>
                            <div class="scroll">
                                <table>
                                    ' . implode('', $parametroHtml) . '
                                </table>
                            </div>
                        </div>
                    ';
                }
                $exemploFinal = '';
                if ($exemploHtml) {
                    $exemploFinal = '<div class="exemplo_geral exemplo_php">';
                    $exemploFinal .= '<div class="subtitulo">Exemplo:</div>';
                    foreach ($exemploHtml as $funcao) {
                        if (empty($funcao[1])) {
                            continue;
                        }

                        $parametroConvertidoString = $this->converterValorParaParametroString($funcao[2]);
                        $parametroConvertidoValor = $funcao[3];

                        $texto = $funcao[0] . ' ' . $funcao[1];
                        $texto .= !empty($parametroConvertidoString) ? '(' . $parametroConvertidoString . ');' : '();';
                        $exemploFinal .= '<div class="item">';
                        $exemploFinal .= '<pre class="texto">&lt;?php' . PHP_EOL . $texto . '</pre>';
                        if ($funcao[0] == 'echo') {
                            try {
                                $resultado = call_user_func_array($funcao[1], $parametroConvertidoValor);
                            } catch (\Throwable) {
                            }
                            if (is_null($resultado)) {
                                $resultado = 'null';
                            } elseif (true === $resultado) {
                                $resultado = 'true';
                            } elseif (false === $resultado) {
                                $resultado = 'false';
                            }
                            $exemploFinal .= '<div class="resultado"><span>Resultado: </span> ' . $resultado . '</div>';
                        }
                        $exemploFinal .= '</div>';
                    }
                    $exemploFinal .= '</div>';
                }
                $lista[] = '
                    <div class="bloco_codigo">
                        <div class="titulo">' . $tituloHtml . '</div>
                        <div class="descricao">' . $descricaoHtml . '</div>
                        ' . $parametroFinal . '
                        ' . $codigoHtml . '
                        ' . $retornoHtml . '
                        ' . $throwsHtml . '
                        ' . $exemploFinal . '
                    </div>
                ';
                $tituloHtml = '';
                $descricaoHtml = '';
                $parametroHtml = [];
                $exemploHtml = [];
                $codigoPropriedadeHtml = [];
                $retornoHtml = '';
                $throwsHtml = '';
                $doc = false;
            }
        }
        $this->html[] = implode('', $lista);
        return $this;
    }

    private function converterValorParaParametroString($parametro)
    {
        if (empty($parametro)) {
            return '';
        }

        $retorno = [];
        foreach ($parametro as $valor) {
            $name = '';
            if (preg_match('/^[a-zA-Z0-9\_]+\:/', $valor)) {
                $name = explode(':', $valor)[0] . ': ';
                $valor = trim(preg_replace('/^[a-zA-Z0-9\_]+\:/', '', $valor));
            }

            if (str_contains($valor, '|')) {
                $retorno[] = $name . '["'
                    . implode('", "', explode('|', str_replace(['_', '=>'], [' ', '" => "'], $valor))) . '"]';
                continue;
            } elseif (empty($valor)) {
                $retorno[] = $name . '""';
                continue;
            } elseif (preg_match('/^[1-9]{1}[0-9]*$/', $valor) || in_array($valor, ['null', 'true', 'false'])) {
                $retorno[] = $name . $valor;
                continue;
            }
            $retorno[] = $name . '"' . $valor . '"';
        }
        return implode(', ', $retorno);
    }

    private function converterValorParaParametroValor($parametro)
    {
        if (empty($parametro)) {
            return [];
        }

        $retorno = [];
        $i = 0;
        foreach ($parametro as $valor) {
            $name = $i;
            if (preg_match('/^[a-zA-Z0-9\_]+\:/', $valor)) {
                $name = explode(':', $valor)[0];
                $valor = preg_replace('/^[a-zA-Z0-9\_]+\:/', '', $valor);
            }
            if (str_contains($valor, '|')) {
                $explode = explode('|', str_replace('_', '', $valor));
                $valorTemp = [];
                $i = 0;
                foreach ($explode as $val) {
                    $ind = $i;
                    if (str_contains($val, '=>')) {
                        $explodeValor = explode('=>', $val);
                        $ind = $explodeValor[0];
                        $val = $explodeValor[1] ?? '';
                    }
                    $valorTemp[$ind] = $val;
                }
                $valor = $valorTemp;
            }
            if ($valor == 'null') {
                $valor = null;
            } elseif ($valor == 'true') {
                $valor = true;
            } elseif ($valor == 'false') {
                $valor = false;
            } elseif (is_string($valor) && preg_match('/^[1-9]{1}[0-9]*$/', $valor)) {
                $valor = (int)$valor;
            }
            $retorno[$name] = $valor;
            $i++;
        }

        return $retorno;
    }
}
