<?php

if (!function_exists('formFooter')) {
    function formFooter($mensagem = false, $contador = false): string
    {
        if (!$mensagem && !$contador) {
            return '';
        }

        $html = '<div class="bloco_input_footer">';
        if ($mensagem) {
            $html .= '<div class="input_mensagem"></div>';
        }
        if ($contador) {
            $html .= '
                <div class="bloco_input_contador">
                    <div class="bloco_input_contador_numero">0</div>
                    <div class="bloco_input_contador_barra">/</div>
                    <div class="bloco_input_contador_numero"></div>
                </div>
            ';
        }
        $html .= '</div>';
        return $html;
    }
}
if (!function_exists('formHidden')) {
    function formHidden(
        string | array $name,
        $value = '',
        string $class = '',
        string $id = ''
    ) {
        $id = !empty($id) ? $id : 'input_' . $name;
        $class = !empty($class) ? 'class="' . $class . '"' : '';

        echo '<input type="hidden" name="' . $name . '" id="' . $id . '" value="' . $value . '">';
    }
}
if (!function_exists('formInput')) {
    // doc
    // exemplo
    // echo formInput name:nome,label:Nome,placeholder:Digite_um_nome
    // echo formInput name:nome1|nome2,label:Nome,placeholder:Digite_um_nome|Digite_outro_nome
    /**
     * Gera um Input padrão
     *
     * @param  string|array   $name         Name do input, array para 2 inputs
     * @param  string         $label        Label do input
     * @param  mixed          $value        Valor do input, array para 2 valores
     * @param  string|array   $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string         $class        Class para o bloco geral
     * @param  string         $id           ID para o bloco geral
     * @param  string         $html         Html de complemento para o input
     * @param  string         $icone        Icone sem cor para o input
     * @param  string         $iconeCor     Icone com cor para o input
     * @param  bool|array     $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool           $focus        Se vai focar o input
     * @param  int|array      $contador     Quantidade de caracteres que o input vai ter, array para 2 valores
     * @param  string|array   $type         O type do input, array para 2 types
     * @param  array          $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string|array   $mascara      Mascara para o input, array para 2 mascaras
     * @param  string         $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool|array     $numero       Se o input vai puxar o teclado numérico, array para 2 inputs
     * @param  bool|array     $data         Se o input vai ser do tipo data, array para 2 inputs
     * @param  bool|array     $senha        Se o input será do tipo password, array para 2 inputs
     * @param  bool           $url          Se o input será uma URL, não pode ter 2 input
     * @param  bool           $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string         $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array          $request      Caso o action precise enviar valores de outros inputs
     * @param  bool           $footer       Se o input vai ter um footer
     * @param  string         $separador    Um separador quando tiver 2 inputs
     * @param  null|int|array $maximo       Valor maximo para o input, array para 2 inputs
     * @return string         HTML com o código do input
     * @return mixed          $local Valor para o localhost
     */
    function formInput(
        string|array $name,
        string $label = '',
        $value = '',
        string|array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool|array $obrigatorio = false,
        bool $focus = false,
        null|int|array $contador = null,
        string|array $type = 'text',
        array $attr = [],
        string|array $mascara = '',
        string $ajuda = '',
        bool|array $numero = false,
        bool|array $data = false,
        bool|array $senha = false,
        bool $url = false,
        bool $autocomplete = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
        mixed $local = ''
    ): string {
        $idBloco = !empty($id) ? $id : 'id_' . md5(uniqid(time()));

        $focusHtml = $focus ? 'autofocus' : '';

        if (eLocalhost() && empty($value)) {
            $value = $local;
        }

        $classBloco = [];
        $classInput = [];
        $classInputSecundario = [];

        $attrBloco = [];
        $attrInput = ['autocomplete="off"'];
        $attrInputSecundario = ['autocomplete="off"'];

        if ($class) {
            $classBloco[] = $class;
        }

        $iconeHtml = '';
        if (!empty($icone)) {
            $iconeHtml = '<div class="input_icone">' . $icone . '</div>';
            $classBloco[] = 'bloco_input_icone';
        }
        if (!empty($iconeCor)) {
            $iconeHtml = '<div class="input_icone">' . $iconeCor . '</div>';
            $classBloco[] = 'bloco_input_icone bloco_input_icone_cor';
        }

        if ($obrigatorio) {
            $obrigatorio = !is_array($obrigatorio) ? [$obrigatorio] : $obrigatorio;
            if (true === $obrigatorio[0]) {
                $classInput[] = 'input_obrigatorio';
            }
            if ((!isset($obrigatorio[1]) && true === $obrigatorio[0]) || true === $obrigatorio[1]) {
                $classInputSecundario[] = 'input_obrigatorio';
            }
        }
        if ($data) {
            $data = !is_array($data) ? [$data] : $data;
            if (true === $data[0]) {
                $classInput[] = 'input_data';
                $classBloco[] = 'bloco_input_data';
            }
            if ((!isset($data[1]) && true === $data[0]) || true === $data[1]) {
                $classInputSecundario[] = 'input_data';
            }
        }

        $contadorStatus = (is_numeric($contador) && $contador > 0) || (is_array($contador) && count($contador) == 2 && is_numeric($contador[0]) && is_numeric($contador[1] ?? ''));
        $footerHtml = '';
        $footerStatus = $footer;
        if ($footerStatus) {
            $footerHtml = formFooter(true, $contadorStatus);

            if ($contadorStatus && is_array($contador) && count($contador) == 2) {
                $attrInput[] = 'data-contador="' . $contador[0] . '"';
                $attrInputSecundario[] = 'data-contador="' . $contador[1] . '"';
                $classInput[] = 'input_contador';
                $classInputSecundario[] = 'input_contador';
            } elseif ($contadorStatus && is_numeric($contador)) {
                $attrInput[] = 'data-contador="' . $contador . '"';
                $classInput[] = 'input_contador';
            }
        } elseif ($contadorStatus && is_array($contador) && count($contador) == 2) {
            $attrInput[] = 'data-contador="' . $contador[0] . '"';
            $attrInputSecundario[] = 'data-contador="' . $contador[1] . '"';
            $classInput[] = 'input_contador';
            $classInputSecundario[] = 'input_contador';
        } elseif ($contadorStatus && is_string($contador)) {
            $attrInput = 'data-contador="' . $contador . '"';
            $classInput[] = 'input_contador';
        }

        $typePrincipal = 'text';
        $typeSecundario = 'text';
        if (is_array($type) && count($type) == 2) {
            $typePrincipal = in_array($type[0] ?? '', ['text', 'email', 'tel', 'button', 'password', 'url', 'number']) ? $type[0] : 'text';
            $typeSecundario = in_array($type[1] ?? '', ['text', 'email', 'tel', 'button', 'password', 'url', 'number']) ? $type[1] : 'text';
        } elseif (is_string($type) && in_array($type ?? '', ['text', 'email', 'tel', 'button', 'password', 'url', 'number'])) {
            $typePrincipal = $type;
        }

        $mascaraPrincipal = '';
        $mascaraSecundaria = '';
        if ($mascara) {
            $mascara = !is_array($mascara) ? [$mascara] : $mascara;
            $mascaraPrincipal = $mascara[0];
            $attrInput[] = 'data-mascara="' . $mascara[0] . '"';
            if ($mascara[1] ?? false) {
                $mascaraSecundaria = $mascara[1];
                $attrInputSecundario[] = 'data-mascara="' . $mascara[1] . '"';
            }
        }
        if ($numero) {
            $numero = !is_array($numero) ? [$numero] : $numero;
            if (true === $numero[0]) {
                $attrInput[] = 'inputmode="numeric"';
            }
            if ($numero[1] ?? false) {
                $attrInputSecundario[] = 'inputmode="numeric"';
            }
        }
        if ($placeholder) {
            $placeholder = !is_array($placeholder) ? [$placeholder] : $placeholder;
            if (!empty($placeholder[0])) {
                $attrInput[] = 'placeholder="' . $placeholder[0] . '"';
            }
            if (!empty($placeholder[1] ?? false)) {
                $attrInputSecundario[] = 'placeholder="' . $placeholder[1] . '"';
            }
        }
        if ($value) {
            $value = !is_array($value) ? [$value] : $value;
            $valuePrincipal = $value[0];
            $valueSecundario = $value[1] ?? '';

            if ($mascaraPrincipal == '00/00/0000') {
                $valuePrincipal = dataBr($valuePrincipal);
            } elseif ($mascaraPrincipal == '00/00/0000 00:00:00') {
                $valuePrincipal = dataHoraBr($valuePrincipal);
            } elseif ($mascaraPrincipal == 'telefone') {
                $valuePrincipal = strTelefone($valuePrincipal);
            } elseif ($mascaraPrincipal == '000.000.000-00') {
                $valuePrincipal = strCpf($valuePrincipal);
            } elseif ($mascaraPrincipal == '00.000.000/0000-00') {
                $valuePrincipal = strCnpj($valuePrincipal);
            } elseif ($mascaraPrincipal == 'dinheiro') {
                $valuePrincipal = strDinheiro($valuePrincipal);
            }

            if ($mascaraSecundaria == '00/00/0000') {
                $valueSecundario = dataBr($valueSecundario);
            } elseif ($mascaraSecundaria == '00/00/0000 00:00:00') {
                $valueSecundario = dataHoraBr($valueSecundario);
            } elseif ($mascaraSecundaria == 'telefone') {
                $valueSecundario = strTelefone($valueSecundario);
            } elseif ($mascaraSecundaria == '000.000.000-00') {
                $valueSecundario = strCpf($valueSecundario);
            } elseif ($mascaraSecundaria == '00.000.000/0000-00') {
                $valueSecundario = strCnpj($valueSecundario);
            } elseif ($mascaraSecundaria == 'dinheiro') {
                $valuePrincipal = strDinheiro($valueSecundario);
            }
            if ($url) {
                $valuePrincipal = str_replace(['https://', 'http://'], '', $valuePrincipal);
            }

            $attrInput[] = 'value="' . $valuePrincipal . '"';
            if (!empty($valueSecundario)) {
                $attrInputSecundario[] = 'value="' . $valueSecundario . '"';
            }
        }

        if ($attr && count($attr) == 2 && is_array($attr[0]) && is_array($attr[1])) {
            foreach ($attr[0] as $ind => $val) {
                if ($ind == 'autocomplete' && str_starts_with($attrInput[0], 'autocomplete=')) {
                    unset($attrInput[0]);
                }
                $attrInput[] = $ind . '="' . $val . '"';
            }
            foreach ($attr[1] as $ind => $val) {
                if ($ind == 'autocomplete' && str_starts_with($attrInputSecundario[0], 'autocomplete=')) {
                    unset($attrInputSecundario[0]);
                }
                $attrInputSecundario[] = $ind . '="' . $val . '"';
            }
        } elseif ($attr) {
            foreach ($attr as $ind => $val) {
                if ($ind == 'autocomplete' && str_starts_with($attrInput[0], 'autocomplete=')) {
                    unset($attrInput[0]);
                }
                if ($ind == 'autocomplete' && str_starts_with($attrInputSecundario[0], 'autocomplete=')) {
                    unset($attrInputSecundario[0]);
                }
                $attrInput[] = $ind . '="' . $val . '"';
                $attrInputSecundario[] = $ind . '="' . $val . '"';
            }
        }

        if (is_numeric($maximo) && $maximo > 0) {
            $attrInput[] = 'maxlength=' . $maximo;
        } elseif (
            is_array($maximo) && count($maximo) == 2 &&
            is_numeric($maximo[0]) && $maximo[0] > 0 &&
            is_numeric($maximo[1] && $maximo[1] > 0)
        ) {
            $attrInput[] = 'maxlength=' . $maximo[0];
            $attrInputSecundario[] = 'maxlength=' . $maximo[1];
        }

        $ajudaHtml = '';
        if ($ajuda) {
            $ajudaHtml = '<div class="input_ajuda" data-ajuda="' . $ajuda . '">?</div>';
            $classBloco[] = 'bloco_ajuda';
        }
        $senhaHtmlPrincipal = '';
        $senhaHtmlSecundario = '';
        if ($senha) {
            $classBloco[] = 'bloco_senha';
            $senha = !is_array($senha) ? [$senha] : $senha;

            $senhaPrincipalStatus = false;
            $senhaSecundarioStatus = false;
            if (true === $senha[0]) {
                $typePrincipal = 'password';
                $senhaPrincipalStatus = true;
            }
            if ($senha[1] ?? false) {
                $typeSecundario = 'password';
                $senhaSecundarioStatus = true;
            }
            if ($senhaPrincipalStatus) {
                $senhaHtmlPrincipal = '
                    <div class="botao_olho botao_mostrar_senha">
                        <div class="icone_olho olho_aberto">
                            <svg height="20" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:cc="hqttp://creativecommons.org/ns#"
                                xmlns:rdf="https://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg"
                                xmlns:sodipodi="https://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                xmlns:inkscape="https://www.inkscape.org/namespaces/inkscape" viewBox="0 0 10 10"
                                version="1.1" x="0px" y="0px"><g transform="translate(0,-288.53333)">
                                    <path style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;
                                    font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;
                                    font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;
                                    font-variant-numeric:normal;font-variant-alternates:normal;
                                    font-feature-settings:normal;text-indent:0;text-align:start;
                                    text-decoration:none;text-decoration-line:none;text-decoration-style:solid;
                                    text-decoration-color:#000000;letter-spacing:normal;word-spacing:normal;
                                    text-transform:none;writing-mode:lr-tb;direction:ltr;text-orientation:mixed;
                                    dominant-baseline:auto;baseline-shift:baseline;text-anchor:start;white-space:normal;
                                    shape-padding:0;clip-rule:nonzero;display:inline;overflow:visible;visibility:visible;
                                    opacity:1;isolation:auto;mix-blend-mode:normal;color-interpolation:sRGB;
                                    color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;
                                    vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;
                                    stroke-width:0.5291667;stroke-linecap:round;stroke-linejoin:round;
                                    stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;
                                    color-rendering:auto;image-rendering:auto;shape-rendering:auto;
                                    text-rendering:auto;enable-background:accumulate"
                                    d="m 4.2324219,289.85742 c -2.6313577,0 -3.8257607,2.43901 -3.88281252,2.55274 -0.11226944,
                                    0.22378 -0.11226944,0.4891 0,0.71289 0.0570534,0.11372 1.25145392,2.55273 3.88281252,
                                    2.55273 2.6313585,0 3.8268388,-2.43726 3.8847656,-2.55273 0.1122691,-0.22379 0.1122691,
                                    -0.48911 0,-0.71289 -0.057924,-0.11547 -1.2534079,-2.55274 -3.8847656,-2.55274 z m 0,
                                    0.52735 c 2.307529,0 3.3281513,2.09631 3.4121093,2.26367 0.037563,0.0749 0.037563,
                                    0.16145 0,0.23633 -0.083955,0.16735 -1.1045779,2.26367 -3.4121093,2.26367 -2.3075315,
                                    0 -3.32532785,-2.09458 -3.41015628,-2.26367 -0.037563,-0.0749 -0.037563,-0.16146 0,
                                    -0.23633 0.08483,-0.1691 1.10262728,-2.26367 3.41015628,-2.26367 z"/>
                                    <path style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;
                                    font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;
                                    font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;
                                    font-variant-numeric:normal;font-variant-alternates:normal;font-feature-settings:normal;
                                    text-indent:0;text-align:start;text-decoration:none;text-decoration-line:none;
                                    text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;
                                    word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;
                                    text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;
                                    text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;
                                    display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;
                                    mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;
                                    solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;
                                    fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;
                                    stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;
                                    stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;
                                    shape-rendering:auto;text-rendering:auto;enable-background:accumulate"
                                    d="m 4.2324219,290.91406 c -1.0197435,0 -1.8515625,0.83377 -1.8515625,1.85352 0,
                                    1.01974 0.831819,1.85156 1.8515625,1.85156 1.0197435,0 1.8535156,-0.83182 1.8535156,
                                    -1.85156 0,-1.01975 -0.8337721,-1.85352 -1.8535156,-1.85352 z m 0,0.5293 c 0.7337606,
                                    0 1.3242187,0.59046 1.3242187,1.32422 0,0.73376 -0.5904581,1.32226 -1.3242187,
                                    1.32226 -0.7337606,0 -1.3222656,-0.5885 -1.3222657,-1.32226 1e-7,-0.73376 0.5885051,
                                    -1.32422 1.3222657,-1.32422 z"/></g>
                            </svg>
                        </div>
                        <div class="icone_olho olho_fechado">
                            <svg height="20" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:cc="https://creativecommons.org/ns#"
                                xmlns:rdf="https://www.w3.org/1999/02/22-rdf-syntax-ns#"
                                xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg"
                                xmlns:sodipodi="https://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                                xmlns:inkscape="https://www.inkscape.org/namespaces/inkscape"
                                viewBox="0 0 10 10" version="1.1" x="0px" y="0px">
                                    <g transform="translate(0,-288.53333)"><path style="color:#000000;font-style:normal;
                                    font-variant:normal;font-weight:normal;font-stretch:normal;font-size:medium;
                                    line-height:normal;font-family:sans-serif;font-variant-ligatures:normal;
                                    font-variant-position:normal;font-variant-caps:normal;font-variant-numeric:normal;
                                    font-variant-alternates:normal;font-feature-settings:normal;text-indent:0;
                                    text-align:start;text-decoration:none;text-decoration-line:none;
                                    text-decoration-style:solid;text-decoration-color:#000000;letter-spacing:normal;
                                    word-spacing:normal;text-transform:none;writing-mode:lr-tb;direction:ltr;
                                    text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;
                                    text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;
                                    display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;
                                    mix-blend-mode:normal;color-interpolation:sRGB;color-interpolation-filters:linearRGB;
                                    solid-color:#000000;solid-opacity:1;vector-effect:none;fill-opacity:1;
                                    fill-rule:nonzero;stroke:none;stroke-width:0.52916664;stroke-linecap:round;
                                    stroke-linejoin:round;stroke-miterlimit:4;stroke-dasharray:none;
                                    stroke-dashoffset:0;stroke-opacity:1;color-rendering:auto;image-rendering:auto;
                                    shape-rendering:auto;text-rendering:auto;enable-background:accumulate"
                                    d="m 4.2322998,289.85676 c -0.393179,0 -0.7513653,0.0586 -1.0790039,
                                    0.14987 a 0.26460979,0.26460979 0 1 0 0.1415934,0.50952 c 0.2881961,
                                    -0.0803 0.5964015,-0.13022 0.9374105,-0.13022 2.3075352,0 3.3277042,
                                    2.09501 3.411678,2.26239 0.037609,0.075 0.037609,0.16172 0,0.23668 -0.035993,
                                    0.0718 -0.2720986,0.54934 -0.749825,1.05834 a 0.26460979,0.26460979 0 1 0 0.3855062,
                                    0.36173 c 0.5324186,-0.56727 0.808275,-1.12375 0.837675,-1.18236 0.1122222,
                                    -0.22369 0.1122222,-0.4884 0,-0.7121 -0.057908,-0.11543 -1.2536829,
                                    -2.55385 -3.8850342,-2.55385 z"/>
                                    <path style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;
                                    font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;
                                    font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;
                                    font-variant-numeric:normal;font-variant-alternates:normal;
                                    font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;
                                    text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;
                                    letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;
                                    direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;
                                    text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;
                                    display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;
                                    mix-blend-mode:normal;color-interpolation:sRGB;
                                    color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;
                                    vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;
                                    stroke-width:1.99999988;stroke-linecap:butt;stroke-linejoin:round;
                                    stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;
                                    color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;
                                    enable-background:accumulate"
                                    d="M 5.9863281 4.9902344 A 1.0001001 1.0001001 0 0 0 5.2929688 6.7089844
                                    L 6.7441406 8.1601562 C 3.185028 10.860344 1.4624756 14.370915 1.3203125
                                    14.654297 C 0.89616307 15.499777 0.89616307 16.500261 1.3203125 17.345703
                                    C 1.5356843 17.77502 6.0508226 26.998047 15.996094 26.998047 C 18.997745 26.998047
                                    21.514162 26.148946 23.554688 24.970703 L 25.291016 26.707031 A 1.0021986 1.0021986
                                    0 1 0 26.707031 25.289062 L 6.7109375 5.2929688 A 1.0001001 1.0001001 0 0 0
                                    5.9863281 4.9902344 z M 8.1835938 9.5996094 L 10.400391 11.816406 C 9.501688
                                    13.018159 9.0012177 14.481934 9 16 C 9 19.854173 12.145852 23 16 23 C 17.518052
                                    22.998763 18.981835 22.498306 20.183594 21.599609 L 22.082031 23.498047 C 20.396239
                                    24.388668 18.397237 24.998047 15.996094 24.998047 C 7.2746937 24.998047 3.4302493
                                    17.086875 3.109375 16.447266 C 2.9672285 16.163801 2.9672285 15.836086 3.109375
                                    15.552734 C 3.2976713 15.177393 4.9149465 11.983619 8.1835938 9.5996094 z M
                                    11.832031 13.248047 L 18.751953 20.167969 C 17.941148 20.704449 16.987131
                                    20.999184 16 21 C 13.226732 21 11 18.773266 11 16 C 11.000792 15.012892
                                    11.295543 14.058853 11.832031 13.248047 z "
                                    transform="matrix(0.26458333,0,0,0.26458333,0,288.53333)"/>
                                    <path style="color:#000000;font-style:normal;font-variant:normal;font-weight:normal;
                                    font-stretch:normal;font-size:medium;line-height:normal;font-family:sans-serif;
                                    font-variant-ligatures:normal;font-variant-position:normal;font-variant-caps:normal;
                                    font-variant-numeric:normal;font-variant-alternates:normal;
                                    font-feature-settings:normal;text-indent:0;text-align:start;text-decoration:none;
                                    text-decoration-line:none;text-decoration-style:solid;text-decoration-color:#000000;
                                    letter-spacing:normal;word-spacing:normal;text-transform:none;writing-mode:lr-tb;
                                    direction:ltr;text-orientation:mixed;dominant-baseline:auto;baseline-shift:baseline;
                                    text-anchor:start;white-space:normal;shape-padding:0;clip-rule:nonzero;
                                    display:inline;overflow:visible;visibility:visible;opacity:1;isolation:auto;
                                    mix-blend-mode:normal;color-interpolation:sRGB;
                                    color-interpolation-filters:linearRGB;solid-color:#000000;solid-opacity:1;
                                    vector-effect:none;fill-opacity:1;fill-rule:nonzero;stroke:none;
                                    stroke-width:0.52916664;stroke-linecap:round;stroke-linejoin:round;
                                    stroke-miterlimit:4;stroke-dasharray:none;stroke-dashoffset:0;stroke-opacity:1;
                                    color-rendering:auto;image-rendering:auto;shape-rendering:auto;text-rendering:auto;
                                    enable-background:accumulate" d="m 4.1615032,291.44671 c 0.025705,-0.002 0.049709,
                                    -0.003 0.07183,-0.003 0.7336853,-1.7e-4 1.3233815,0.58936 1.3229166,
                                    1.32291 -1.11e-5,0.0175 -0.00155,0.0401 -0.00362,0.0677 -0.026235,0.35212 0.5018984,
                                    0.39142 0.5281332,0.0393 0.00229,-0.0306 0.00462,-0.0662 0.00465,-0.10697 6.464e-4,
                                    -1.01994 -0.8322649,-1.85233 -1.8520815,-1.85208 -0.036836,10e-6 -0.071744,
                                    0.002 -0.1049013,0.004 -0.3575024,0.0176 -0.3238028,0.55565 0.033073,0.52813 z"/>
                                    </g>
                            </svg>
                        </div>
                    </div>
                ';
                if ($senhaSecundarioStatus) {
                    $senhaHtmlSecundario = '<div class="bloco_senha_igual"></div>';
                }
            }
        }

        if ($autocomplete) {
            $classBloco[] = 'input_autocomplete';
            if (!empty($action)) {
                $attrBloco[] = 'data-action="' . $action . '"';
            }
            if (!is_array($name)) {
                $name = $name[0];
            }
        }

        $urlHtml = '';
        if ($url) {
            $valueTemp = $value[0] ?? '';
            $http = str_starts_with($valueTemp, 'http://') ? 'http://' : 'https://';
            $urlHtml = '<div class="input_http">' . $http . '</div>';
            $classBloco[] = 'bloco_url';
            $classInput[] = 'input_url';
            if (is_array($name)) {
                $name = $name[0];
            }
        }

        if (is_array($request) && $request) {
            $attrBloco[] = 'data-request="' . base64_encode(json_encode($request)) . '"';
        }

        $bloqueadoHtml = '';
        if (!empty($bloqueado)) {
            $bloqueadoHtml = '<div class="input_bloqueado" style="display: none" id="input_' . $name . '_bloqueado" data-ajuda="' . $bloqueado . '"></div>';
            $classBloco[] = 'bloco_bloqueado';
            if (empty($value)) {
                $attrInput[] = 'readonly';
                $attrInputSecundario[] = 'readonly';
                $bloqueadoHtml = '<div class="input_bloqueado" id="input_' . $name . '_bloqueado" data-ajuda="' . $bloqueado . '"></div>';
            }
        }

        $inputSecundario = '';
        $separadorHtml = '';
        if (is_array($name) && count($name) == 2) {
            $classBloco[] = 'bloco_separador';

            $nameSecundario = $name[1];
            $name = $name[0];

            $inputSecundario = '<input class="input_separador_3 input_geral ' . implode(' ', $classInputSecundario) . '" type="' . $typeSecundario . '" name="' . $nameSecundario . '" id="input_' . $nameSecundario . '" ' . implode(' ', $attrInputSecundario) . ' >';

            $classInput[] = 'input_separador_1';

            $separadorHtml = !empty($separador) ? '<span class="input_separador_2"><p>' . $separador . '</p></span>' : '';
        } elseif (!is_string($name)) {
            $name = '';
        }

        $nameHtml = !empty($name) ? 'name="' . $name . '"' : '';

        $label = !empty($label) ? '<label for="input_' . $name . '">' . $label . '</label>' : '';
        return '
            <div class="bloco_input input_input ' . implode(' ', $classBloco) . '" id="' . $idBloco . '" ' . implode(' ', $attrBloco) . '>
                ' . $bloqueadoHtml . '
                ' . $html . '
                ' . $iconeHtml . '
                <input class="input_geral ' . implode(' ', $classInput) . '" ' . $focusHtml . ' type="' . $typePrincipal . '" ' . $nameHtml . ' ' . implode(' ', $attrInput) . ' id="input_' . $name . '">
                ' . $separadorHtml . '
                ' . $inputSecundario . '
                <div class="borda"></div>
                ' . $label . '
                ' . $ajudaHtml . '
                ' . $senhaHtmlPrincipal . '
                ' . $senhaHtmlSecundario . '
                ' . $urlHtml . '
                <i class="input_icone_erro"></i>
                ' . $footerHtml . '
            </div>
        ';
    }
}

