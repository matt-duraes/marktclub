<?php

namespace PainelConfig;

use Helpers\ListaHelper;

final class Add
{
    private int $coluna;
    private int $fieldset;

    private string|int $numeroColuna;

    private string $titulo = '';
    private array $html = [];
    private array $camposAceitos = [];
    private array $camposObrigatorio = [];
    private string $css = '';
    private string $js = '';
    private string $link = '';

    public function __construct(
        private string $app
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

    public function fieldset(string $titulo = '', ?\Closure $callback = null)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->fieldset++;
        $this->titulo = $titulo;
        call_user_func($callback);
        return $this;
    }

    public function fieldsetCheckbox(string $titulo = null, ?\Closure $callback = null, ?string $todos = null, ?bool $mais = null, ?bool $margin = null)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->fieldset++;

        $classTodos = !empty($todos) ? 'bloco_checkbox_marcar_todos' : '';
        $classMais = $mais ? 'bloco_checkbox_mais' : '';
        $classMargin = $margin ? 'bloco_fieldset_margin' : '';

        $this->html('<div class="bloco_checkbox_geral ' . $classTodos . ' ' . $classMais . ' ' . $classMargin . '">');

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

    public function html(string $html)
    {
        $this->html[$this->coluna][$this->fieldset]['lista'][] = [
            'funcao' => 'html',
            'html' => $html
        ];
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
        $id = !empty($id) ? 'class="' . $id . '"' : '';

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
        string $formatar = ''
    ) {
        return $this->adicionarNovoInput([
            'funcao' => 'input',
            'name' => $name,
            'label' => $label,
            'placeholder' => $placeholder,
            'class' => $class,
            'id' => $id,
            'html' => $html,
            'icone' => $icone,
            'iconeCor' => $iconeCor,
            'obrigatorio' => $obrigatorio,
            'focus' => $focus,
            'contador' => $contador,
            'type' => $type,
            'attr' => $attr,
            'mascara' => $mascara,
            'ajuda' => $ajuda,
            'numero' => $numero,
            'data' => $data,
            'senha' => $senha,
            'url' => $url,
            'autocomplete' => $autocomplete,
            'action' => $action,
            'footer' => $footer,
            'request' => $request,
            'separador' => $separador,
            'maximo' => $maximo,
            'formatar' => $formatar
        ]);
    }

    public function telefone(
        $name,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(name: $name, label: $label, placeholder: $placeholder, obrigatorio: $obrigatorio, mascara: 'telefone', numero: 1, formatar: 'telefone');
        return $this;
    }

    public function email(
        $name,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(name: $name, label: $label, obrigatorio: $obrigatorio, placeholder: $placeholder, type: 'email');
        return $this;
    }

    public function cpf(
        $name,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(name: $name, label: $label, placeholder: $placeholder, obrigatorio: $obrigatorio, mascara: '000.000.000-00', numero: 1, formatar: 'cpf');
        return $this;
    }

    public function cep(
        $name,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(name: $name, label: $label, placeholder: $placeholder, obrigatorio: $obrigatorio, mascara: '00000-000', numero: 1, formatar: 'cep');
        return $this;
    }

    public function numero(
        $name,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(name: $name, label: $label, obrigatorio: $obrigatorio, placeholder: $placeholder, numero: 1);
        return $this;
    }

    public function data(
        $name,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(name: $name, label: $label, obrigatorio: $obrigatorio, placeholder: $placeholder, attr: ['data-calendario' => 'data'], mascara: '00/00/0000', numero: 1, data: 1, formatar: 'data');
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
        return $this->input(name: $name, label: $label, placeholder: $placeholder, class: $class, id: $id, html: $html, icone: $icone, iconeCor: $iconeCor, obrigatorio: $obrigatorio, focus: $focus, contador: $contador, type: 'password', attr: $attr, mascara: '', ajuda: $ajuda, senha: $senha, footer: $footer);
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
        string $change = ''
    ) {
        if (is_string($lista) && !in_array($lista, ['genero', 'estado_civil', 'estado'])) {
            mensagemErro('Erro', 'Você deve passar um valor de lista aceito.');
        }
        if (is_string($lista) && $lista == 'genero') {
            $lista = (new ListaHelper)->add('', 'Escolha um gênero')->genero()->r();
        } else if (is_string($lista) && $lista == 'estado_civil') {
            $lista = (new ListaHelper)->add('', 'Escolha um Estado Civil')->estadoCivil()->r();
        } else if (is_string($lista) && $lista == 'estado') {
            $lista = (new ListaHelper)->add('', 'Escolha um estado')->estado()->r();
        }

        return $this->adicionarNovoInput([
            'funcao' => 'select',
            'name' => $name,
            'lista' => $lista,
            'label' => $label,
            'placeholder' => $placeholder,
            'id' => $id,
            'class' => $class,
            'obrigatorio' => $obrigatorio,
            'footer' => $footer,
            'change' => $change
        ]);
    }

    public function textarea(
        $name,
        string $label = '',
        bool $enter = true,
        string $placeholder = '',
        string $class = '',
        string $id = '',
        string $html = '',
        bool $obrigatorio = false,
        array $attr = []
    ) {
        return $this->adicionarNovoInput([
            'funcao' => 'textarea',
            'name' => $name,
            'label' => $label,
            'enter' => $enter,
            'placeholder' => $placeholder,
            'class' => $class,
            'id' => $id,
            'html' => $html,
            'obrigatorio' => $obrigatorio,
            'attr' => $attr,
        ]);
    }

    public function switch(
        $name,
        string $label,
        string $class = '',
        string $id = '',
        string $ajuda = '',
        string $html = '',
        array $attr = []
    ) {
        return $this->adicionarNovoInput([
            'funcao' => 'switch',
            'name' => $name,
            'label' => $label,
            'class' => $class,
            'id' => $id,
            'ajuda' => $ajuda,
            'html' => $html,
            'attr' => $attr,
        ]);
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
        array $attr = []
    ) {
        return $this->adicionarNovoInput([
            'funcao' => 'checkbox',
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'check' => $check,
            'class' => $class,
            'id' => $id,
            'ajuda' => $ajuda,
            'html' => $html,
            'attr' => $attr,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function adicionarNovoInput($dado)
    {
        if (!$this->campoAceito($dado['name'])) {
            return $this;
        }
        $this->setarTitulo();
        $this->setarColuna();

        if (!in_array($dado['funcao'], ['checkbox', 'switch'])) {
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
    private function setarColuna()
    {
        if (!empty($this->numeroColuna)) {
            $this->html[$this->coluna]['coluna'] = $this->numeroColuna;
            $this->numeroColuna = 0;
        }
    }

    private function campoAceito($name)
    {
        if (!empty($this->camposAceitos) && !in_array($name, $this->camposAceitos)) {
            return false;
        }
        return true;
    }

    private function erroCallback()
    {
        mensagemStatus(500, localhost: 'Você deve passar uma callback.');
    }
}
