<?php

namespace PainelConfig;

use Modules\Genero;
use Helpers\ApiHelper;
use Helpers\ListaHelper;
use Modules\EstadoCivil;

final class Add
{
    private int $coluna;
    private int $fieldset;
    private string|int $numeroColuna;
    private string $titulo = '';
    private bool $abrir = false;
    private bool $row = false;
    private array $html = [];
    private array $camposAceitos = [];
    private array $camposObrigatorio = [];
    private string $css = '';
    private string|array $js = '';
    private string $link = '';

    public const TAG_TIPO_TAG = 'tag';
    public const TAG_TIPO_TEXTO = 'texto';
    public const TAG_TIPO_URL = 'url';

    public function __construct(
        private string $app,
        private ?string $acao = null
    ) {
        $this->app = $app;

        $campo = sessao('PAINEL.campo', padrao: []);
        if (is_array($campo) && array_key_exists($this->app, $campo) && $campo[$this->app]) {
            $this->camposAceitos = $campo[$this->app]['add'] ?? $campo[$this->app]['geral'] ?? [];
        }

        $obrigatorio = sessao('PAINEL.obrigatorio', padrao: []);
        $this->camposObrigatorio =
            is_array($obrigatorio) && array_key_exists($this->app, $obrigatorio) ?
            $obrigatorio[$this->app] :
            [];

        $this->coluna = -1;
        $this->fieldset = -1;
    }

    /*
    |--------------------------------------------------------------------------
    | RETORNO
    |--------------------------------------------------------------------------
    */
    public function pegarHtml()
    {
        return $this->html;
    }

    public function pegarCss()
    {
        return $this->css;
    }

    public function pegarJs()
    {
        return $this->js;
    }

    public function pegarLink()
    {
        return $this->link;
    }

    /*
    |--------------------------------------------------------------------------
    | SETAR RETORNOS
    |--------------------------------------------------------------------------
    */
    public function css(string $css)
    {
        $this->css = $css;
        return $this;
    }

    public function js(string $js)
    {
        $this->js = $js;
        return $this;
    }

    public function link(string $link)
    {
        $this->link = $link;
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | ESTRUTURA
    |--------------------------------------------------------------------------
    */
    public function coluna(string|int $coluna = '', ?\Closure $callback = null)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->coluna++;
        $this->numeroColuna = $coluna;
        $this->fieldset = -1;
        call_user_func($callback);
        return $this;
    }