if (!function_exists('formSelect')) {
    // doc
    // exemplo
    // echo formSelect name:select,lista:valor_1|valor_2|valor_3,label:Escolha_uma_opção,placeholder:Escolha_uma_opção
    /**
     * Gera um select padrão
     *
     * @param  string $name        Name do select
     * @param  array  $lista       Lista de opções do select ['indice_1' => 'Nome 01', 'indice_2' => 'Nome 02']
     * @param  string $label       Label do select
     * @param  string $placeholder Placeholder do select
     * @param  midex  $value       Valor inicial do select
     * @param  string $id          ID para o select
     * @param  string $class       Classe para o box do select
     * @param  bool   $obrigatorio Se o select é obrigatório
     * @param  bool   $footer      Se o select vai ter um footer
     * @param  bool   $change      Nome da funcao onchange
     * @return string HTML com o código do select
     */
    function formSelect(
        $name,
        array $lista,
        string $label = '',
        string $placeholder = '',
        $value = '',
        string $id = '',
        string $class = '',
        bool $obrigatorio = false,
        bool $footer = true,
        string $change = '',
        mixed $local = ''
    ): string {
        if (eLocalhost() && empty($value)) {
            $value = $local;
        }
        $valueTexto = (is_string($value) || is_numeric($value)) && !empty($value) && array_key_exists($value, $lista)
            ? $lista[$value] : '';
        if (is_array($valueTexto)) {
            $valueTexto = $valueTexto[0] ?? $valueTexto;
        }

        $changeHtml = !empty($change) ? 'data-onchange="' . $change . '"' : '';
        $labelHtml = !empty($label) ? '<label for="input_' . $name . '_texto">' . $label . '</label>' : '';

        $obrigatorio = !empty($obrigatorio) ? 'input_obrigatorio' : '';
        $id = !empty($id) ? $id : 'id_' . md5(uniqid(time()));

        $option = [];
        if ($lista) {
            foreach ($lista as $ind => $val) {
                $classeInterna = 'lista';
                if (is_array($val)) {
                    $classeInterna = $val[1] ?? '';
                    $val = $val[0];
                }
                $ind = is_int($ind) ? $val : $ind;
                $option[] = '<li class="' . $classeInterna . ' " data-value="' . $ind . '">' . $val . '</li>';
            }
        }

        $footerHtml = '';
        if ($footer) {
            $footerHtml = formFooter(true);
        }

        return '
            <div class="bloco_input input_select ' . $class . '" id="' . $id . '">
                <input class="input_select_value" type="hidden" name="' . $name . '" id="input_' . $name . '" value="' . $value . '">
                <input autocomplete="off" class="input_geral input_select_texto ' . $obrigatorio . '" ' . $changeHtml . ' type="text" placeholder="' . $placeholder . '" id="input_' . $name . '_texto" value="' . $valueTexto . '" >
                <div class="borda"></div>
                ' . $labelHtml . '
                <ul class="option">
                    ' . implode(' ', $option) . '
                </ul>
                <i class="input_icone"></i>
                ' . $footerHtml . '
            </div>
        ';
    }
}

