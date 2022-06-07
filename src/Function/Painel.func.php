<?php

/*
|--------------------------------------------------------------------------
| APP DE LISTA GERAL
|--------------------------------------------------------------------------
*/
if (!function_exists('painelAppLista')) {
    function painelAppLista(string $app, stdClass $dado, array $filtro, stdClass $busca, stdClass $config)
    {
        require ROOT . '/src/Html/Painel/appLista.php';
    }
}
if (!function_exists('painelCor')) {
    /**
     * Retornar uma cor pelo nome
     *
     * @param string     $cor       Cores padrões: verde, vermelho, azul, laranja, preto, branco
     *                              cinza, rosa, roxo, amarelo, marrom
     * @return string
     */
    function painelCor(string $cor)
    {
        return [
            'verde' => '#169e91', 'vermelho' => '#FF6C60', 'azul' => '#63b4e4', 'laranja' => '#ff6600',
            'preto' => '#333', 'branco' => '#FFF', 'cinza' => '#CCC', 'rosa' => '#ff1981', 'roxo' => '#68217a',
            'amarelo' => '#FFCC00', 'marrom' => '#8B4513'
        ][$cor] ?? '';
    }
}
/*
|--------------------------------------------------------------------------
| APP DE FILTRO
|--------------------------------------------------------------------------
*/
if (!function_exists('painelAppFiltro')) {
    /**
     * @param null|string       $app        App que deseja usar
     * @param null|string       $ordem      Ordem que está usando no momento
     * @param Null|StdClass     $config     Configuração do app filtro
     */
    function painelAppFiltro(?string $app = null, ?string $ordem = null, ?stdClass $config = null)
    {
        if (!is_null($config)) {
            $html = $config->input;
            require ROOT . '/src/Html/Painel/appFiltro.php';
            return;
        }
        $inputOrdem = '';
        if (!empty($ordem)) {
            $inputOrdem = '<input type="hidden" name="ordem" value="' . $ordem . '">';
        }
        echo '
            <form action="' . LINK . '/app/filtrar/' . $app . '" method="post" id="bloco_app_filtrar" class="form_geral bloco_pagina_popup">
                <header class="header_pagina_popup">
                    <i class="mobile botao_fechar_filtro">' . iconeVoltar() . '</i>
                    <h1>FILTRAR DADOS</h1>
                    <i class="desktop botao_fechar_filtro">' . iconeFechar() . '</i>
                </header>
                <div class="conteudo_pagina_popup">
                    ' . $inputOrdem . '
        ';
    }
}
if (!function_exists('painelAppFiltroEnd')) {
    function painelAppFiltroEnd()
    {
        echo '
                </div>
                <footer class="footer_pagina_popup">
                    <div class="flex"></div>
                    <button type="submit" class="botao_filtrar_geral button botao_loading_geral">
                        <p>BUSCAR</p>
                        <span>' . iconeLoadingBola() . '</span>
                    </button>
                </footer>
            </form>
        ';
    }
}
/*
|--------------------------------------------------------------------------
| APP DE DOWNLOAD
|--------------------------------------------------------------------------
*/
if (!function_exists('painelAppDownload')) {
    /**
     * @param null|string       $app        App que deseja usar
     * @param null|string       $ordem      Ordem que está usando no momento
     * @param Null|StdClass     $config     Configuração do app download
     */
    function painelAppDownload(
        ?string $app = null,
        ?stdClass $config = null,
        string $pesquisa = '',
        string $filtro = '',
        string $ordem = ''
    ) {
        if (!is_null($config)) {
            $html = $config->download->html;
            require ROOT . '/src/Html/Painel/appDownload.php';
            return;
        }

        echo '
            <form action="' . LINK . '/app/download/' . $app . '" target="_blank" method="post" id="bloco_app_download" class="form_geral bloco_pagina_popup">
                <header class="header_pagina_popup">
                    <i class="mobile botao_fechar_download">' . iconeVoltar() . '</i>
                    <h1>DOWNLOAD</h1>
                    <i class="desktop botao_fechar_download">' . iconeFechar() . '</i>
                </header>
                <div class="conteudo_pagina_popup">
        ';
    }
}
if (!function_exists('painelAppDownloadEnd')) {
    function painelAppDownloadEnd()
    {
        echo '
                    <div class="footer" id="bloco_download_footer">
                        ' . formCheckbox(name: 'termo', value: 'sim', label: 'Confirmar que sou ' . sessao('USUARIO.nome') . ' e que tenho permissão para fazer esse download.', check: false, class: 'botao_termo_download') . '
                        ' . formSenha(name: 'senha', label: 'Senha', placeholder: 'Digite sua senha') . '
                        <button type="submit" class="botao_download_geral button botao_loading_geral">
                            <p>DOWNLOAD</p>
                            <span>' . iconeLoadingBola() . '</span>
                        </button>
                    </div>
                </div>
            </form>
        ';
    }
}
/*
|--------------------------------------------------------------------------
| POPUP
|--------------------------------------------------------------------------
*/
if (!function_exists('painelPopup()')) {
    /**
     * @param string        $titulo     Título para o Popup
     * @param null|string   $action     Link para o formulário caso queira usar o popup como Form
     * @param null|string   $method     GET ou POST para o método do formulário caso queira usar o popup como Form
     * @param null|string   $id         ID para o bloco
     * @param bool          $fechar     Se vai ter o botão de fechar no header
     */
    function painelPopup(string $titulo, ?string $action = null, ?string $method = null, ?string $id = null, bool $fechar = true)
    {
        $form = !empty($action) || !empty($method);
        $action = $form && empty($action) ? '/' : $action;
        $method = $form && !in_array($method, ['POST', 'GET']) ? 'POST' : $method;
        require ROOT . '/src/Html/Painel/popup.php';
    }
}
if (!function_exists('painelPopupEnd')) {
    /**
     * @param null|string   $botao      Texto para o botão
     * @param null|string   $id         ID para o botão
     * @param bool          $form       Se o popup é um form ou não
     * @param string        $html       Coloca um html do lado do botao
     */
    function painelPopupEnd(?string $botao = null, ?string $id = null, bool $form = false, string $html = '')
    {
        require ROOT . '/src/Html/Painel/popupEnd.php';
    }
}

