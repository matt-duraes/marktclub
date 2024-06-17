<?php

namespace PainelConfig;

final class Visualizar
{
    private int $coluna;
    private int $fieldset;
    private string|int $numeroColuna;
    private array $camposAceitos = [];
    private string $titulo = '';
    private bool $abrir = false;
    private array $html = [];
    private string $css = '';
    private string $js = '';
    private string $linkEditar = '';
    private string $link;
    private array $replace = [];
    private array $status = [];

    public const TARGET_SELF = '_self';
    public const TARGET_BLANK = '_blank';

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
        call_user_func($callback);
        return $this;
    }

    public function bloco(string $titulo = '', ?\Closure $callback = null, bool $abrir = false)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->titulo = $titulo;
        $this->abrir = $abrir;
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
            'html'   => $html
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
    public function imagemRedonda(array|string $campo, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'imagem_redonda',
            'campo'  => $campo
        ], $permissao);
        return $this;
    }

    public function imagemLogo(array|string $campo, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'imagem_logo',
            'campo'  => $campo
        ], $permissao);
        return $this;
    }

    public function titulo(array|string $campo, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'titulo',
            'campo'  => $campo
        ], $permissao);
        return $this;
    }

    public function subTitulo(array|string $campo, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'sub_titulo',
            'campo'  => $campo
        ], $permissao);
        return $this;
    }

    public function texto(array|string $campo, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao'   => 'texto',
            'campo'    => $campo,
            'nome'     => '',
            'formatar' => ''
        ], $permissao);
        return $this;
    }

    public function dinheiro(
        array|string $campo,
        string $nome,
        ?string $permissao = null,
        bool $vazio = true
    ): self {
        $this->adicionarCampo($campo, [
            'funcao'   => 'linha',
            'campo'    => $campo,
            'nome'     => $nome,
            'formatar' => 'dinheiro',
            'vazio'    => $vazio
        ], $permissao);
        return $this;
    }

    public function linha(
        array|string $campo,
        string $nome,
        string $formatar = '',
        ?string $permissao = null,
        bool $vazio = true
    ): self {
        $this->adicionarCampo($campo, [
            'funcao'   => 'linha',
            'campo'    => $campo,
            'nome'     => $nome,
            'formatar' => $formatar,
            'vazio'    => $vazio
        ], $permissao);
        return $this;
    }

    public function ou(array|string $campo, string $nome, ?string $permissao = null)
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'ou',
            'campo'  => $campo,
            'nome'   => $nome
        ], $permissao);
        return $this;
    }

    public function e(array|string $campo, string $nome, ?string $permissao = null)
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'e',
            'campo'  => $campo,
            'nome'   => $nome
        ], $permissao);
        return $this;
    }

    public function array(array|string $campo, string $nome, ?string $permissao = null)
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'array',
            'campo'  => $campo,
            'nome'   => $nome
        ], $permissao);
        return $this;
    }

    public function cpf(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->linha($campo, $nome, 'cpf', $permissao);
        return $this;
    }

    public function cnpj(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->linha($campo, $nome, 'cnpj', $permissao);
        return $this;
    }

    public function data(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->linha($campo, $nome, 'data', $permissao);
        return $this;
    }

    public function dataHora(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->linha($campo, $nome, 'datahora', $permissao);
        return $this;
    }

    public function email(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->linha($campo, $nome, 'email', $permissao);
        return $this;
    }

    public function telefone(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->linha($campo, $nome, 'telefone', $permissao);
        return $this;
    }

    public function cep(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->linha($campo, $nome, 'cep', $permissao);
        return $this;
    }

    public function endereco(string $principal, string $secundario)
    {
        $this->adicionarCampo('', [
            'funcao'          => 'endereco',
            'localPrincipal'  => $principal,
            'localSecundario' => $secundario
        ]);
        return $this;
    }

    /**
     * Coloca uma bola com a imagem do usuário
     *
     * @param string|null $nome      Nome do campo que deve aparecer
     * @param string|null $permissao Se precisa de uma permissão
     */
    public function equipe(?string $nome = null, ?string $permissao = null)
    {
        $this->adicionarCampo('equipe', [
            'funcao' => 'equipe',
            'campo'  => 'equipe',
            'nome'   => $nome
        ], $permissao);
        return $this;
    }

    public function linhaTempo(string $principal)
    {
        $this->adicionarCampo('', [
            'funcao'           => 'linha_tempo',
            'localPrincipal'   => $principal
        ]);
        return $this;
    }

    public function contato(string $principal, string $secundario)
    {
        $this->adicionarCampo('', [
            'funcao'           => 'contato',
            'localPrincipal'   => $principal,
            'localSecundario'  => $secundario
        ]);
        return $this;
    }

    public function contar(array|string $campo, string $nome, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'contar',
            'campo'  => $campo,
            'nome'   => $nome
        ], $permissao);
        return $this;
    }

    public function checked(string $campo, ?string $nome = null, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'checked',
            'campo'  => $campo,
            'nome'   => $nome
        ], $permissao);
        return $this;
    }

    public function hidden(array|string $campo, string $id = null, string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao'   => 'hidden',
            'campo'    => $campo,
            'nome'     => '',
            'id'       => $id,
            'formatar' => ''
        ], $permissao);
        return $this;
    }

    public function botao(
        array|string $campo,
        string $texto,
        ?string $id = null,
        ?string $link = null,
        ?string $target = null,
        ?string $permissao = null,
        array $attr = []
    ) {
        $this->adicionarCampo($campo, [
            'funcao' => 'botao',
            'texto'  => $texto,
            'id'     => $id,
            'link'   => $link,
            'target' => $target,
            'attr'   => $attr
        ], $permissao);
        return $this;
    }

    public function botaoDestaque(
        string $texto = '',
        array|string $campo = null,
        ?array $inArray = null,
        ?string $id = null,
        ?string $cor = null,
        ?string $permissao = null,
        ?bool $editar = null
    ) {
        $this->adicionarCampo($campo, [
            'funcao'   => 'botaoDestaque',
            'texto'    => $texto,
            'inArray'  => $inArray,
            'cor'      => $cor,
            'id'       => $id,
            'campo'    => $campo,
            'editar'   => $editar
        ], $permissao);
        return $this;
    }

    public function status(
        array|string $campo,
        string $texto,
        ?array $inArray = null,
        ?string $status = null,
        ?string $mensagem = null,
        ?string $id = null,
        ?string $cor = null,
        ?string $permissao = null,
        ?bool $editar = null
    ) {
        $this->status[] = $status;
        $this->adicionarCampo($campo, [
            'funcao'   => 'status',
            'texto'    => $texto,
            'inArray'  => $inArray,
            'cor'      => $cor,
            'id'       => $id,
            'status'   => $status,
            'mensagem' => $mensagem,
            'campo'    => $campo,
            'editar'   => $editar
        ], $permissao);
        return $this;
    }

    public function vazioBreak(string $campo, ?string $titulo = null, ?string $permissao = null): self
    {
        $this->adicionarCampo($campo, [
            'funcao' => 'vazio_break',
            'titulo' => $titulo,
            'campo'  => $campo
        ], $permissao);
        return $this;
    }

    public function replace(string $campo, array $lista)
    {
        $this->replace[$campo] = $lista;
        return $this;
    }

    public function margin(int $margin)
    {
        $this->html('<div class="margin" style="margin-top: ' . $margin . 'px"></div>');
        return $this;
    }

    /**
     * Faz o include de uma view
     *
     * @param string            $view      Qual view será incluida
     * @param null|array|string $campo     Caso queira mostrar apenas se tiver permissão para um campo
     * @param null|string       $permissao Caso o usuário tenha que ter uma permissão específica
     * @param null|string       $app       Quando o APP do include não é o mesmo do principal
     */
    public function include($view, null|array|string $campo = null, ?string $permissao = null, ?string $app = null): self
    {
        if (!empty($campo) && empty($this->pegarCampoAceito($campo, $permissao))) {
            return $this;
        }

        $app = !empty($app) ? $app : $this->app;
        $this->html[]['lista'][] = [
            'funcao'  => 'include',
            'arquivo' => ROOT . '/files/build/views/painel_' . $app . '_' . $view . '.php'
        ];
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function adicionarCampo($campo = null, array $dado = [], ?string $permissao = null)
    {
        $campoAceito = !empty($campo) ? $this->pegarCampoAceito($campo, $permissao) : true;
        if (empty($campoAceito)) {
            return $this;
        }
        $this->setarTitulo();
        $this->setarAbrir();
        $this->setarColuna();

        $this->html[$this->coluna][$this->fieldset]['lista'][] = $dado;
    }

    private function pegarCampoAceito($campo, ?string $permissao = null)
    {
        $usuarioPermissao = sessao('USUARIO.permissao');
        if (is_array($campo)) {
            $campoLiberado = [];
            foreach ($campo as $val) {
                $validar = preg_replace('/^\!/', '', $val);
                $validar = explode('->', $validar)[0];
                if (
                    (empty($permissao) || in_array($permissao, $usuarioPermissao)) &&
                    (empty($this->camposAceitos) || in_array($validar, $this->camposAceitos))
                ) {
                    $campoLiberado[] = $val;
                }
            }
            return $campoLiberado;
        }

        $validar = explode('->', $campo)[0];
        if (
            (!empty($this->camposAceitos) && !in_array($validar, $this->camposAceitos)) ||
            (!empty($permissao) && !in_array($permissao, $usuarioPermissao))
        ) {
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

    private function setarAbrir()
    {
        if ($this->abrir) {
            $this->html[$this->coluna][$this->fieldset]['abrir'] = true;
            $this->abrir = false;
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