if (!function_exists('formInputSelect')) {
    function formInputSelect($optionInput, $optionSelect, $option = []): string
    {
        $optionInput['footer'] = false;
        $optionSelect['footer'] = false;
        $inputHtml = formInput($optionInput);
        $selectHtml = '';

        $id = isset($option['id']) && !empty($option['id']) ? 'id="' . $option['id'] . '"' : '';
        $class = $option['class'] ?? '';

        $contador = isset($option['contador']) && is_numeric($option['contador']) ? $option['contador'] : 0;
        $footer = formFooter(true, $contador > 0);

        return '
            <div class="bloco_input_select ' . $class . '" ' . $id . '>
                <div class="input_conteudo">
                    ' . $inputHtml . '
                    <div class="input_select_linha"></div>
                    ' . $selectHtml . '
                </div>
                ' . $footer . '
            </div>
        ';
    }
}
if (!function_exists('formAutocomplete')) {
    /**
     * @param string|array $name        Name do input, array para 2 inputs
     * @param string       $label       Label do input
     * @param mixed        $value       Valor do input, array para 2 valores
     * @param string|array $placeholder Placeholder do input, array para 2 placeholders
     * @param string       $class       Class para o bloco geral
     * @param string       $id          ID para o bloco geral
     * @param string       $html        Html de complemento para o input
     * @param string       $icone       Icone sem cor para o input
     * @param string       $iconeCor    Icone com cor para o input
     * @param bool|array   $obrigatorio Se o input vai ser obrigatório, array para 2 inputs
     * @param bool         $focus       Se vai focar o input
     * @param int|array    $contador    Quantidade de caracteres que o input vai ter, array para 2 valores
     * @param string|array $type        O type do input, array para 2 types
     * @param string|array $attr        Atributos para o input, array para passar atributos para 2 inputs
     * @param string|array $mascara     Mascara para o input, array para 2 mascaras
     * @param string       $ajuda       Icone de ajuda com um texto de ajuda
     * @param bool|array   $numero      Se o input vai puxar o teclado numérico, array para 2 inputs
     * @param bool|array   $data        Se o input vai ser do tipo data, array para 2 inputs
     * @param bool         $url         Se o input será uma URL, não pode ter 2 input
     * @param string       $action      Action que será disparado via Ajax ao digitar no autocomplete
     * @param array        $request     Caso o action precise enviar valores de outros inputs
     * @param bool         $footer      Se o input vai ter um footer
     * @param string       $separador   Um separador quando tiver 2 inputs
     */
    function formAutocomplete(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        null | int | array $contador = null,
        string | array $type = 'text',
        string | array $attr = [],
        string | array $mascara = '',
        string $ajuda = '',
        bool | array $numero = false,
        bool | array $data = false,
        bool $url = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        mixed $local = ''
    ): string {
        return formInput(
            $name,
            $label,
            $value,
            $placeholder,
            $class,
            $id,
            $html,
            $icone,
            $iconeCor,
            $obrigatorio,
            $focus,
            $contador,
            $type,
            $attr,
            $mascara,
            $ajuda,
            $numero,
            $data,
            false,
            $url,
            true,
            $action,
            $footer,
            $request,
            $separador,
            local: $local
        );
    }
}
if (!function_exists('formNumero')) {
    // doc
    // exemplo
    // echo formNumero name:numero,label:Número,placeholder:Digite_um_numero
    /**
     * Gera um Input para número
     *
     * @param  string|array   $name         Name do input, array para 2 inputs
     * @param  string         $label        Label do input
     * @param  mixed          $value        Valor do input, array para 2 valores
     * @param  string|array   $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string         $class        Class para o bloco geral
     * @param  string         $id           ID para o bloco geral
     * @param  string         $html         Html de complemento para o input
     * @param  string         $icone        Icone sem cor para o input
     * @param  string         $iconeCor     Icone com cor para o input
     * @param  bool|array     $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool           $focus        Se vai focar o input
     * @param  int|array      $contador     Quantidade de caracteres que o input vai ter, array para 2 valores
     * @param  array          $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string|array   $mascara      Mascara para o input, array para 2 mascaras
     * @param  string         $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool           $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string         $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  bool           $footer       Se o input vai ter um footer
     * @param  array          $request      Caso o action precise enviar valores de outros inputs
     * @param  string         $separador    Um separador quando tiver 2 inputs
     * @param  null|int|array $maximo       Valor máximo que pode ter no input
     * @return string         HTML com o código do input
     */
    function formNumero(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        null | int | array $contador = null,
        array $attr = [],
        string | array $mascara = '',
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
        mixed $local = ''
    ): string {
        return formInput(
            name: $name,
            label: $label,
            value: $value,
            placeholder: $placeholder,
            class: $class,
            id: $id,
            html: $html,
            icone: $icone,
            iconeCor: $iconeCor,
            obrigatorio: $obrigatorio,
            focus: $focus,
            contador: $contador,
            attr: $attr,
            mascara: !empty($mascara) ? $mascara : 'numero',
            ajuda: $ajuda,
            numero: true,
            autocomplete: $autocomplete,
            action: $action,
            footer: $footer,
            request: $request,
            separador: $separador,
            maximo: $maximo,
            local: $local
        );
    }
}