/*
|--------------------------------------------------------------------------
| APP DE VISUALIZAR
|--------------------------------------------------------------------------
*/
if (!function_exists('painelAppVisualizar')) {
    function painelAppVisualizar(?stdClass $config = null, ?stdClass $r = null, ?string $app = null)
    {
        if (is_object($config) && isset($config->visualizar)) {
            require ROOT . '/src/Html/Painel/appVisualizar.php';
            if ($config->permissao->historico) {
                require ROOT . '/src/Html/Painel/appHistorico.php';
            }
            return;
        } elseif (is_null($config)) {
            echo '<div id="bloco_app_visualizar" class="bloco_app_estrutura_visualizar">';
            return;
        }
    }
}
if (!function_exists('painelAppVisualizarEnd')) {
    function painelAppVisualizarEnd()
    {
        echo '</div>';
    }
}
if (!function_exists('painelLinhaLista')) {
    function painelLinhaLista($lista, $dado, $replace)
    {
        $botaoStatus = '';
        foreach ($lista as $item) {
            $acao = $item['funcao'];
            if ($acao == 'include') {
                require_once $item['arquivo'];
                continue;
            } else if ($acao == 'html') {
                echo $item['html'];
                continue;
            }

            $campo = $item['campo'] ?? '';
            $campo = !is_array($campo) && !in_array($acao, ['checked', 'botao', 'status', 'vazio_break']) ? [$campo] : $campo;

            if ($acao == 'vazio_break' && object_key_exists($campo, $dado) && !empty(painelValor($dado, $campo))) {
                continue;
            } else if ($acao == 'vazio_break') {
                echo '<div class="zero">' . $item['titulo'] . '</div>';
                break;
            }

            $nome = array_key_exists('nome', $item) ? $item['nome'] : '';
            $texto = $item['texto'] ?? '';
            $id = $item['id'] ?? '';
            $link = $item['link'] ?? '';
            $status = $item['status'] ?? '';
            $mensagem = $item['mensagem'] ?? '';
            $inArray = $item['inArray'] ?? '';
            $cor = $item['cor'] ?? '';
            $formatar = $item['formatar'] ?? '';

            $valor = [];
            if (is_array($campo) && $campo) {
                $apenasUm = false;
                foreach ($campo as $val) {
                    if (str_starts_with($val, '!')) {
                        $val = preg_replace('/^\!/', '', $val);
                        $apenasUm = true;
                    }
                    $campoInicial = explode('->', $val)[0];
                    if (!object_key_exists($campoInicial, $dado)) {
                        mensagemStatus(500, 'Não foi encontrado o indice ' . $campoInicial);
                    }
                    $valorTemporario = painelValor($dado, $val);
                    if (!empty($valorTemporario)) {
                        if (
                            (is_string($valorTemporario) || is_numeric($valorTemporario)) &&
                            array_key_exists($val, $replace) &&
                            array_key_exists($valorTemporario, $replace[$val])
                        ) {
                            $valorTemporario = $replace[$val][$valorTemporario] ?? $valorTemporario;
                        }
                        if ($acao == 'contar') {
                            $valor = array_merge($valor, $valorTemporario);
                            continue;
                        }
                        $valor[] = $valorTemporario;
                    }
                    if ($valor && $apenasUm) {
                        break;
                    }
                }
            } else if (!empty($campo)) {
                $campoInicial = explode('->', $campo)[0];
                if (!object_key_exists($campoInicial, $dado)) {
                    mensagemStatus(500, 'Não foi encontrado o indice ' . $campoInicial);
                }
                $valor = painelValor($dado, $campo);
                if (array_key_exists($campo, $replace) && is_string($valor)) {
                    $valor = $replace[$campo][$valor] ?? $valor;
                }
            }

            $valor = !in_array($acao, ['checked', 'botao', 'contar']) && is_array($valor) ? implode(' ou ', $valor) : $valor;
            if ($acao == 'contar' && is_array($valor)) {
                $acao = 'linha';
                $valor = count($valor);
            }

            if (!empty($formatar)) {
                $valor = painelValorFormatar($valor, '', $formatar);
            }

            if ($acao == 'imagem_redonda') {
                echo '<figure class="imagem_redonda" style="background-image: url(' . $valor . ')"></figure>';
            } else if ($acao == 'linha') {
                $valor = !empty($valor) ? $valor : '<span class="vazio">Dado não informado</span>';
                $nome = preg_match('/\:|\!|\?$/', $nome) ? $nome : $nome . ':';
                echo '<div class="linha bg_hover"><strong class="texto_nome">' . $nome . '</strong> ' . $valor . '</div>';
            } else if ($acao == 'titulo') {
                echo '<h2 class="titulo">' . $valor . '</h2>';
            } else if ($acao == 'sub_titulo') {
                echo '<p class="sub_titulo">' . $valor . '</p>';
            } else if ($acao == 'checked' && is_array($valor)) {
                echo '<div class="bloco_checked">';
                foreach ($valor as $ind) {
                    echo '<div class="item"><span class="texto_nome">' . $ind . '</span> <i>' . iconeCheck() . '</i></div>';
                }
                echo '</div>';
            } else if ($acao == 'checked' && is_bool($valor)) {
                $icone = $valor ? iconeCheck(10) : iconeFechar(8);
                $classe = $valor ? 'checked_sim' : 'checked_nao';
                echo '<div class="checked bg_hover"><span class="texto_nome">' . $nome . '</span> <i class="' . $classe . '">' . $icone . '</i></div>';
            } else if ($acao == 'botao' && !empty($link)) {
                $id = !empty($id) ? 'id="' . $id . '"' : '';
                echo '<a class="botao_link" ' . $id . ' href="' . painelConverterLink($link, $dado) . '">' . $texto . '</a>';
            } else if ($acao == 'botao') {
                $id = !empty($id) ? 'id="' . $id . '"' : '';
                echo '<div class="botao_link" ' . $id . '>' . $texto . '</div>';
            } else if ($acao == 'status' && is_string($valor) && !empty($valor) && is_array($inArray) && $inArray && in_array($valor, $inArray)) {
                $id = !empty($id) ? 'id="' . $id . '"' : '';
                $cor = !empty($cor) ? $cor : '';
                $mensagem = !empty($mensagem) ? 'data-mensagem="' . $mensagem . '"' : '';
                $status = !empty($status) ? 'data-status="' . $status . '"' : '';
                $botaoStatus .= '<div class="botao_status ' . $cor . '" ' . $id . ' ' . $mensagem . ' ' . $status . '>' . $texto . '</div>';
            }
        }
        if ($botaoStatus) {
            echo '<div class="bloco_botao_status"><div class="bloco_status_lista">' . $botaoStatus . '</div></div>';
        }
    }
}
if (!function_exists('painelConverterLink')) {
    function painelConverterLink($link, $dado)
    {
        if (!str_contains($link, '->')) {
            return $link;
        }

        $explode = explode('/', $link);
        $linkFinal = [];
        foreach ($explode as $item) {
            if (preg_match('/\-\>/', $item)) {
                $item = painelValor($dado, $item);
            }
            $linkFinal[] = $item;
        }
        return implode('/', $linkFinal);
    }
}
/*
|--------------------------------------------------------------------------
| APP DE ADD
|--------------------------------------------------------------------------
*/
if (!function_exists('painelAppAdd')) {
    function painelAppAdd(?stdClass $config = null, ?stdClass $r = null, ?string $app = null)
    {
        if (is_object($config) && isset($config->add)) {
            $html = $config->add->html;
            $linkVoltar = !empty($config->index->link) ? $config->index->link : LINK . '/app' . $app;
            require ROOT . '/src/Html/Painel/appAdd.php';
            return;
        } elseif (is_null($config)) {
            echo '<form id="bloco_app_add" action="/" class="form_geral bloco_app_estrutura">';
            return;
        }
    }
}
if (!function_exists('painelAppAddEnd')) {
    /**
     * Finaliza o bloco do APP ADD
     *
     * @param null|string   $id     ID do botão para salvar
     * @param null|string   $botao  Nome do botão para salvar
     */
    function painelAppAddEnd(?string $id = null, ?string $botao = null)
    {
        painelAppAddBotao($id, $botao);
        echo '</form>';
    }
}
if (!function_exists('painelAppAddBotao')) {
    /**
     * Finaliza o bloco do APP ADD
     *
     * @param null|string   $id     ID do botão para salvar
     * @param null|string   $botao  Nome do botão para salvar
     */
    function painelAppAddBotao(?string $id = null, ?string $botao = null)
    {
        $id = $id != null ? $id : 'botao_salvar_geral';
        $botao = $botao != null ? $botao : 'SALVAR';
        echo '
                <div class="botao_salvar" id="' . $id . '">
                    <p>' . $botao . '</p>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="34" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><g><g><circle cx="20" cy="3.6" r="3.6"/><circle cx="20" cy="36.4" r="3.6"/></g><g><circle cx="8.4" cy="8.4" r="3.6"/><circle cx="31.6" cy="31.6" r="3.6"/></g><g><circle cx="3.6" cy="20" r="3.6"/><circle cx="36.4" cy="20" r="3.6"/></g><g><circle cx="8.4" cy="31.6" r="3.6"/><circle cx="31.6" cy="8.4" r="3.6"/></g></g></svg>
                    </span>
                </div>
            </form>
        ';
    }
}