    public function fieldset(string $titulo = '', ?\Closure $callback = null, bool $abrir = false, $row = false)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->fieldset++;
        $this->titulo = $titulo;
        $this->abrir = $abrir;
        $this->row = $row;
        call_user_func($callback);
        return $this;
    }

    public function endereco(string $titulo = '')
    {
        $this->fieldset(titulo: $titulo, callback: function () {
            $this
                ->cep(name: 'endereco_cep', label: 'CEP', placeholder: 'CEP', obrigatorio: true)
                ->input(name: 'endereco_logradouro', label: 'Logradouro', placeholder: 'Digite o logradouro', contador: 100, obrigatorio: true)
                ->input(name: 'endereco_numero', label: 'Número', placeholder: 'Digite o número')
                ->input(name: 'endereco_complemento', label: 'Complemento', placeholder: 'Digite um complemento')
                ->input(name: 'endereco_bairro', label: 'Bairro', placeholder: 'Digite um bairro', obrigatorio: true)
                ->select(name: 'endereco_estado', label: 'Estado', placeholder: 'Escolha um estado', lista: (new ListaHelper())->add('', 'Escolha um estado')->estado()->r(), obrigatorio: true)
                ->select(name: 'endereco_cidade', label: 'Cidade', placeholder: 'Escolha uma cidade', lista: ['' => 'Escolha um estado primeiro'], obrigatorio: true);
        }, row: true);
    }

    public function blocoCheckbox(
        string $titulo = null,
        \Closure $callback,
        string $todos = null,
        bool $mais = null,
        string $class = null,
        string $id = null
    ) {
        $html = '<h3>' . $titulo . '</h3>';

        if ($todos) {
            $class .= ' bloco_checkbox_marcar_todos';
            $id = uuid();
            $html .= '
                <div class="marcar_todas">
                    <div class="input_checkbox " id="id_' . $id . '">
                        <input type="checkbox" id="input_' . $id . '" value="">
                        <label for="input_' . $id . '">' . $todos . '</label>
                    </div>
                </div>
            ';
        }
        if ($mais) {
            $class .= ' bloco_checkbox_mais';
            $html .= '<div class="botao_mais"><span>Mostrar todos</span></div>';
        }

        $this->div(
            callback: $callback,
            class: !empty($class) ? 'bloco_checkbox_geral ' . $class : 'bloco_checkbox_geral',
            id: $id,
            htmlPre: $html
        );
        return $this;
    }

    public function fieldsetCheckbox(
        string $titulo = null,
        \Closure $callback = null,
        string $todos = null,
        bool $mais = null,
        bool $margin = null,
        string $id = null,
        string $class = null
    ) {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->fieldset++;

        $classTodos = !empty($todos) ? 'bloco_checkbox_marcar_todos' : '';
        $classMais = $mais ? 'bloco_checkbox_mais' : '';
        $classMargin = $margin ? 'bloco_fieldset_margin' : '';
        $id = !empty($id) ? ' id="' . $id . '"' : '';

        $this->html('
            <div class="bloco_checkbox_geral '
            . $class . ' ' . $classTodos . ' ' . $classMais . ' ' . $classMargin . '"' . $id . '>');

        if (!empty($titulo)) {
            $this->html('<h3>' . $titulo . '</h3>');
        }

        if (!empty($todos)) {
            $this->html('<div class="marcar_todas">' . formCheckbox(label: $todos, check: false) . '</div>');
        }

        call_user_func($callback);

        if (!empty($todos)) {
            $this->html('<div class="botao_mais"><span>Mostrar todos</span></div>');
        }
        $this->html('</div>');
    }

    /**
     * Adiciona um titulo
     *
     * @param string      $titulo
     * @param string|null $campo
     * @param string|null $acao
     * @param string|null $permissao
     */
    public function titulo(string $titulo, string $campo = null, string $acao = null, string $permissao = null)
    {
        $this->html('<h4>' . $titulo . '</h4>', $campo, $acao, $permissao);
        return $this;
    }

    /**
     * Adiciona uma margem
     *
     * @param string $tamanho
     */
    public function margem(int $tamanho)
    {
        $this->html('<div class="margem" style="margin-top: ' . $tamanho . 'px"></div>');
        return $this;
    }

    /**
     * Adiciona HTML
     *
     * @param string      $html
     * @param string|null $campo
     * @param string|null $acao
     * @param string|null $permissao
     */
    public function html(string $html, string $campo = null, string $acao = null, string $permissao = null)
    {
        if ((!empty($campo) || !empty($permissao)) && !$this->campoAceito($campo, $acao, $permissao)) {
            return $this;
        }
        $this->html[$this->coluna][$this->fieldset]['lista'][] = [
            'funcao' => 'html',
            'html'   => $html
        ];
        return $this;
    }

    public function div(
        \Closure $callback,
        string $class = null,
        string $id = null,
        array $attr = [],
        string $htmlPre = null,
        string $htmlPos = null
    ) {
        $class = !empty($class) ? 'class="' . $class . '"' : '';
        $id = !empty($id) ? 'id="' . $id . '"' : '';

        $attrLista = [];
        foreach ($attr as $ind => $val) {
            $attrLista[] = $ind . '="' . $val . '"';
        }

        $this->html('<div ' . $class . ' ' . $id . ' ' . implode(' ', $attrLista) . '>');
        if (!empty($htmlPre)) {
            $this->html($htmlPre);
        }

        call_user_func($callback);

        if (!empty($htmlPos)) {
            $this->html($htmlPos);
        }
        $this->html('</div>');
    }

    /*
    |--------------------------------------------------------------------------
    | INPUTS
    |--------------------------------------------------------------------------
    */
    public function hidden(
        string | array $name,
        string $class = '',
        string $id = '',
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'    => 'hidden',
            'name'      => $name,
            'class'     => $class,
            'id'        => $id,
            'permissao' => $permissao
        ], $acao);
    }

    public function input(
        string | array $name,
        string $label = '',
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
        array $attr = [],
        string | array $mascara = '',
        string $ajuda = '',
        bool | array $numero = false,
        bool | array $data = false,
        bool | array $senha = false,
        bool $url = false,
        bool $autocomplete = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
        string $formatar = '',
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'       => 'input',
            'name'         => $name,
            'label'        => $label,
            'placeholder'  => $placeholder,
            'class'        => $class,
            'id'           => $id,
            'html'         => $html,
            'icone'        => $icone,
            'iconeCor'     => $iconeCor,
            'obrigatorio'  => $obrigatorio,
            'focus'        => $focus,
            'contador'     => $contador,
            'type'         => $type,
            'attr'         => $attr,
            'mascara'      => $mascara,
            'ajuda'        => $ajuda,
            'numero'       => $numero,
            'data'         => $data,
            'senha'        => $senha,
            'url'          => $url,
            'autocomplete' => $autocomplete,
            'action'       => $action,
            'footer'       => $footer,
            'request'      => $request,
            'separador'    => $separador,
            'maximo'       => $maximo,
            'formatar'     => $formatar,
            'permissao'    => $permissao
        ], $acao);
    }

    public function uri(
        string | array $name,
        string $label = '',
        string | array $placeholder = '',
        string $class = '',
        string $id = '',
        string $icone = '',
        string $iconeCor = '',
        bool | array $obrigatorio = false,
        bool $focus = false,
        null | int | array $contador = null,
        array $attr = [],
        string $ajuda = '',
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
        string $formatar = '',
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'       => 'uri',
            'name'         => $name,
            'label'        => $label,
            'placeholder'  => $placeholder,
            'class'        => $class,
            'id'           => $id,
            'icone'        => $icone,
            'iconeCor'     => $iconeCor,
            'obrigatorio'  => $obrigatorio,
            'focus'        => $focus,
            'contador'     => $contador,
            'attr'         => $attr,
            'ajuda'        => $ajuda,
            'action'       => $action,
            'footer'       => $footer,
            'request'      => $request,
            'separador'    => $separador,
            'maximo'       => $maximo,
            'formatar'     => $formatar,
            'permissao'    => $permissao
        ], $acao);
    }

    public function dinheiro(
        string | array $name,
        string $label = '',
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
        string $ajuda = '',
        bool $autocomplete = false,
        string $action = '',
        bool $footer = true,
        array $request = [],
        string $separador = '',
        null|int|array $maximo = null,
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'       => 'input',
            'name'         => $name,
            'label'        => $label,
            'placeholder'  => $placeholder,
            'class'        => $class,
            'id'           => $id,
            'html'         => $html,
            'icone'        => $icone,
            'iconeCor'     => $iconeCor,
            'obrigatorio'  => $obrigatorio,
            'focus'        => $focus,
            'contador'     => $contador,
            'type'         => 'text',
            'attr'         => $attr,
            'mascara'      => 'dinheiro',
            'ajuda'        => $ajuda,
            'numero'       => true,
            'autocomplete' => $autocomplete,
            'action'       => $action,
            'footer'       => $footer,
            'request'      => $request,
            'separador'    => $separador,
            'maximo'       => $maximo,
            'formatar'     => 'dinheiro',
            'permissao'    => $permissao
        ], $acao);
    }

    public function url(
        string | array $name,
        string $label = '',
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
        string $formatar = '',
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'       => 'input',
            'name'         => $name,
            'label'        => $label,
            'placeholder'  => $placeholder,
            'class'        => $class,
            'id'           => $id,
            'html'         => $html,
            'icone'        => $icone,
            'iconeCor'     => $iconeCor,
            'obrigatorio'  => $obrigatorio,
            'focus'        => $focus,
            'contador'     => null,
            'type'         => 'url',
            'attr'         => $attr,
            'mascara'      => '',
            'ajuda'        => $ajuda,
            'numero'       => false,
            'data'         => false,
            'senha'        => false,
            'url'          => true,
            'autocomplete' => $autocomplete,
            'action'       => $action,
            'footer'       => $footer,
            'request'      => $request,
            'separador'    => '',
            'maximo'       => null,
            'formatar'     => $formatar,
            'permissao'    => $permissao
        ], $acao);
    }

    public function telefone(
        string | array $name,
        string $label = '',
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
        string $separador = ''
    ) {
        $this->input(
            $name,
            $label,
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
            formatar: 'telefone'
        );
        return $this;
    }

    public function email(
        string | array $name,
        string $label = '',
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
        string $separador = ''
    ) {
        $this->input(
            $name,
            $label,
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
            formatar: 'email'
        );
        return $this;
    }

    public function indiceValor(
        string $name,
        ?string $class = null,
        ?string $id = null,
        bool $obrigatorio = false,
        array $placeholder = [],
        ?string $acao = null,
        ?string $permissao = null,
        bool $ordem = false
    ) {
        return $this->adicionarNovoInput([
            'funcao'      => 'indiceValor',
            'name'        => $name,
            'class'       => $class,
            'id'          => $id,
            'placeholder' => $placeholder,
            'obrigatorio' => $obrigatorio,
            'ordem'       => $ordem,
            'permissao'   => $permissao
        ], $acao);
    }

    public function imagem(
        string $name,
        string $diretorio,
        ?string $class = null,
        ?string $id = null,
        bool $obrigatorio = false,
        string $tipo = 'quadrado',
        int $height = 200,
        string $label = null,
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'      => 'imagem',
            'name'        => $name,
            'diretorio'   => $diretorio,
            'class'       => $class,
            'id'          => $id,
            'label'       => $label,
            'obrigatorio' => $obrigatorio,
            'tipo'        => $tipo,
            'height'      => $height,
            'permissao'   => $permissao
        ], $acao);
    }

    public function tag(
        string $name,
        string $label = '',
        string $placeholder = '',
        string $class = '',
        string $id = '',
        string $tipo = 'tag',
        bool $focus = false,
        bool $espaco = false,
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'      => 'tag',
            'name'        => $name,
            'label'       => $label,
            'placeholder' => $placeholder,
            'class'       => $class,
            'id'          => $id,
            'tipo'        => $tipo,
            'focus'       => $focus,
            'espaco'      => $espaco,
            'permissao'   => $permissao
        ], $acao);
    }

    public function editor(
        string $name,
        string $tipo = '',
        string $label = '',
        string $placeholder = '',
        string $value = '',
        string $diretorioImagem = '',
        string $diretorioArquivo = '',
        string $bar = '',
        ?string $barBalao = null,
        string $id = '',
        string $class = '',
        bool $obrigatorio = false,
        bool $footer = true,
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'           => 'editor',
            'name'             => $name,
            'tipo'             => in_array($tipo, ['balao', 'classico']) ? $tipo : 'balao',
            'label'            => $label,
            'placeholder'      => $placeholder,
            'value'            => $value,
            'diretorioImagem'  => $diretorioImagem,
            'diretorioArquivo' => $diretorioArquivo,
            'bar'              => $bar,
            'barBalao'         => $barBalao,
            'id'               => $id,
            'class'            => $class,
            'obrigatorio'      => $obrigatorio,
            'footer'           => $footer,
            'permissao'        => $permissao
        ], $acao);
    }

    public function editorBalao(
        string $name,
        string $label = '',
        string $placeholder = '',
        string $diretorioImagem = '',
        string $diretorioArquivo = '',
        string $bar = null,
        ?string $barBalao = null,
        string $id = '',
        string $class = '',
        bool $obrigatorio = false,
        bool $footer = true,
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'           => 'editor',
            'name'             => $name,
            'tipo'             => 'balao',
            'label'            => $label,
            'placeholder'      => $placeholder,
            'diretorioImagem'  => $diretorioImagem,
            'diretorioArquivo' => $diretorioArquivo,
            'bar'              => $bar,
            'barBalao'         => $barBalao,
            'id'               => $id,
            'class'            => $class,
            'obrigatorio'      => $obrigatorio,
            'footer'           => $footer,
            'permissao'        => $permissao
        ], $acao);
    }

    public function editorClassico(
        string $name,
        string $label = '',
        string $placeholder = '',
        string $value = '',
        string $diretorioImagem = '',
        string $diretorioArquivo = '',
        string $bar = '',
        ?string $barBalao = null,
        string $id = '',
        string $class = '',
        bool $obrigatorio = false,
        bool $footer = true,
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'           => 'editor',
            'name'             => $name,
            'tipo'             => 'classico',
            'label'            => $label,
            'placeholder'      => $placeholder,
            'value'            => $value,
            'diretorioImagem'  => $diretorioImagem,
            'diretorioArquivo' => $diretorioArquivo,
            'bar'              => $bar,
            'barBalao'         => $barBalao,
            'id'               => $id,
            'class'            => $class,
            'obrigatorio'      => $obrigatorio,
            'footer'           => $footer,
            'permissao'        => $permissao
        ], $acao);
    }

    public function cpf(
        string | array $name,
        string $label = '',
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
    ) {
        $this->input(
            name: $name,
            label: $label,
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
            formatar: 'cpf'
        );
        return $this;
    }

    public function cnpj(
        string | array $name,
        string $label = '',
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
    ) {
        $this->input(
            name: $name,
            label: $label,
            placeholder: $placeholder,
            class: $class,
            id: $id,
            html: $html,
            icone: $icone,
            iconeCor: $iconeCor,
            obrigatorio: $obrigatorio,
            focus: $focus,
            attr: $attr,
            ajuda: $ajuda,
            autocomplete: $autocomplete,
            action: $action,
            footer: $footer,
            request: $request,
            separador: $separador,
            maximo: $maximo,
            mascara: '00.000.000/0000-00'
        );
        return $this;
    }

    public function arquivoLista(
        string $name,
        string $diretorio,
        ?string $class = null,
        ?string $id = null,
        bool $obrigatorio = false,
        string $acao = null,
        string $permissao = null
    ) {
        $this->adicionarNovoInput([
            'funcao'       => 'arquivoLista',
            'name'         => $name,
            'diretorio'    => $diretorio,
            'class'        => $class,
            'id'           => $id,
            'obrigatorio'  => $obrigatorio,
        ], $acao, $permissao);
        return $this;
    }

    public function cep(
        $name,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(
            name: $name,
            label: $label,
            placeholder: $placeholder,
            obrigatorio: $obrigatorio,
            mascara: '00000-000',
            numero: 1,
            formatar: 'cep'
        );
        return $this;
    }

    public function numero(
        string | array $name,
        string $label = '',
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
    ) {
        $this->input(
            name: $name,
            label: $label,
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
        );
        return $this;
    }

    public function data(
        string | array $name,
        string $label = '',
        string | array $placeholder = '00/00/0000',
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
        string $separador = ''
    ) {
        $this->input(
            $name,
            $label,
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
            '00/00/0000',
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
            formatar: 'data'
        );
        return $this;
    }

    public function dataHora(
        string | array $name,
        string $label = '',
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
        string $separador = ''
    ) {
        $this->input(
            $name,
            $label,
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
            formatar: 'datahora'
        );
        return $this;
    }

    public function senha(
        string | array $name,
        string $label = '',
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
        bool $footer = true
    ) {
        $senha = is_array($name) ? [true, true] : true;
        return $this->input(
            name: $name,
            label: $label,
            placeholder: $placeholder,
            class: $class,
            id: $id,
            html: $html,
            icone: $icone,
            iconeCor: $iconeCor,
            obrigatorio: $obrigatorio,
            focus: $focus,
            contador: $contador,
            type: 'password',
            attr: $attr,
            mascara: '',
            ajuda: $ajuda,
            senha: $senha,
            footer: $footer
        );
    }

    public function cor(
        string $name,
        string $label = '',
        string $class = '',
        string $id = '',
        string $acao = null,
        string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'      => 'cor',
            'name'        => $name,
            'label'       => $label,
            'id'          => $id,
            'class'       => $class,
            'permissao'   => $permissao
        ], $acao);
    }

    public function select(
        $name,
        string|array $lista,
        string $label = '',
        string $placeholder = '',
        string $id = '',
        string $class = '',
        bool $obrigatorio = false,
        bool $footer = true,
        string $change = '',
        string $acao = null,
        ?string $permissao = null
    ) {
        if (is_string($lista) && !in_array($lista, ['genero', 'estado_civil', 'estado', 'empresa'])) {
            mensagemErro('Erro', 'Você deve passar um valor de lista aceito.');
        }
        if (is_string($lista) && $lista == 'genero') {
            $lista = (new Genero())->select('Escolha um gênero');
        } elseif (is_string($lista) && $lista == 'estado_civil') {
            $lista = (new EstadoCivil())->select('Escolha um Estado Civil');
        } elseif (is_string($lista) && $lista == 'estado') {
            $lista = (new ListaHelper())->add('', 'Escolha um estado')->estado()->r();
        } elseif (is_string($lista) && $lista == 'empresa') {
            $lista = (new ApiHelper(token: true))
                ->json(['titulo' => 'Escolha um cliente'])
                ->get('/comercial-empresa/select')
                ->array()['dado'] ?? [];
        }

        return $this->adicionarNovoInput([
            'funcao'      => 'select',
            'name'        => $name,
            'lista'       => $lista,
            'label'       => $label,
            'placeholder' => $placeholder,
            'id'          => $id,
            'class'       => $class,
            'obrigatorio' => $obrigatorio,
            'footer'      => $footer,
            'change'      => $change,
            'permissao'   => $permissao
        ], $acao);
    }

    public function textarea(
        $name,
        string $label = '',
        string $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        bool $obrigatorio = false,
        array $attr = [],
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'      => 'textarea',
            'name'        => $name,
            'label'       => $label,
            'placeholder' => $placeholder,
            'class'       => $class,
            'id'          => $id,
            'html'        => $html,
            'obrigatorio' => $obrigatorio,
            'attr'        => $attr,
            'permissao'   => $permissao
        ], $acao);
    }

    public function switch(
        $name,
        string $label,
        string $class = '',
        string $id = '',
        string $ajuda = '',
        string $html = '',
        array $attr = [],
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'    => 'switch',
            'name'      => $name,
            'label'     => $label,
            'class'     => $class,
            'id'        => $id,
            'ajuda'     => $ajuda,
            'html'      => $html,
            'attr'      => $attr,
            'permissao' => $permissao
        ], $acao);
    }

    public function checkbox(
        ?string $name = null,
        string $label = '',
        $value = '',
        bool $check = false,
        string $class = '',
        string $id = '',
        string $ajuda = '',
        string $html = '',
        array $attr = [],
        ?string $acao = null,
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao'    => 'checkbox',
            'name'      => $name,
            'label'     => $label,
            'value'     => $value,
            'check'     => $check,
            'class'     => $class,
            'id'        => $id,
            'ajuda'     => $ajuda,
            'html'      => $html,
            'attr'      => $attr,
            'permissao' => $permissao
        ], $acao);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function adicionarNovoInput($dado, ?string $acao = null, ?string $permissao = null)
    {
        $dado['indice'] = preg_replace('/\[\]$/', '', $dado['name']);
        $dado['name'] = is_string($dado['name']) ? explode('->', $dado['name'])[0] : $dado['name'];
        $permissao = null;
        if (array_key_exists('permissao', $dado)) {
            $permissao = $dado['permissao'];
            unset($dado['permissao']);
        }
        if (!$this->campoAceito($dado['name'], $acao, $permissao)) {
            return $this;
        }
        $this->setarTitulo();
        $this->setarAbrir();
        $this->setarRow();
        $this->setarColuna();

        if (!in_array($dado['funcao'], ['cor', 'checkbox', 'switch', 'tag', 'indiceValor', 'hidden']) && !is_array($dado['name'])) {
            $dado['obrigatorio'] = $this->setarCampoObrigatorio($dado['name'], $dado['obrigatorio'] ?? false);
        }

        $this->html[$this->coluna][$this->fieldset]['lista'][] = $dado;
        return $this;
    }

    private function setarCampoObrigatorio(string $name, bool $obrigatorio): bool
    {
        if (in_array($name, $this->camposObrigatorio)) {
            return true;
        }
        return $obrigatorio;
    }

    private function setarTitulo()
    {
        if (!empty($this->titulo)) {
            $this->html[$this->coluna][$this->fieldset]['titulo'] = $this->titulo;
            $this->titulo = '';
        }
    }

    private function setarAbrir()
    {
        if (!empty($this->abrir)) {
            $this->html[$this->coluna][$this->fieldset]['abrir'] = $this->abrir;
            $this->abrir = false;
        }
    }

    private function setarRow()
    {
        if (!empty($this->row)) {
            $this->html[$this->coluna][$this->fieldset]['row'] = $this->row;
            $this->row = false;
        }
    }

    private function setarColuna()
    {
        if (!empty($this->numeroColuna)) {
            $this->html[$this->coluna]['coluna'] = $this->numeroColuna;
            $this->numeroColuna = 0;
        }
    }

    private function campoAceito($name, ?string $acao, ?string $permissao)
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        $name = preg_replace('/\[\]$/', '', $name);
        if (
            (!empty($permissao) && !in_array($permissao, $usuarioPermissao)) ||
            (!empty($this->camposAceitos) && !in_array($name, $this->camposAceitos)) ||
            (!empty($acao) && !empty($this->acao) && $acao != $this->acao)
        ) {
            return false;
        }
        return true;
    }

    private function erroCallback()
    {
        mensagemStatus(500, localhost: 'Você deve passar uma callback.');
    }
}