if (!function_exists('formCpf')) {
    // doc
    // exemplo
    // echo formCpf name:cpf,label:CPF
    /**
     * Gera um Input para CPF
     *
     * @param  string|array   $name         Name do input, array para 2 inputs
     * @param  string         $label        Label do input
     * @param  mixed          $value        Valor do input, array para 2 valores
     * @param  string|array   $placeholder  Placeholder do input, array para 2 placeholders, por padrão 000.000.000-00
     * @param  string         $class        Class para o bloco geral
     * @param  string         $id           ID para o bloco geral
     * @param  string         $html         Html de complemento para o input
     * @param  string         $icone        Icone sem cor para o input
     * @param  string         $iconeCor     Icone com cor para o input
     * @param  bool|array     $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool           $focus        Se vai focar o input
     * @param  array          $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string         $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool           $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string         $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array          $request      Caso o action precise enviar valores de outros inputs
     * @param  bool           $footer       Se o input vai ter um footer
     * @param  string         $separador    Um separador quando tiver 2 inputs
     * @param  null|int|array $maximo       Valor maximo para o input, array para 2 inputs
     * @return string         HTML com o código do input
     */
    function formCpf(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
        mixed $local = ''
    ): string {
        return formInput(
            name: $name,
            label: $label,
            value: $value,
            placeholder: !empty($placeholder) ? $placeholder : '000.000.000-00',
            class: $class,
            id: $id,
            html: $html,
            icone: $icone,
            iconeCor: $iconeCor,
            obrigatorio: $obrigatorio,
            focus: $focus,
            attr: $attr,
            mascara: '000.000.000-00',
            ajuda: $ajuda,
            numero: true,
            autocomplete: $autocomplete,
            action: $action,
            footer: $footer,
            request: $request,
            separador: $separador,
            maximo: $maximo,
            local: $local
        );
    }
}

if (!function_exists('formCnpj')) {
    // doc
    // exemplo
    // echo formCnpj name:cnpj,label:CNPJ
    /**
     * Gera um Input para CNPJ
     *
     * @param  string|array   $name         Name do input, array para 2 inputs
     * @param  string         $label        Label do input
     * @param  mixed          $value        Valor do input, array para 2 valores
     * @param  string|array   $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string         $class        Class para o bloco geral
     * @param  string         $id           ID para o bloco geral
     * @param  string         $html         Html de complemento para o input
     * @param  string         $icone        Icone sem cor para o input
     * @param  string         $iconeCor     Icone com cor para o input
     * @param  bool|array     $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool           $focus        Se vai focar o input
     * @param  array          $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string         $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool           $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string         $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array          $request      Caso o action precise enviar valores de outros inputs
     * @param  bool           $footer       Se o input vai ter um footer
     * @param  string         $separador    Um separador quando tiver 2 inputs
     * @param  null|int|array $maximo       Valor maximo para o input, array para 2 inputs
     * @return string         HTML com o código do input
     */
    function formCnpj(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
        mixed $local = ''
    ): string {
        return formInput(
            name: $name,
            label: $label,
            value: $value,
            placeholder: !empty($placeholder) ? $placeholder : '00.000.000/0000-00',
            class: $class,
            id: $id,
            html: $html,
            icone: $icone,
            iconeCor: $iconeCor,
            obrigatorio: $obrigatorio,
            focus: $focus,
            attr: $attr,
            mascara: '00.000.000/0000-00',
            ajuda: $ajuda,
            numero: true,
            autocomplete: $autocomplete,
            action: $action,
            footer: $footer,
            request: $request,
            separador: $separador,
            maximo: $maximo,
            local: $local
        );
    }
}

