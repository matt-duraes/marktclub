<?php

namespace PainelConfig;

use Helpers\ApiHelper;
use Helpers\ListaHelper;

final class Filtrar
{
    private array $html = [];
    private array $camposAceitos = [];

    private array $replace = [];

    public function __construct(
        private string $app
    ) {
        $this->app = $app;

        $campo = sessao('PAINEL.campo', padrao: []);
        if (is_array($campo) && array_key_exists($this->app, $campo) && $campo[$this->app]) {
            $this->camposAceitos = $campo[$this->app]['filtrar'] ?? $campo[$this->app]['geral'] ?? [];
        }
    }

    public function replace(string $campo, array $lista)
    {
        $this->replace[$campo] = $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | RETORNO
    |--------------------------------------------------------------------------
    */
    public function pegarInput()
    {
        return $this->html['input'];
    }

    public function pegarNome()
    {
        return $this->html['nome'] ?? '';
    }
    public function pegarReplace()
    {
        return $this->replace;
    }

    public function bloco(?\Closure $callback)
    {
        if (is_null($callback)) {
            mensagemErro('Erro!', 'Você precisa passar uma função para o bloco.');
        }
        $this->html['input'][] = ['funcao' => 'html', 'html' => '<div class="bloco_row">'];
        call_user_func($callback);
        $this->html['input'][] = ['funcao' => 'html', 'html' => '</div>'];
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | INPUTS
    |--------------------------------------------------------------------------
    */
    public function input(
        string | array $name,
        ?string $titulo = null,
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
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao' => 'input',
            'nome' => $titulo,
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
            'maximo' => $maximo
        ], $permissao);
    }

    public function telefone(
        $name,
        ?string $titulo = null,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(
            name: $name,
            titulo: $titulo,
            label: $label,
            placeholder: $placeholder,
            obrigatorio: $obrigatorio,
            mascara: 'telefone',
            numero: 1
        );
        return $this;
    }

    public function email(
        $name,
        ?string $titulo = null,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(
            name: $name,
            titulo: $titulo,
            label: $label,
            obrigatorio: $obrigatorio,
            placeholder: $placeholder,
            type: 'email'
        );
        return $this;
    }

    public function cpf(
        $name,
        ?string $titulo = null,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(
            name: $name,
            titulo: $titulo,
            label: $label,
            placeholder: $placeholder,
            obrigatorio: $obrigatorio,
            mascara: '000.000.000-00',
            numero: 1
        );
        return $this;
    }

    public function cep(
        $name,
        ?string $titulo = null,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(
            name: $name,
            titulo: $titulo,
            label: $label,
            placeholder: $placeholder,
            obrigatorio: $obrigatorio,
            mascara: '00000-000',
            numero: 1
        );
        return $this;
    }

    public function numero(
        $name,
        ?string $titulo = null,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(
            name: $name,
            titulo: $titulo,
            label: $label,
            obrigatorio: $obrigatorio,
            placeholder: $placeholder,
            numero: 1
        );
        return $this;
    }

    public function data(
        $name,
        ?string $titulo = null,
        string $label = '',
        string $placeholder = '',
        bool $obrigatorio = false
    ) {
        $this->input(
            name: $name,
            titulo: $titulo,
            label: $label,
            obrigatorio: $obrigatorio,
            placeholder: $placeholder,
            attr: ['data-calendario' => 'data'],
            mascara: '00/00/0000',
            numero: 1,
            data: 1
        );
        return $this;
    }

    public function select(
        $name,
        string|array $lista,
        ?string $titulo = null,
        string $label = '',
        string $placeholder = '',
        string $id = '',
        string $class = '',
        bool $obrigatorio = false,
        bool $footer = true,
        string $change = '',
        ?string $permissao = null
    ) {
        if (is_string($lista) && !in_array($lista, ['genero', 'estado_civil', 'estado', 'empresa'])) {
            mensagemErro('Erro', 'Você deve passar um valor de lista aceito.');
        }
        if (is_string($lista) && $lista == 'genero') {
            $lista = (new ListaHelper)->add('', 'Escolha um gênero')->genero()->r();
        } else if (is_string($lista) && $lista == 'estado_civil') {
            $lista = (new ListaHelper)->add('', 'Escolha um Estado Civil')->estadoCivil()->r();
        } else if (is_string($lista) && $lista == 'estado') {
            $lista = (new ListaHelper)->add('', 'Escolha um estado')->estado()->r();
        } else if (is_string($lista) && $lista == 'empresa') {
            $lista = (new ApiHelper(token: true))
                ->json(['titulo' => 'Escolha um cliente'])
                ->get('/admin-empresa/select')
                ->array()['dado'] ?? [];
        }

        $this->replace($name, $lista);

        return $this->adicionarNovoInput([
            'funcao' => 'select',
            'name' => $name,
            'nome' => $titulo,
            'lista' => $lista,
            'label' => $label,
            'placeholder' => $placeholder,
            'id' => $id,
            'class' => $class,
            'obrigatorio' => $obrigatorio,
            'footer' => $footer,
            'change' => $change
        ], $permissao);
    }

    public function switch(
        $name,
        string $label,
        ?string $titulo = null,
        string $class = '',
        string $id = '',
        string $ajuda = '',
        string $html = '',
        array $attr = [],
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao' => 'switch',
            'nome' => $titulo,
            'name' => $name,
            'label' => $label,
            'class' => $class,
            'id' => $id,
            'ajuda' => $ajuda,
            'html' => $html,
            'attr' => $attr,
        ], $permissao);
    }

    public function checkbox(
        ?string $name = null,
        ?string $titulo = null,
        string $label = '',
        $value = '',
        bool $check = false,
        string $class = '',
        string $id = '',
        string $ajuda = '',
        string $html = '',
        array $attr = [],
        ?string $permissao = null
    ) {
        return $this->adicionarNovoInput([
            'funcao' => 'checkbox',
            'nome' => $titulo,
            'name' => $name,
            'label' => $label,
            'value' => $value,
            'check' => $check,
            'class' => $class,
            'id' => $id,
            'ajuda' => $ajuda,
            'html' => $html,
            'attr' => $attr,
        ], $permissao);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function adicionarNovoInput($dado, $permissao)
    {
        $dado['indice'] = preg_replace('/\[\]$/', '', $dado['name']);
        $dado['name'] = explode('->', $dado['name'])[0];
        if (!$this->campoAceito($dado['name'], $permissao)) {
            return $this;
        }

        if (!empty($dado['nome'])) {
            $this->html['nome'][$dado['name']] = $dado['nome'];
        }

        unset($dado['nome']);
        $this->html['input'][] = $dado;

        return $this;
    }

    private function campoAceito($name, $permissao)
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        if (
            (!empty($permissao) && !in_array($permissao, $usuarioPermissao)) ||
            (!empty($this->camposAceitos) && !in_array($name, $this->camposAceitos))
        ) {
            return false;
        }
        return true;
    }
}