if (!function_exists('painelBotao')) {
    /**
     * Finaliza o bloco do APP ADD
     *
     * @param null|string   $id     ID do botão para salvar
     * @param null|string   $botao  Nome do botão para salvar
     */
    function painelBotao(?string $id = null, ?string $botao = null)
    {
        $id = $id != null ? $id : 'botao_salvar_geral';
        $botao = $botao != null ? $botao : 'SALVAR';
        echo '
                <div class="painel_botao_salvar" id="' . $id . '">
                    <p>' . $botao . '</p>
                    <span>
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="34" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><g><g><circle cx="20" cy="3.6" r="3.6"/><circle cx="20" cy="36.4" r="3.6"/></g><g><circle cx="8.4" cy="8.4" r="3.6"/><circle cx="31.6" cy="31.6" r="3.6"/></g><g><circle cx="3.6" cy="20" r="3.6"/><circle cx="36.4" cy="20" r="3.6"/></g><g><circle cx="8.4" cy="31.6" r="3.6"/><circle cx="31.6" cy="8.4" r="3.6"/></g></g></svg>
                    </span>
                </div>
            </form>
        ';
    }
}

if (!function_exists('painelColuna')) {
    function painelColuna($prefix = 1)
    {
        echo '<div class="bloco_coluna_geral bloco_coluna_' . $prefix . '">';
    }
}
if (!function_exists('painelColunaEnd')) {
    function painelColunaEnd()
    {
        echo '</div>';
    }
}
if (!function_exists('painelFieldset')) {
    function painelFieldset(?string $titulo = null)
    {
        $tituloHtml = !empty($titulo) ? '<h2>' . $titulo . '</h2>' : '';
        echo '
            <div class="bloco_fieldset">
                ' . $tituloHtml . '
                <div class="lista_dado">
        ';
    }
}
if (!function_exists('painelFieldsetEnd')) {
    function painelFieldsetEnd()
    {
        echo '
                </div>
            </div>
        ';
    }
}
/*
|--------------------------------------------------------------------------
| DIV FECHADA
|--------------------------------------------------------------------------
*/
if (!function_exists('painelDiv')) {
    function painelDiv()
    {
        echo '</div>';
    }
}
/*
|--------------------------------------------------------------------------
| VALOR
|--------------------------------------------------------------------------
*/
if (!function_exists('painelValor')) {
    function painelValor(stdClass $lista, string $campo, string $vazio = '', string $formatar = '')
    {
        if (vazio($lista)) {
            return '';
        }
        if (!str_starts_with($campo, '->')) {
            $campo = '->' . $campo;
        }

        $explode = explode('->', $campo);
        $inicial = $explode[1];
        unset($explode[0], $explode[1]);
        $valor = $lista->$inicial;

        if (empty($explode)) {
            return painelValorFormatar(valor: $valor, vazio: $vazio, formatar: $formatar);
        }

        foreach ($explode as $val) {
            if (
                (is_array($valor) && !array_key_exists($val, $valor)) ||
                (is_object($valor) && !object_key_exists($val, $valor))
            ) {
                mensagemStatus(500, 'O campo "' . $campo . '" não existe na lista.');
            }
            $valor = is_array($valor) ? $valor[$val] : $valor->$val;
        }
        return painelValorFormatar(valor: $valor, vazio: $vazio, formatar: $formatar);
    }
}
if (!function_exists('painelValorFormatar')) {
    function painelValorFormatar($valor, string $vazio = '', string $formatar = '')
    {
        if (empty($valor)) {
            return $vazio;
        } else if (
            (!is_string($valor) && !is_numeric($valor)) || empty($formatar)
        ) {
            return $valor;
        } elseif ($formatar == 'telefone') {
            $valor = strTelefone($valor);
        } else if ($formatar == 'cep') {
            $valor = strCep($valor);
        } else if ($formatar == 'cpf') {
            $valor = strCpf($valor);
        } else if ($formatar == 'cnpj') {
            $valor = strCnpj($valor);
        } else if ($formatar == 'data') {
            $valor = dataBr($valor);
        } else if ($formatar == 'email') {
            $valor = strEmail($valor);
        } else if ($formatar == 'datahora') {
            $valor = dataHoraBr($valor);
        }
        return $valor;
    }
}
/*
|--------------------------------------------------------------------------
| CONVERTE SELECT PARA PADRÃO DO CONFIG
|--------------------------------------------------------------------------
*/
if (!function_exists('painelSelectConfig')) {
    /**
     * Converte um array id => valor para o padrão de select do painel
     *
     * @param array $lista Array com a lista
     * @return string
     */
    function painelSelectConfig(array $lista): string
    {
        // 1=Nome 01|2=Nome 02|3=Nome 03
        $retorno = [];
        foreach ($lista as $id => $valor) {
            $retorno[] = $id . '=' . preg_replace('/[^a-zA-Zà-úÀ-Ú0-9\ \/\|\-\_\.\,]/', '', $valor);
        }
        return implode('|', $retorno);
    }
}
if (!function_exists('painelConfigSelect')) {
    /**
     * Converter uma string de select para array
     *
     * @param array $lista Array com a lista
     * @return array
     */
    function painelConfigSelect(string $valor): array
    {
        $select = [];
        foreach (explode('|', $valor) as $val) {
            $explode = explode('=', $val);
            $indice = $explode[0];
            $value = $explode[1] ?? '';
            $select[preg_replace(['/^\ {1,}/', '/\ {1,}$/'], '', $indice)] = preg_replace(['/^\ {1,}/', '/\ {1,}$/'], '', $value);
        }
        return $select;
    }
}