if (!function_exists('formSenha')) {
    // doc
    // exemplo
    // echo formSenha senha,placeholder:Digite_sua_senha,label:Senha
    // echo formSenha senha_1|senha2,label:Senha,placeholder:Digite_uma_senha|Repetir_nova_senha
    /**
     * Gera um Input para senha
     *
     * @param  string|array $name        Name do input, array para 2 inputs
     * @param  string       $label       Label do input
     * @param  mixed        $value       Valor do input, array para 2 valores
     * @param  string|array $placeholder Placeholder do input, array para 2 placeholders
     * @param  string       $class       Class para o bloco geral
     * @param  string       $id          ID para o bloco geral
     * @param  string       $html        Html de complemento para o input
     * @param  string       $icone       Icone sem cor para o input
     * @param  string       $iconeCor    Icone com cor para o input
     * @param  bool|array   $obrigatorio Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool         $focus       Se vai focar o input
     * @param  int|array    $contador    Quantidade de caracteres que o input vai ter, array para 2 valores
     * @param  array        $attr        Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string       $ajuda       Icone de ajuda com um texto de ajuda
     * @param  bool         $footer      Se o input vai ter um footer
     * @return string       HTML com o código do input
     */
    function formSenha(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        null | int | array $contador = null,
        string | array $attr = [],
        string $ajuda = '',
        bool $footer = true,
        mixed $local = ''
    ): string {
        $senha = is_array($name) ? [true, true] : true;
        return formInput(
            $name,
            $label,
            $value,
            $placeholder,
            $class,
            $id,
            $html,
            $icone,
            $iconeCor,
            $obrigatorio,
            $focus,
            $contador,
            'password',
            $attr,
            '',
            $ajuda,
            false,
            false,
            $senha,
            false,
            false,
            '',
            $footer,
            [],
            '',
            local: $local
        );
    }
}
if (!function_exists('formEmail')) {
    // doc
    // exemplo
    // echo formEmail name:email,label:E-mail,placeholder:email@dominio.com
    /**
     * Gera um Input para email
     *
     * @param  string|array $name         Name do input, array para 2 inputs
     * @param  string       $label        Label do input
     * @param  mixed        $value        Valor do input, array para 2 valores
     * @param  string|array $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string       $class        Class para o bloco geral
     * @param  string       $id           ID para o bloco geral
     * @param  string       $html         Html de complemento para o input
     * @param  string       $icone        Icone sem cor para o input
     * @param  string       $iconeCor     Icone com cor para o input
     * @param  bool|array   $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool         $focus        Se vai focar o input
     * @param  int|array    $contador     Quantidade de caracteres que o input vai ter, array para 2 valores
     * @param  array        $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string       $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool         $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string       $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array        $request      Caso o action precise enviar valores de outros inputs
     * @param  bool         $footer       Se o input vai ter um footer
     * @param  string       $separador    Um separador quando tiver 2 inputs
     * @return string       HTML com o código do input
     */
    function formEmail(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        null | int | array $contador = null,
        string | array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        array $request = [],
        bool $footer = true,
        string $separador = '',
        mixed $local = ''
    ) {
        return formInput(
            $name,
            $label,
            $value,
            $placeholder,
            $class,
            $id,
            $html,
            $icone,
            $iconeCor,
            $obrigatorio,
            $focus,
            $contador,
            'email',
            $attr,
            '',
            $ajuda,
            false,
            false,
            false,
            false,
            $autocomplete,
            $action,
            $footer,
            $request,
            $separador,
            local: $local
        );
    }
}
if (!function_exists('formTelefone')) {
    // doc
    // exemplo
    // echo formTelefone name:telefone,label:Telefone
    /**
     * Gera um Input para telefone
     *
     * @param  string|array $name         Name do input, array para 2 inputs
     * @param  string       $label        Label do input
     * @param  mixed        $value        Valor do input, array para 2 valores
     * @param  string|array $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string       $class        Class para o bloco geral
     * @param  string       $id           ID para o bloco geral
     * @param  string       $html         Html de complemento para o input
     * @param  string       $icone        Icone sem cor para o input
     * @param  string       $iconeCor     Icone com cor para o input
     * @param  bool|array   $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool         $focus        Se vai focar o input
     * @param  array        $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string       $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool         $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string       $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array        $request      Caso o action precise enviar valores de outros inputs
     * @param  bool         $footer       Se o input vai ter um footer
     * @param  string       $separador    Um separador quando tiver 2 inputs
     * @return string       HTML com o código do input
     */
    function formTelefone(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        array $request = [],
        bool $footer = true,
        string $separador = '',
        mixed $local = ''
    ): string {
        return formInput(
            $name,
            $label,
            $value,
            $placeholder,
            $class,
            $id,
            $html,
            $icone,
            $iconeCor,
            $obrigatorio,
            $focus,
            null,
            'text',
            $attr,
            'telefone',
            $ajuda,
            true,
            false,
            false,
            false,
            $autocomplete,
            $action,
            $footer,
            $request,
            $separador,
            local: $local
        );
    }
}
if (!function_exists('formUrl')) {
    // doc
    // exemplo
    // echo formUrl name:url,label:URL,placeholder:Digite_uma_url
    /**
     * Gera um Input para URL
     *
     * @param  string|array $name         Name do input, array para 2 inputs
     * @param  string       $label        Label do input
     * @param  mixed        $value        Valor do input, array para 2 valores
     * @param  string|array $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string       $class        Class para o bloco geral
     * @param  string       $id           ID para o bloco geral
     * @param  string       $html         Html de complemento para o input
     * @param  string       $icone        Icone sem cor para o input
     * @param  string       $iconeCor     Icone com cor para o input
     * @param  bool|array   $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool         $focus        Se vai focar o input
     * @param  array        $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string       $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool         $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string       $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array        $request      Caso o action precise enviar valores de outros inputs
     * @param  bool         $footer       Se o input vai ter um footer
     * @return string       HTML com o código do input
     */
    function formUrl(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        string | array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        array $request = [],
        bool $footer = true,
        mixed $local = ''
    ): string {
        return formInput(
            $name,
            $label,
            $value,
            $placeholder,
            $class,
            $id,
            $html,
            $icone,
            $iconeCor,
            $obrigatorio,
            $focus,
            null,
            'url',
            $attr,
            '',
            $ajuda,
            false,
            false,
            false,
            true,
            $autocomplete,
            $action,
            $footer,
            $request,
            '',
            local: $local
        );
    }
}
if (!function_exists('formData')) {
    // doc
    // exemplo
    // echo formData name:data,label:Data
    /**
     * Gera um Input para data
     *
     * @param  string|array $name         Name do input, array para 2 inputs
     * @param  string       $label        Label do input
     * @param  mixed        $value        Valor do input, array para 2 valores
     * @param  string|array $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string       $class        Class para o bloco geral
     * @param  string       $id           ID para o bloco geral
     * @param  string       $html         Html de complemento para o input
     * @param  string       $icone        Icone sem cor para o input
     * @param  string       $iconeCor     Icone com cor para o input
     * @param  bool|array   $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool         $focus        Se vai focar o input
     * @param  array        $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string       $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool         $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string       $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array        $request      Caso o action precise enviar valores de outros inputs
     * @param  bool         $footer       Se o input vai ter um footer
     * @param  string       $separador    Um separador quando tiver 2 inputs
     * @return string       HTML com o código do input
     */
    function formData(
        string | array $name,
        string $label = '',
        $value = '',
        null| string | array $placeholder = null,
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        array $request = [],
        bool $footer = true,
        string $separador = '',
        mixed $local = ''
    ): string {
        $attr = array_merge(['data-calendario' => 'data'], $attr);

        $mascara = is_array($name) ? ['00/00/0000', '00/00/0000'] : '00/00/0000';
        if (is_null($placeholder)) {
            $placeholder = is_array($name) ? ['00/00/0000', '00/00/0000'] : '00/00/0000';
        }

        return formInput(
            $name,
            $label,
            $value,
            $placeholder,
            $class,
            $id,
            $html,
            $icone,
            $iconeCor,
            $obrigatorio,
            $focus,
            null,
            'text',
            $attr,
            $mascara,
            $ajuda,
            true,
            true,
            false,
            false,
            $autocomplete,
            $action,
            $footer,
            $request,
            $separador,
            local: $local
        );
    }
}
if (!function_exists('formDataHora')) {
    // doc
    // exemplo
    // echo formDataHora name:datahora,label:Data_hora
    /**
     * Gera um Input com data e hora
     *
     * @param  string|array $name         Name do input, array para 2 inputs
     * @param  string       $label        Label do input
     * @param  mixed        $value        Valor do input, array para 2 valores
     * @param  string|array $placeholder  Placeholder do input, array para 2 placeholders
     * @param  string       $class        Class para o bloco geral
     * @param  string       $id           ID para o bloco geral
     * @param  string       $html         Html de complemento para o input
     * @param  string       $icone        Icone sem cor para o input
     * @param  string       $iconeCor     Icone com cor para o input
     * @param  bool|array   $obrigatorio  Se o input vai ser obrigatório, array para 2 inputs
     * @param  bool         $focus        Se vai focar o input
     * @param  array        $attr         Atributos para o input, array duplo para mandar attr para 2 inputs
     * @param  string       $ajuda        Icone de ajuda com um texto de ajuda
     * @param  bool         $autocomplete Se o input vai ser do tipo autocomplete, não pode ter 2 inputs
     * @param  string       $action       Action que será disparado via Ajax ao digitar no autocomplete
     * @param  array        $request      Caso o action precise enviar valores de outros inputs
     * @param  bool         $footer       Se o input vai ter um footer
     * @param  string       $separador    Um separador quando tiver 2 inputs
     * @return string       HTML com o código do input
     */
    function formDataHora(
        string | array $name,
        string $label = '',
        $value = '',
        string | array $placeholder = '00/00/0000 00:00:00',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        array $request = [],
        bool $footer = true,
        string $separador = '',
        mixed $local = ''
    ): string {
        $attr = array_merge(['data-calendario' => 'datahora'], $attr);
        return formInput(
            $name,
            $label,
            $value,
            $placeholder,
            $class,
            $id,
            $html,
            $icone,
            $iconeCor,
            $obrigatorio,
            $focus,
            null,
            'text',
            $attr,
            '00/00/0000 00:00:00',
            $ajuda,
            true,
            true,
            false,
            false,
            $autocomplete,
            $action,
            $footer,
            $request,
            $separador,
            local: $local
        );
    }
}
if (!function_exists('formTextarea')) {
    // doc
    // exemplo
    // echo formTextarea name:textarea,label:Textarea,placeholder:Digite_uma_mensagem
    /**
     * Gera um textarea padrão
     *
     * @param  string $name        Nome do input
     * @param  string $label       Label do input
     * @param  mixed  $value       Valor do input
     * @param  string $placeholder Placeholder do input
     * @param  string $class       Class para o box do input
     * @param  string $id          ID para o box do input
     * @param  string $html        Html para complementar o input
     * @param  bool   $obrigatorio Se o input vai ser obrigatório
     * @param  array  $attr        Lista de atributos para o input
     * @return string HTML com o código do textarea
     */
    function formTextarea(
        $name,
        string $label = '',
        $value = '',
        string $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        bool $obrigatorio = false,
        array $attr = [],
        ?int $numeroLinha = null,
        mixed $local = ''
    ): string {
        if (eLocalhost() && empty($value)) {
            $value = $local;
        }

        $id = !empty($id) ? $id : 'id_' . md5(uniqid(time()));
        $obrigatorio = $obrigatorio ? 'input_obrigatorio' : '';
        $footer = formFooter(true);

        $attrInput = [];
        if ($attr) {
            foreach ($attr as $ind => $val) {
                $attrInput[] = $ind . '="' . $val . '"';
            }
        }

        $numeroLinha = is_numeric($numeroLinha) && $numeroLinha > 1 ? $numeroLinha : 9999;

        $label = !empty($label) ? '<label for="input_' . $name . '">' . $label . '</label>' : '';

        return '
            <div class="bloco_input input_textarea ' . $class . '" id="' . $id . '">
                <textarea data-numero-linha="' . $numeroLinha . '" speelcheck="true" ' . implode(' ', $attrInput) . ' class="input_geral resize textarea_resize ' . $obrigatorio . '" name="' . $name . '" placeholder="' . $placeholder . '" id="input_' . $name . '">' . $value . '</textarea>
                ' . $html . '
                <div class="borda"></div>
                ' . $label . '
                <div class="input_icone"></div>
                ' . $footer . '
            </div>
        ';
    }
}

