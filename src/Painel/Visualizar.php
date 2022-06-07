<?php

namespace PainelConfig;

final class Visualizar
{
    private int $coluna;
    private int $fieldset;

    private string|int $numeroColuna;

    private array $camposAceitos = [];
    private string $titulo = '';
    private array $html = [];
    private string $css = '';
    private string $js = '';
    private string $linkEditar = '';

    private array $replace = [];
    private array $status = [];

    public function __construct(
        private string $app,
        private string $tipo = 'html'
    ) {
        $this->app = $app;

        $this->coluna = -1;
        $this->fieldset = 0;

        $campo = sessao('PAINEL.campo', padrao: []);
        if (is_array($campo) && array_key_exists($this->app, $campo) && $campo[$this->app]) {
            $this->camposAceitos = $campo[$this->app]['visualizar'] ?? $campo[$this->app]['geral'] ?? [];
        }

        $this->linkEditar = LINK . '/app/editar/' . str_replace('_', '-', $this->app) . '/{id}';
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
    public function pegarLinkEditar()
    {
        return $this->linkEditar;
    }
    public function pegarTipo()
    {
        return in_array($this->tipo, ['html', 'mensagem']) ? $this->tipo : 'html';
    }
    public function pegarReplace()
    {
        return $this->replace;
    }
    public function pegarStatus()
    {
        return $this->status;
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
    public function linkEditar(string $link)
    {
        $this->linkEditar = $link;
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
        $this->fieldset = 0;
        $this->colunaAberta = true;
        call_user_func($callback);
        $this->colunaAberta = false;
        return $this;
    }

    public function bloco(string $titulo = '', ?\Closure $callback = null)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->titulo = $titulo;
        call_user_func($callback);
        $this->fieldset++;
        return $this;
    }

    public function blocoCheckbox(string $titulo = null, ?\Closure $callback = null, ?string $todos = null, ?bool $mais = null, ?bool $margin = null)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }

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
        $this->fieldset++;
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
    | CAMPOS
    |--------------------------------------------------------------------------
    */
    public function imagemRedonda(array|string $campo): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'imagem_redonda',
            'campo' => $campo
        ]);
        return $this;
    }
    public function titulo(array|string $campo): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'titulo',
            'campo' => $campo
        ]);
        return $this;
    }
    public function subTitulo(array|string $campo): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'sub_titulo',
            'campo' => $campo
        ]);
        return $this;
    }
    public function linha(array|string $campo, string $nome, string $formatar = ''): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'linha',
            'campo' => $campo,
            'nome' => $nome,
            'formatar' => $formatar
        ]);
        return $this;
    }
    public function cpf(array|string $campo, string $nome): self
    {
        $this->linha($campo, $nome, 'cpf');
        return $this;
    }
    public function data(array|string $campo, string $nome): self
    {
        $this->linha($campo, $nome, 'data');
        return $this;
    }
    public function dataHora(array|string $campo, string $nome): self
    {
        $this->linha($campo, $nome, 'datahora');
        return $this;
    }
    public function email(array|string $campo, string $nome): self
    {
        $this->linha($campo, $nome, 'email');
        return $this;
    }
    public function telefone(array|string $campo, string $nome): self
    {
        $this->linha($campo, $nome, 'telefone');
        return $this;
    }
    public function cep(array|string $campo, string $nome): self
    {
        $this->linha($campo, $nome, 'cep');
        return $this;
    }

    public function contar(array|string $campo, string $nome): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'contar',
            'campo' => $campo,
            'nome' => $nome
        ]);
        return $this;
    }

    public function checked(string $campo, ?string $nome = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'checked',
            'campo' => $campo,
            'nome' => $nome
        ]);
        return $this;
    }
    public function botao(array|string $campo, string $texto, ?string $id = null, ?string $link = null)
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'botao',
            'texto' => $texto,
            'id' => $id,
            'link' => $link
        ]);
        return $this;
    }
    public function status(
        array|string $campo,
        string $texto,
        ?array $inArray = null,
        ?string $status = null,
        ?string $mensagem = null,
        ?string $id = null,
        ?string $cor = null
    ) {
        $this->status[] = $status;
        $this->adicionarCampo($campo, [
            'funcao' => 'status',
            'texto' => $texto,
            'inArray' => $inArray,
            'cor' => $cor,
            'id' => $id,
            'status' => $status,
            'mensagem' => $mensagem,
            'campo' => $campo
        ]);
        return $this;
    }

    public function vazioBreak(string $campo, ?string $titulo = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'vazio_break',
            'titulo' => $titulo,
            'campo' => $campo
        ]);
        return $this;
    }

    public function replace(string $campo, array $lista)
    {
        $this->replace[$campo] = $lista;
    }

    public function margin(int $margin)
    {
        $this->html('<div class="margin" style="margin-top: ' . $margin . 'px"></div>');
        return $this;
    }

    /**
     * Faz o include de uma view
     *
     * @param string                $view   Qual view será incluida
     * @param null|array|string     $campo  Caso queira mostrar apenas se tiver permissão para um campo
     */
    public function include($view, null|array|string $campo = null): self
    {
        if (!empty($campo) && empty($this->pegarCampoAceito($campo))) {
            return $this;
        }

        $this->html[]['lista'][] = [
            'funcao' => 'include',
            'arquivo' => ROOT . '/files/build/views/painel_' . $this->app . '_Views_' . $view . '.php'
        ];
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function adicionarCampo($campo, $dado)
    {
        $campo = $this->pegarCampoAceito($campo);
        if (empty($campo)) {
            return $this;
        }
        $this->setarTitulo();
        $this->setarColuna();

        $this->html[$this->coluna][$this->fieldset]['lista'][] = $dado;
    }
    private function pegarCampoAceito($campo)
    {

        if (is_array($campo)) {
            $campoLiberado = [];
            foreach ($campo as $val) {
                $validar = preg_replace('/^\!/', '', $val);
                $validar = explode('->', $validar)[0];
                if (empty($this->camposAceitos) | in_array($validar, $this->camposAceitos)) {
                    $campoLiberado[] = $val;
                }
            }
            return $campoLiberado;
        }

        $validar = explode('->', $campo)[0];
        if (!empty($this->camposAceitos) && !in_array($validar, $this->camposAceitos)) {
            return false;
        }
        return $campo;
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
    private function erroCallback()
    {
        mensagemStatus(500, localhost: 'Você deve passar uma callback.');
    }
}