/*
|--------------------------------------------------------------------------
| FUNÇÕES PADRÕES PARA CRIAR INPUT
|--------------------------------------------------------------------------
*/
if (!function_exists('painelInputLista')) {
    function painelInputLista($lista, $dado)
    {
        foreach ($lista as $input) {
            $funcao = $input['funcao'];
            unset($input['funcao']);

            if ($funcao == 'html') {
                echo $input['html'];
                continue;
            }

            $name = preg_replace('/\[\]$/', '', $input['name']);

            $formatar = '';
            if (array_key_exists('formatar', $input)) {
                $formatar = $input['formatar'];
                unset($input['formatar']);
            }
            $valor = is_object($dado) && !vazio($dado) && object_key_exists($name, $dado) ? painelValor($dado, $name, formatar: $formatar) : '';

            if ($funcao == 'switch') {
                $input['check'] = $valor == 1;
            } else if ($funcao == 'checkbox') {
                $input['check'] = is_array($valor) && !empty($valor) && !empty($input['value']) && in_array($input['value'], $valor);
            } else {
                $input['value'] = $valor;
            }

            if (array_key_exists('placeholder', $input) && empty($input['placeholder'])) {
                $input['placeholder'] = $input['label'];
            }

            echo call_user_func_array('form' . ucfirst($funcao), $input);
        }
    }
}