if (!function_exists('formCheckbox')) {
    // doc
    // exemplo
    // echo formCheckbox name:checkboxcheck,label:Texto_para_o_checkbox,check:false
    // echo formCheckbox name:checkboxuncheck,label:Texto_para_o_checkbox,check:true
    /**
     * Gera um checkbox padrão
     *
     * @param  null|string $name  Name do checkbox
     * @param  bool        $check Se o checkbox está marcado ou não
     * @param  string      $label Label do checkbox
     * @param  mixed       $value Valor do checkbox
     * @param  string      $class Class para o box do checkbox
     * @param  string      $id    ID para o box do checkbox
     * @param  string      $ajuda Icone de ajuda com um texto de ajuda
     * @param  string      $html  Html para o checkbox
     * @param  array       $attr  Atributos para o checkbox
     * @return string      HTML com o código do checkbox
     */
    function formCheckbox(
        ?string $name = null,
        string $label = '',
        bool $check = false,
        $value = '',
        string $class = '',
        string $id = '',
        string $ajuda = '',
        string $html = '',
        array $attr = []
    ): string {
        $checkHtml = $check ? 'checked' : '';
        $id = !empty($id) ? $id : 'id_' . md5(uniqid(time()));

        $ajudaHtml = '';
        if ($ajuda) {
            $ajudaHtml = '<div class="input_ajuda" data-ajuda="' . $ajuda . '">?</div>';
            $classBloco[] = 'bloco_ajuda';
        }

        $nameHtml = !empty($name) ? 'name="' . $name . '"' : '';
        $inputId = !empty($name) && preg_match("/\[\]$/", $name) || empty($name) ? md5(uniqid(time())) : $name;
        $labelHtml = '<label for="input_' . $inputId . '">' . $label . $ajudaHtml . '</label>';

        $attrInput = [];
        if ($attr) {
            foreach ($attr as $ind => $val) {
                $attrInput = $ind . '"' . $val . '"';
            }
        }

        return '
            <div class="input_checkbox ' . $class . '" id="' . $id . '">
                ' . $html . '
                <input type="checkbox" ' . $nameHtml . ' ' . implode(' ', $attrInput) . ' ' . $checkHtml . ' id="input_' . $inputId . '" value="' . $value . '">
                ' . $labelHtml . '
            </div>
        ';
    }
}

if (!function_exists('formSwitch')) {
    // doc
    // exemplo
    // echo formSwitch name:switchcheck,label:Texto_para_o_switch,check:true
    // echo formSwitch name:switchuncheck,label:Texto_para_o_switch,check:false
    /**
     * Gera um botão de switch padrão
     *
     * @param  string $name  Name do switch
     * @param  string $label Label do switch
     * @param  bool   $check Se o switch está ativo ou não
     * @param  string $value Valor do checkbox
     * @param  string $class Class para o box do checkbox
     * @param  string $id    ID para o box do checkbox
     * @param  string $ajuda Icone de ajuda com um texto de ajuda
     * @param  string $html  Html para o checkbox
     * @param  array  $attr  Atributos para o checkbox
     * @return string HTML com o código do botão
     */
    function formSwitch(
        string $name,
        string $label,
        bool $check = false,
        ?string $value = '',
        string $class = '',
        string $id = '',
        string $ajuda = '',
        string $html = '',
        array $attr = []
    ): string {
        $id = !empty($id) ? $id : 'id_' . md5(uniqid(time()));
        $value = true === $value || 'sim' == $value || 1 == $value ? 'sim' : 'nao';
        $checkHtml = $value == 'sim' || $check ? 'checked' : '';

        $ajudaHtml = '';
        if ($ajuda) {
            $ajudaHtml = '<div class="input_ajuda" data-ajuda="' . $ajuda . '">?</div>';
            $classBloco[] = 'bloco_ajuda';
        }

        $label = '<label for="input_' . $name . '"><p>' . $label . '</p>' . $ajudaHtml . '<span></span></label>';

        $attrInput = [];
        if ($attr) {
            foreach ($attr as $ind => $val) {
                $attrInput = $ind . '"' . $val . '"';
            }
        }

        return '
            <div class="bloco_switch ' . $class . '" id="' . $id . '">
                ' . $html . '
                <input type="checkbox" name="' . $name . '" ' . implode(' ', $attrInput) . ' ' . $checkHtml . ' id="input_' . $name . '" value="' . $value . '">
                ' . $label . '
            </div>
        ';
    }
}

if (!function_exists('formCor')) {
    // doc
    // exemplo
    // echo formCor name:cor,label:Escolha_uma_cor
    /**
     * Gera um bloco de cor padrão
     *
     * @param  string $name  Name do input
     * @param  array  $label Label para o bloco
     * @param  string $value Valor do input. Ex: #FF0000
     * @param  string $class Class para o box do checkbox
     * @param  string $id    ID para o box do checkbox
     * @return string HTML com o código do bloco de cor
     */
    function formCor(
        string $name,
        string $label = '',
        string $value = '',
        string $class = '',
        string $id = '',
        mixed $local = ''
    ) {
        if (eLocalhost() && empty($value)) {
            $value = $local;
        }
        $id = !empty($id) ? $id : 'id_' . md5(uniqid(time()));
        $label = !empty($label) ? '<label for="input_' . $name . '_texto">' . $label . '</label>' : '';

        return '
            <div class="input_cor ' . $class . '" id="' . $id . '">
                <input type="hidden" name="' . $name . '" id="input_' . $name . '" value="' . $value . '">
                <div class="input_cor_conteudo">
                    ' . $label . '
                    <div class="input_cor_bg" style="background-color: ' . $value . '">
                    </div>
                    <div class="input_cor_icone">' . iconeCor() . '</div>
                </div>
            </div>
        ';
    }
}
if (!function_exists('formTag')) {
    // doc
    // exemplo
    // echo formTag name:tag,label:Lista_de_tag
    // echo formTag name:tagespaco,label:Lista_de_tag_com_espaco,espaco:true
    // echo formTag name:tagurl,label:Lista_de_url,tipo:url
    /**
     * Gera um input de tag ou lista de urls
     *
     * @param  string $name        Name do input
     * @param  array  $label       Label para o bloco
     * @param  string $value       Valor da tag podendo ser um array ou json
     * @param  string $placeholder Placeholder do input
     * @param  string $class       Class para o box do checkbox
     * @param  string $id          ID para o box do checkbox
     * @param  string $tipo        Se vai ser do tipo tag ou url
     * @param  bool   $focus       Se vai focar o input
     * @param  bool   $espaco      Se vai adicionar a tag após um espaço
     * @return string HTML com o código da tag
     */
    function formTag(
        string $name,
        string $label = '',
        string|array $value = '',
        string $placeholder = '',
        string $class = '',
        string $id = '',
        string $tipo = 'tag',
        bool $focus = false,
        bool $espaco = false,
        string|array $local = ''
    ) {
        if (eLocalhost() && empty($value)) {
            $value = $local;
        }
        $input = formInput(
            name: '',
            label: $label,
            placeholder: $placeholder,
            focus: $focus = false,
            attr: ['autocomplete' => 'off']
        );

        $lista = '';
        $value = is_array($value) ? $value : jsonDecode($value, array: true);
        if ($value) {
            foreach ($value as $item) {
                $lista .= '<div class="fw_form_tag_item" data-item="' . $item . '"><span>' . $item . '</span><div class="fw_form_tag_remover" data-ajuda="Clique duas vezes no x para remover"><svg height="8" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 50 50"  xml:space="preserve"><path d="M28.9,25L49.2,4.7c1.1-1.1,1.1-2.8,0-3.9c-1.1-1.1-2.8-1.1-3.9,0L25,21.1L4.7,0.8c-1.1-1.1-2.8-1.1-3.9,0s-1.1,2.8,0,3.9 L21.1,25L0.8,45.3c-1.1,1.1-1.1,2.8,0,3.9C1.4,49.8,2,50,2.8,50s1.4-0.3,1.9-0.8L25,28.9l20.3,20.3c0.6,0.6,1.2,0.8,1.9,0.8 c0.7,0,1.4-0.3,1.9-0.8c1.1-1.1,1.1-2.8,0-3.9L28.9,25z"/></svg></div></div>';
            }
        }
        $removeClass = 'fw_form_tag_hide';
        if (!empty($lista)) {
            $removeClass = '';
        }

        $focus = $focus ? 'focus' : '';
        $class = !empty($class) ? $class : '';
        $id = !empty($id) ? 'id="' . $id . '"' : '';
        $espaco = $espaco ? 'sim' : 'nao';
        $tipo = in_array($tipo, ['tag', 'url']) ? $tipo : 'tag';

        return '
            <div data-name="' . $name . '" data-tipo="' . $tipo . '" data-espaco="' . $espaco . '" class="fw_form fw_form_tag fw_form_tag_' . $tipo . $class . '" ' . $id . '>
                ' . $input . '
                <div class="fw_form_tag_remover_todos ' . $removeClass . '">Remover todos</div>
                <div class="fw_form_tag_lista">
                    ' . $lista . '
                </div>
            </div>
        ';
    }
}

if (!function_exists('formEditor')) {
    /**
     * Gera um editor de texto padrão
     *
     * @param  string $name             Name do editor
     * @param  string $tipo             Tipo do editor que pode ser classico ou balao, balao como padrão
     * @param  string $label            Label do editor
     * @param  string $placeholder      Placeholder do editor
     * @param  string $value            Valor inicial do editor
     * @param  string $diretorioImagem  Diretório para as imagens
     * @param  string $diretorioArquivo Diretório para os arquivos
     * @param  string $bar              Campos que terão na barra podendo ser: heading, bold, italic, underline, Strikethrough, fontColor, fontBackgroundColor, alignment, link, removeFormat, fwimage, fwfile, mediaEmbed, insertTable, codeBlock, horizontalLine, blockQuote, indent, outdent, numberedList e bulletedList - Passar valores separador por espaço. Ex.: bold italic | fontColor
     * @param  string $barBalao         Campos que terão no balão podendo ser os mesmos do $bar
     * @param  string $id               ID para o bloco geral
     * @param  string $class            Class para o bloco geral
     * @param  bool   $obrigatorio      Se o editor é obrigatório
     * @param  bool   $footer           Se o editor vai ter um footer
     * @return string HTML com o código do editor
     */
    function formEditor(
        string $name,
        string $tipo = '',
        string $label = '',
        string $placeholder = '',
        string $value = '',
        string $diretorioImagem = '',
        string $diretorioArquivo = '',
        string $bar = null,
        ?string $barBalao = null,
        string $id = '',
        string $class = '',
        bool $obrigatorio = false,
        bool $footer = true,
        mixed $local = ''
    ): string {
        if (eLocalhost() && empty($value)) {
            $value = $local;
        }
        $labelHtml = !empty($label) ? '<label class="editor_label">' . $label . '</label>' : '';

        $obrigatorio = !empty($obrigatorio) ? 'input_obrigatorio' : '';
        $id = !empty($id) ? $id : 'id_' . md5(uniqid(time()));

        $footerHtml = '';
        if ($footer) {
            $footerHtml = formFooter(true);
        }

        $bar = is_null($bar) ? 'heading,|,bold,italic,underline,Strikethrough,FwDestaque,|,fontColor,fontBackgroundColor,|,alignment,|,link,removeFormat,|,fwImagem,fwArquivo,mediaEmbed,|,insertTable,codeBlock,|,horizontalLine,blockQuote,FwObservacao,|,indent,outdent,numberedList,bulletedList' : str_replace(' ', ',', trim($bar));
        $barBalao = is_null($barBalao) ? 'bold,italic,underline,Strikethrough,FwDestaque,|,fontColor,fontBackgroundColor,|,link,removeFormat' : str_replace(' ', ',', trim($barBalao));

        $classeEditor = '';
        if ($tipo == 'classico') {
            $tipo = 'classico';
        } else {
            $classeEditor = 'ck-content';
            $tipo = 'balao';
        }

        $classSemLabel = '';
        if (empty($labelHtml)) {
            $classSemLabel = 'bloco_ckeditor_sem_label';
        }

        $inputId = 'input_' . $name;

        return '
            <div class="bloco_editor ' . $classSemLabel . ' bloco_ckeditor bloco_ckeditor_' . $tipo . ' ' . $class . '" id="' . $id . '">
                ' . $labelHtml . '
                <div class="centralizar">
                    <div
                        class="fw_ckeditor ' . $classeEditor . '"
                        data-ckeditor-input="#' . $inputId . '"
                        data-ckeditor-tipo="' . $tipo . '"
                        data-ckeditor-placeholder="' . $placeholder . '"
                        data-ckeditor-bar="' . $bar . '"
                        data-ckeditor-bar-balao="' . $barBalao . '"
                        data-ckeditor-diretorio-imagem="' . $diretorioImagem . '"
                        data-ckeditor-diretorio-arquivo="' . $diretorioArquivo . '"
                    >
                        ' . $value . '
                    </div>
                </div>
                ' . $footerHtml . '
                <textarea id="' . $inputId . '" name="' . $name . '">' . $value . '</textarea>
            </div>
        ';
    }
}