if (!function_exists('painelMenuBloco')) {
    /**
     * @param string        $titulo         Título do bloco do menu
     * @param string        $app            APP que será achamado
     * @param null|string   $appUsado       App que está em uso para colocar o Hover
     * @param null|string   $uri            URI do menu, caso não enviado, irá usar o padrão do APP
     */
    // function painelMenu(string $titulo, string $app, string $appUsado = null, ?string $uri = null)
    // {
    //     $hover = ($app == $appUsado) ? 'hover' : '';
    //     $uri = $uri == null ? 'app/' . str_replace('_', '-', $app) : preg_replace('/^\//', '', $uri);
    //     $link = LINK . '/' . $uri;
    //     return '
    //
    //     ';
    // }
    function painelMenuBloco(string $titulo, ?closure $funcao = null)
    {
        $funcao();
    }
}
if (!function_exists('painelMenu')) {
    function painelMenu(string $appUso, array $permissao, array $lista)
    {
        $permissao['dashboard'] = true;
        $permissaoUsuario = array_merge(['dashboard_index'], sessao('USUARIO.permissao'));

        $h2 = '';
        $menu = '';
        foreach ($lista as $val) {
            if (preg_match('/^[a-zA-Zà-úÀ-Ú0-9\ ]+$/', $val)) {
                echo !empty($menu) ? '<h2>' . $h2 . '</h2>' . PHP_EOL . $menu : '';
                $h2 = $val;
                $menu = '';
                continue;
            }
            $titulo = null;
            $app = null;
            $uri = null;
            $explode = explode(
                ',',
                preg_replace(
                    ['/\,\ {1,}/', '/\ {1,}\,/', '/\:\ {1,}/', '/\ {1,}\:/'],
                    [',', ',', ':', ':'],
                    $val
                )
            );
            foreach ($explode as $linha) {
                if (preg_match('/^titulo\:/', $linha)) {
                    $titulo = preg_replace('/^titulo\:/', '', $linha);
                } elseif (preg_match('/^app\:/', $linha)) {
                    $app = preg_replace('/^app\:/', '', $linha);
                } elseif (preg_match('/^uri\:/', $linha)) {
                    $uri = preg_replace('/^uri\:/', '', $linha);
                }
            }
            $link = $uri == null ? LINK . '/app/' . str_replace('_', '-', $app) : LINK . '/' . preg_replace('/^\//', '', $uri);
            $hover = $appUso == $app ? 'hover' : '';
            if (array_key_exists($app, $permissao) && in_array($app . '_index', $permissaoUsuario)) {
                $menu .= '
                    <a href="' . $link . '" class="' . $hover . '">
                        <p>' . $titulo . '</p>
                    </a>
                ';
            }
        }
        echo !empty($menu) ? '<h2>' . $h2 . '</h2>' . PHP_EOL . $menu : '';
    }
}