if (!function_exists('formImagem')) {
    /**
     * Gera um bloco de imagem
     *
     * @param  string|array $name        Name do input
     * @param  string       $diretorio   Diretório da imagem
     * @param  null|string  $value       Valor do input
     * @param  null|string  $class       Class para o bloco geral
     * @param  null|string  $id          ID para o bloco geral
     * @param  bool|array   $obrigatorio Se o input vai ser obrigatório
     * @param  string       $tipo        Tipo do bloco da imagem podendo ser quadrado ou redondo
     * @param  int          $height      Altura em pixel do bloco de imagem
     * @return string       HTML com o código do bloco
     */
    function formImagem(
        string $name,
        string $diretorio,
        ?string $value = null,
        ?string $class = null,
        ?string $id = null,
        bool $obrigatorio = false,
        string $tipo = 'quadrado',
        int $height = 200
    ) {
        $blocoId = empty($id) ? 'id_' . md5(uniqid(time())) : $id;
        $blocoClass = empty($class) ? '' : $class;
        $inputId = 'input_' . $name;

        $imagem = '';
        $imagemCss = '';
        $botaoDisplay = 'fw_imagem_hide';
        $iconeDisplay = '';
        $attrGaleria = '';

        if (!empty($value)) {
            $blocoClass .= ' fw_form_imagem_galeria';
            $value = validarUrl($value) ? arquivoPrivadoId($value) : $value;
            $imagem = arquivoPrivado($value);
            $imagemCss = 'style="background-image: url(' . $imagem . ')"';
            $botaoDisplay = '';
            $iconeDisplay = 'fw_imagem_hide';
            $attrGaleria = 'data-galeria-imagem="' . $imagem . '"';
        }

        if ($obrigatorio) {
            $blocoClass .= ' fw_form_input_obrigatorio';
        }

        $widthFinal = '100%';
        $heightFinal = ($height + 20) . 'px';
        if ($tipo == 'redondo') {
            $widthFinal = $height . 'px';
            $heightFinal = $height . 'px';
        }

        $tipo = in_array($tipo, ['redondo', 'quadrado']) ? $tipo : 'quadrado';
        $blocoClass .= ' fw_form_tipo_' . $tipo;

        return '
            <div class="fw_form fw_form_imagem ' . $blocoClass . '" id="' . $blocoId . '" ' . $attrGaleria . ' data-diretorio="' . $diretorio . '">
                <input type="hidden" name="' . $name . '" id="' . $inputId . '" value="' . $value . '">
                <div class="fw_imagem_conteudo" style="width: ' . $widthFinal . '; height: ' . $heightFinal . '">
                    <div class="fw_imagem_icone ' . $iconeDisplay . '"><svg height="80" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 30" style="enable-background:new 0 0 40 30;" xml:space="preserve"><g transform="translate(0,-952.36218)"><path class="st0" d="M2.7,952.4c-1.5,0-2.7,1.2-2.7,2.6v24.7c0,1.5,1.2,2.6,2.7,2.6h34.7c1.5,0,2.7-1.2,2.7-2.6V955 c0-1.5-1.2-2.6-2.7-2.6H2.7z M2.7,954.1h34.7c0.5,0,0.9,0.4,0.9,0.9v18.5l-7.4-5.9c-0.3-0.2-0.7-0.3-1.1,0l-6.6,4.5l-8.8-7.1 c-0.2-0.1-0.4-0.2-0.7-0.2c-0.1,0-0.3,0.1-0.4,0.2l-11.5,7.9V955C1.8,954.5,2.2,954.1,2.7,954.1L2.7,954.1z M23.1,958.5 c-2,0-3.6,1.6-3.6,3.5s1.6,3.5,3.6,3.5s3.6-1.6,3.6-3.5S25.1,958.5,23.1,958.5z M23.1,960.3c1,0,1.8,0.8,1.8,1.8 c0,1-0.8,1.8-1.8,1.8c-1,0-1.8-0.8-1.8-1.8C21.3,961.1,22.1,960.3,23.1,960.3z M13.7,966.7l8.8,7.1c0.3,0.2,0.7,0.3,1.1,0l6.6-4.5 l8.1,6.4v4c0,0.5-0.4,0.9-0.9,0.9H2.7c-0.5,0-0.9-0.4-0.9-0.9v-4.8L13.7,966.7L13.7,966.7z"/></g></svg></div>
                    <figure class="fw_imagem_figure" ' . $imagemCss . '></figure>
                </div>
                <div class="fw_imagem_controle">
                    <div class="fw_imagem_icone fw_imagem_upload" data-ajuda="Enviar nova imagem">
                        <svg height="15" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34 40" style="enable-background:new 0 0 34 40;" xml:space="preserve"><path d="M18.3,38.7c0-3.1,0-6.2,0-9.3c0-4.9,0-9.9,0-14.8c0-1.1,0-2.2,0-3.4c0-0.7-0.6-1.4-1.3-1.3c-0.7,0-1.3,0.6-1.3,1.3 c0,3.1,0,6.2,0,9.3c0,4.9,0,9.9,0,14.8c0,1.1,0,2.2,0,3.4c0,0.7,0.6,1.4,1.3,1.3C17.7,40,18.3,39.4,18.3,38.7L18.3,38.7z"/><path d="M27.9,21.6c-1.1-1.4-2.3-2.7-3.4-4.1c-1.8-2.2-3.6-4.3-5.4-6.5c-0.4-0.5-0.8-1-1.2-1.5c-0.4-0.5-1.4-0.5-1.9,0 c-1.1,1.4-2.3,2.7-3.4,4.1c-1.8,2.2-3.6,4.3-5.4,6.5c-0.4,0.5-0.8,1-1.2,1.5c-0.5,0.6-0.5,1.4,0,1.9c0.5,0.5,1.4,0.6,1.9,0 c1.1-1.4,2.3-2.7,3.4-4.1c1.8-2.2,3.6-4.3,5.4-6.5c0.4-0.5,0.8-1,1.2-1.5c-0.6,0-1.2,0-1.9,0c1.1,1.4,2.3,2.7,3.4,4.1 c1.8,2.2,3.6,4.3,5.4,6.5c0.4,0.5,0.8,1,1.2,1.5c0.5,0.6,1.4,0.5,1.9,0C28.4,22.9,28.4,22.2,27.9,21.6L27.9,21.6z"/><path d="M32.7,0c-1,0-2.1,0-3.1,0c-2.5,0-5,0-7.5,0c-3,0-6,0-9.1,0c-2.6,0-5.2,0-7.8,0C3.9,0,2.6,0,1.4,0c0,0,0,0-0.1,0 C0.6,0,0,0.6,0,1.4s0.6,1.3,1.3,1.3c1,0,2.1,0,3.1,0c2.5,0,5,0,7.5,0c3,0,6,0,9.1,0c2.6,0,5.2,0,7.8,0c1.3,0,2.5,0,3.8,0 c0,0,0,0,0.1,0c0.7,0,1.3-0.6,1.3-1.3S33.4,0,32.7,0L32.7,0z"/></svg>
                    </div>
                    <div class="fw_imagem_linha"></div>
                    <div class="fw_imagem_icone ' . $botaoDisplay . ' fw_imagem_visualizar" data-ajuda="Visualizar Imagem"><svg height="12" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:inkscape="https://www.inkscape.org/namespaces/inkscape" xmlns:rdf="https://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="https://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg></div>
                    <div class="fw_imagem_icone ' . $botaoDisplay . ' fw_imagem_remover" data-ajuda="Deletar Imagem"><svg height="19" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 48 48" x="0px" y="0px"><g data-name="Application, Delete"><path d="M13,37a4,4,0,0,0,4,4H31a4,4,0,0,0,4-4V16H13Zm2-19H33V37a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2Zm7,16H20V23h2Zm6,0H26V23h2Zm3.41-23-4-4H20.59l-4,4H9v2H39V11Zm-10-2h5.18l2,2H19.41Z"/></g></svg></div>
                </div>
            </div>
        ';
    }
}
if (!function_exists('formArquivoLista')) {
    /**
     * Gera um bloco de arquivos em lista
     *
     * @param  string|array $name        Name do input
     * @param  string       $diretorio   Diretório do arquivo
     * @param  array        $value       Valor do input
     * @param  null|string  $class       Class para o bloco geral
     * @param  null|string  $id          ID para o bloco geral
     * @param  bool|array   $obrigatorio Se o input vai ser obrigatório
     * @return string       HTML com o código do bloco
     */
    function formArquivoLista(
        string $name,
        string $diretorio,
        array $value = [],
        ?string $class = null,
        ?string $id = null,
        bool $obrigatorio = false
    ) {
        $blocoId = empty($id) ? 'id_' . md5(uniqid(time())) : $id;
        $blocoClass = empty($class) ? '' : $class;
        $arquivoListaHtml = '';
        foreach ($value as $id) {
            $arquivo = arquivoPrivadoDado($id);
            if (!$arquivo) {
                continue;
            }
            $eUmaImagem = in_array($arquivo->extensao, ['jpg', 'jpeg', 'png', 'gif', 'svg']);
            $arquivoDownloadHtml = '';
            if (!$eUmaImagem) {
                $arquivoDownloadHtml = '
                <a class="fw_form_arquivo_lista_icone fw_form_arquivo_lista_download" href="https://docs.google.com/viewer?url=' . $arquivo->link . '" target="_blank" rel="noopener noreferrer">
                    <svg height="12" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:inkscape="https://www.inkscape.org/namespaces/inkscape" xmlns:rdf="https://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="https://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg>
                </a>
            ';
            } else {
                $arquivoDownloadHtml = '
                    <i class="fw_form_arquivo_lista_icone fw_form_arquivo_lista_download fw_imagem_visualizar">
                        <svg height="12" xmlns:cc="hqttp://creativecommons.org/ns#" xmlns:dc="https://purl.org/dc/elements/1.1/" xmlns:inkscape="https://www.inkscape.org/namespaces/inkscape" xmlns:rdf="https://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:sodipodi="https://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 40 29.3" style="enable-background:new 0 0 40 29.3;" xml:space="preserve"><g transform="translate(0,-288.53333)"><path d="M20,288.5c-13.3,0-19.3,12.3-19.6,12.9c-0.6,1.1-0.6,2.5,0,3.6c0.3,0.6,6.3,12.9,19.6,12.9s19.3-12.3,19.6-12.9 c0.6-1.1,0.6-2.5,0-3.6C39.3,300.8,33.3,288.5,20,288.5z M20,291.2c11.6,0,16.8,10.6,17.2,11.4c0.2,0.4,0.2,0.8,0,1.2 c-0.4,0.8-5.6,11.4-17.2,11.4S3.2,304.6,2.8,303.8c-0.2-0.4-0.2-0.8,0-1.2C3.2,301.7,8.4,291.2,20,291.2z"/><path d="M20,293.9c-5.1,0-9.3,4.2-9.3,9.3s4.2,9.3,9.3,9.3s9.3-4.2,9.3-9.3S25.1,293.9,20,293.9z M20,296.5c3.7,0,6.7,3,6.7,6.7 s-3,6.7-6.7,6.7s-6.7-3-6.7-6.7S16.3,296.5,20,296.5z"/></g></svg>
                    </i>
                ';
            }

            $figureBg = $eUmaImagem ? 'style="background-image: url(' . $arquivo->link . ')"' : '';
            $figureExtensaoHtml = !$eUmaImagem ? '<p>' . $arquivo->extensao . '</p>' : '';

            $attrGaleria = '';
            $classGaleria = '';
            if ($eUmaImagem) {
                $classGaleria = 'fw_form_imagem_galeria';
                $attrGaleria = 'data-galeria-imagem="' . $arquivo->link . '"';
            }

            $arquivoListaHtml .= '
                <div class="fw_form_arquivo_lista_arquivo fw_arquivo_' . $id . ' ' . $classGaleria . '" ' . $attrGaleria . '>
                    <input type="hidden" name="' . $name . '[]" value="' . $id . '">
                    <figure ' . $figureBg . '>' . $figureExtensaoHtml . '</figure>
                    ' . $arquivoDownloadHtml . '
                    <i class="fw_form_arquivo_lista_icone fw_form_arquivo_lista_remover">
                        <svg height="19" xmlns="https://www.w3.org/2000/svg" viewBox="0 0 48 48" x="0px" y="0px"><g data-name="Application, Delete"><path d="M13,37a4,4,0,0,0,4,4H31a4,4,0,0,0,4-4V16H13Zm2-19H33V37a2,2,0,0,1-2,2H17a2,2,0,0,1-2-2Zm7,16H20V23h2Zm6,0H26V23h2Zm3.41-23-4-4H20.59l-4,4H9v2H39V11Zm-10-2h5.18l2,2H19.41Z"/></g></svg>
                    </i>
                    <p class="fw_form_arquivo_lista_arquivo_nome fw_arquivo_nome_' . $id . '">' . $arquivo->nome . '</p>
                </div>
            ';
        }
        $classZero = !empty($arquivoListaHtml) ? 'fw_arquivo_lista_hide' : '';

        if ($obrigatorio) {
            $blocoClass .= ' fw_form_input_obrigatorio';
        }

        return '
            <div class="fw_form fw_form_arquivo_lista ' . $blocoClass . '" id="' . $blocoId . '" data-name="' . $name . '" data-diretorio="' . $diretorio . '">
                <i class="fw_form_arquivo_lista_icone fw_form_arquivo_lista_upload">
                    <svg height="15" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 34 40" style="enable-background:new 0 0 34 40;" xml:space="preserve"><path d="M18.3,38.7c0-3.1,0-6.2,0-9.3c0-4.9,0-9.9,0-14.8c0-1.1,0-2.2,0-3.4c0-0.7-0.6-1.4-1.3-1.3c-0.7,0-1.3,0.6-1.3,1.3 c0,3.1,0,6.2,0,9.3c0,4.9,0,9.9,0,14.8c0,1.1,0,2.2,0,3.4c0,0.7,0.6,1.4,1.3,1.3C17.7,40,18.3,39.4,18.3,38.7L18.3,38.7z"/><path d="M27.9,21.6c-1.1-1.4-2.3-2.7-3.4-4.1c-1.8-2.2-3.6-4.3-5.4-6.5c-0.4-0.5-0.8-1-1.2-1.5c-0.4-0.5-1.4-0.5-1.9,0 c-1.1,1.4-2.3,2.7-3.4,4.1c-1.8,2.2-3.6,4.3-5.4,6.5c-0.4,0.5-0.8,1-1.2,1.5c-0.5,0.6-0.5,1.4,0,1.9c0.5,0.5,1.4,0.6,1.9,0 c1.1-1.4,2.3-2.7,3.4-4.1c1.8-2.2,3.6-4.3,5.4-6.5c0.4-0.5,0.8-1,1.2-1.5c-0.6,0-1.2,0-1.9,0c1.1,1.4,2.3,2.7,3.4,4.1 c1.8,2.2,3.6,4.3,5.4,6.5c0.4,0.5,0.8,1,1.2,1.5c0.5,0.6,1.4,0.5,1.9,0C28.4,22.9,28.4,22.2,27.9,21.6L27.9,21.6z"/><path d="M32.7,0c-1,0-2.1,0-3.1,0c-2.5,0-5,0-7.5,0c-3,0-6,0-9.1,0c-2.6,0-5.2,0-7.8,0C3.9,0,2.6,0,1.4,0c0,0,0,0-0.1,0 C0.6,0,0,0.6,0,1.4s0.6,1.3,1.3,1.3c1,0,2.1,0,3.1,0c2.5,0,5,0,7.5,0c3,0,6,0,9.1,0c2.6,0,5.2,0,7.8,0c1.3,0,2.5,0,3.8,0 c0,0,0,0,0.1,0c0.7,0,1.3-0.6,1.3-1.3S33.4,0,32.7,0L32.7,0z"/></svg>
                </i>
                <div class="fw_form_arquivo_lista_lista">
                    <div class="fw_form_arquivo_lista_zero ' . $classZero . '">Sem arquivos no momento</div>
                    ' . $arquivoListaHtml . '
                </div>
            </div>
        ';
    }

    function formDinheiro(
        string|array $name,
        string $label = '',
        $value = '',
        string|array $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        string $icone = '',
        string $iconeCor = '',
        bool|array $obrigatorio = false,
        bool $focus = false,
        null|int|array $contador = null,
        array $attr = [],
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
    ): string {
        $mascara = 'dinheiro';
        if (is_array($name)) {
            $mascara = [];
            foreach ($name as $val) {
                $mascara[] = 'dinheiro';
            }
        }
        return formInput(
            name: $name,
            label: $label,
            value: $value,
            placeholder: $placeholder,
            class: $class,
            id: $id,
            html: $html,
            icone: $icone,
            iconeCor: $iconeCor,
            obrigatorio: $obrigatorio,
            focus: $focus,
            contador: $contador,
            type: 'text',
            attr: $attr,
            mascara: $mascara,
            ajuda: $ajuda,
            numero: true,
            autocomplete: $autocomplete,
            action: $action,
            footer: $footer,
            request: $request,
            separador: $separador,
            maximo: $maximo,
        );
    }
}