if (!function_exists('botaoLoading')) {
    /**
     * @param string        $texto      Texto do botão
     * @param null|string   $id         ID para o botão
     */
    function botaoLoading(string $texto, ?string $id = null)
    {
        $id = $id != null ? 'id="' . $id . '"' : '';
        echo '
            <div class="botao button botao_loading_geral" ' . $id . '>
                <p>' . $texto . '</p>
                <span>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" height="34" x="0px" y="0px" viewBox="0 0 40 40" style="enable-background:new 0 0 40 40;" xml:space="preserve"><g><g><circle cx="20" cy="3.6" r="3.6"/><circle cx="20" cy="36.4" r="3.6"/></g><g><circle cx="8.4" cy="8.4" r="3.6"/><circle cx="31.6" cy="31.6" r="3.6"/></g><g><circle cx="3.6" cy="20" r="3.6"/><circle cx="36.4" cy="20" r="3.6"/></g><g><circle cx="8.4" cy="31.6" r="3.6"/><circle cx="31.6" cy="8.4" r="3.6"/></g></g></svg>
                </span>
            </div>
        ';
    }
}
if (!function_exists('painelPermissao')) {
    /**
     * Verifica se o usuário tem permissão
     * @param   string    $permissao    Permissão que deve ser verificada
     * @param   bool      $erro         Se true dispara status code 403, se false, retorna um bool
     * @return  bool                    True para se tiver permissão
     */
    function painelPermissao(string $permissao, $erro = true)
    {
        try {
            $permissaoLista = sessao('USUARIO.permissao');
        } catch (\Throwable) {
            $permissaoLista = [];
        }

        $validar = $permissaoLista && in_array($permissao, $permissaoLista);
        if (!$validar && $erro) {
            mensagemStatus(403);
        }
        return $validar;
    }
}
if (!function_exists('botaoControle')) {
    /**
     * Gera uma lista de botões
     * @param string    $app            O APP que está usando
     * @param string    $add            ID do botão de ADD
     * @param string    $addTexto       Texto para o botão de ADD
     * @param string    $addLink        Link para o botão de ADD
     * @param string    $editar         ID do botão de Editar
     * @param string    $editarTexto    Texto para o botão de Editar
     * @param string    $editarLink     Link para o botão de Editar
     * @param string    $download       ID do botão de Download
     * @param string    $downloadTexto  Texto para o botão de Download
     * @param string    $downloadLink   Link para o botão de Download
     * @param string    $deletar        ID do botão de Deletar
     * @param string    $deletarTexto   Texto para o botão de Deletar
     */
    function botaoControle(
        string $app,
        string $add = '',
        string $addTexto = 'ADD',
        string $addLink = '',
        bool $addPermissao = true,
        string $editar = '',
        string $editarTexto = 'EDITAR',
        string $editarLink = '',
        bool $editarPermissao = true,
        string $download = '',
        string $downloadTexto = 'DOWNLOAD',
        string $downloadLink = '',
        bool $downloadPermissao = true,
        string $deletar = '',
        string $deletarTexto = 'DELETAR',
        bool $deletarPermissao = true,
    ) {
        $permissao = sessao('USUARIO.permissao');
        $app = str_replace('-', '_', $app);
        $dev = sessao('USUARIO.dev') == 1;

        $addHtml = '';
        if (!empty($add) && !empty($addLink) && $addPermissao && (in_array($app . '_add', $permissao) || $dev)) {
            $addHtml = '
                <a class="botao add" href="' . $addLink . '" id="' . $add . '">
                    <i>' . iconeAdd(13) . '</i>
                    <p>' . $addTexto . '</p>
                </a>
            ';
        } else if (!empty($add) && $addPermissao && (in_array($app . '_add', $permissao) || $dev)) {
            $addHtml = '
                <button class="botao add" id="' . $add . '">
                    <i>' . iconeAdd(13) . '</i>
                    <p>' . $addTexto . '</p>
                </button>
            ';
        }

        $editarHtml = '';
        if (!empty($editar) && $editarPermissao && !empty($editarLink) && (in_array($app . '_editar', $permissao) || $dev)) {
            $editarHtml = '
                <a class="botao editar" href="' . $editarLink . '" id="' . $editar . '">
                    <i>' . iconeEditar(15) . '</i>
                    <p>' . $editarTexto . '</p>
                </a>
            ';
        } else if (!empty($editar) && $editarPermissao && (in_array($app . '_editar', $permissao) || $dev)) {
            $editarHtml = '
                <div class="botao editar" id="' . $editar . '">
                    <i>' . iconeEditar(15) . '</i>
                    <p>' . $editarTexto . '</p>
                </div>
            ';
        }

        $deletarHtml = '';
        if (!empty($deletar) && $deletarPermissao && (in_array($app . '_deletar', $permissao) || $dev)) {
            $deletarHtml = '
                <button class="botao deletar" id="' . $deletar . '">
                    <i>' . iconeDeletar(25) . '</i>
                    <p>' . $deletarTexto . '</p>
                </button>
            ';
        }

        $downloadHtml = '';
        if (!empty($download) && $downloadPermissao && !empty($downloadLink) && (in_array($app . '_download', $permissao) || $dev)) {
            $downloadHtml = '
                <a class="botao download" href="' . $downloadLink . '" id="' . $download . '">
                    <i>' . iconeDownload(18) . '</i>
                    <p>' . $downloadTexto . '</p>
                </a>
            ';
        } else if (!empty($download) && $downloadPermissao && (in_array($app . '_download', $permissao) || $dev)) {
            $downloadHtml = '
                <div class="botao download" id="' . $download . '">
                    <i>' . iconeDownload(18) . '</i>
                    <p>' . $downloadTexto . '</p>
                </div>
            ';
        }
        return '
            <div id="bloco_botao_salvar">
                ' . $downloadHtml . '
                ' . $deletarHtml . '
                ' . $editarHtml . '
                ' . $addHtml . '
            </div>
        ';
    }
}
