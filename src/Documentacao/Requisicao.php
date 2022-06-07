<?php

namespace DocumentacaoConfig;

final class Requisicao
{
    private ?string $id = '';
    private ?array $erro = [];
    private ?array $pre = [];
    private ?array $observacao = null;
    private ?array $header = null;
    private ?array $body = null;

    public function __toString()
    {
        $titulo = !empty($this->titulo) ? $this->titulo : '';
        $descricao = !empty($this->descricao) ? $this->descricao : '';
        $status = !empty($this->status) ? $this->status : '';
        $scope = !empty($this->scope) ? $this->scope : '';
        $metodo = !empty($this->metodo) ? $this->metodo : '';
        $uri = !empty($this->uri) ? $this->uri : '';
        $observacao = $this->montarObservacao();

        return '
            <article class="requisicao" id="' . $this->id . '">
                <div class="dado">
                    <header>' . $titulo . $descricao . '</header>
                    ' . $status . $scope . '
                    <div class="bloco_metodo">
                    ' . $metodo . $uri . '
                    </div>
                    ' . $this->montarBody('Header', $this->header) . '
                    ' . $this->montarBody('Body', $this->body) . '
                    ' . $observacao . '
                    ' . $this->montarErro() . '
                </div>
                ' . $this->montarPre() . '
            </article>
        ';
    }
    private function montarBody($titulo, $lista)
    {
        if (!is_array($lista) || empty($lista)) {
            return '';
        }
        return '
            <div class="body">
                <h3>' . $titulo . '</h3>
                <div class="lista">
                    ' . implode(' ', $lista) . '
                </div>
            </div>
        ';
    }
    private function montarObservacao()
    {
        if (empty($this->observacao)) {
            return '';
        }
        return '
            <div class="observacao">
                ' . implode(' ', $this->observacao) . '
            </div>
        ';
    }
    private function montarErro()
    {
        $this->erro[] = [500, 'Erro interno por alguma falha ou instabilidade.'];

        $html = '
            <div class="erro">
                <h3>Lista de erro:</h3>
                <div class="lista">
        ';
        foreach ($this->erro as $valor) {
            $html .= '<div class="linha"><div class="numero">' . $valor[0] . '</div><p>' . $valor[1] . '</p></div>';
        }
        $html .= '
                </div>
            </div>
        ';
        return $html;
    }
    private function montarPre()
    {
        if (empty($this->pre)) {
            return '';
        }
        $html = '<div class="curl">';
        foreach ($this->pre as $valor) {
            $html .= '<h2>' . $valor[0] . ':</h2><pre>' . $valor[1] . '</pre>';
        }
        $html .= '</div>';
        return $html;
    }

    public function titulo($titulo)
    {
        $this->titulo = '<h1>' . $titulo . '</h1>';
        return $this;
    }
    public function descricao($descricao)
    {
        $this->descricao = '<p>' . $descricao . '</p>';
        return $this;
    }
    public function status($status)
    {
        $this->status = '<div class="status"><p>Status de retorno</p><strong>' . $status . '</strong></div>';
        return $this;
    }
    public function scope($scope)
    {
        $this->scope = '<div class="scope"><p>Scope:</p><strong>' . $scope . '</strong></div>';
        return $this;
    }
    public function metodo($metodo)
    {
        if (empty($this->id)) {
            $this->id = strCaixaBaixa($metodo);
        } else {
            $this->id = strCaixaBaixa($metodo) . '-' . $this->id;
        }

        $this->metodo = '<div class="metodo">' . $metodo . '</div>';
        return $this;
    }

    public function uri($uri)
    {
        $uri = strCaixaBaixa($uri);
        $id = str_replace('/', '-', preg_replace('/^\//', '', $uri));
        if (empty($this->id)) {
            $this->id = $id;
        } else {
            $this->id = $this->id . '-' . $id;
        }

        $this->uri = '<div class="uri">' . $uri . '</div>';
        return $this;
    }

    public function body($campo, ?string $exemplo = null, ?string $descricao = null, ?string $tipo = null, ?int $tamanho = null, string|bool $obrigatorio = false)
    {
        $this->blocoBody('body', $campo, $exemplo, $descricao, $tipo, $tamanho, $obrigatorio);
        return $this;
    }

    public function header($campo, ?string $exemplo = null, ?string $descricao = null, ?string $tipo = null, ?int $tamanho = null, string|bool $obrigatorio = false)
    {
        $this->blocoBody('header', $campo, $exemplo, $descricao, $tipo, $tamanho, $obrigatorio);
        return $this;
    }

    public function headerToken()
    {
        $this->blocoBody('header', 'Authorization', 'access_token_aqui', 'O access_token criado pela rota POST /token');
        return $this;
    }
    public function headerJson()
    {
        $this->blocoBody('header', 'Content-Type', 'application/json');
        return $this;
    }

    private function blocoBody($indice, $campo, ?string $exemplo = null, ?string $descricao = null, ?string $tipo = null, ?int $tamanho = null, string|bool $obrigatorio = false)
    {
        $tipo = !empty($tipo) && !empty($tamanho) ? $tipo . ' <span class="texto">(' . $tamanho . ')</span>' : $tipo;
        $exemplo = !empty($exemplo) ? '<div class="exemplo texto"><span class="texto">Ex.:</span>' . $exemplo . '</div>' : '';
        $descricao = !empty($descricao) ? '<div class="descricao texto">' . $descricao . '</div>' : '';
        $tipo = !empty($tipo) ? '<div class="tipo texto">' . $tipo . '</div>' : '<div class="tipo"></div>';
        $obrigatorioHtml = '<div class="obrigatorio"></div>';
        if ($obrigatorio === true) {
            $obrigatorioHtml = '<div class="obrigatorio texto">*</div>';
        } else if ($obrigatorio == '-') {
            $obrigatorioHtml = '<div class="obrigatorio azul texto">-</div>';
        }
        $this->$indice[] = '
            <div class="linha">
                <div class="campo texto">' . $campo . '</div>
                <div class="exemplo_descricao">
                    ' . $exemplo . '
                    ' . $descricao . '
                </div>
                ' . $tipo . '
                ' . $obrigatorioHtml . '
            </div>
        ';
    }
    public function observacao($observacao)
    {
        $this->observacao[] = '<p>' . $observacao . '</p>';
        return $this;
    }
    public function erro(int $status, string $mensagem)
    {
        $this->erro[] = [$status, $mensagem];
        return $this;
    }
    public function erro400()
    {
        $this->erro[] = [400, 'Geralmente o erro vem descrito na resposta, em casos especiais de segurança, vem uma mensagem genêrica que normalmente é a falta ou envio de um parâmetro a mais no corpo.'];
        return $this;
    }
    public function erro401()
    {
        $this->erro[] = [401, 'Você não enviou um Token para essa requisição.'];
        return $this;
    }
    public function erro403()
    {
        $this->erro[] = [403, 'O Token enviado para essa requisição é inválido, não tem permissão para esse scope ou venceu.'];
        return $this;
    }
    public function pre(string $titulo, string $pre)
    {
        $this->pre[] = [preg_replace('/\:$/', '', $titulo), $pre];
        return $this;
    }
    public function preExemplo(string $pre)
    {
        $this->pre[] = ['Exemplo', $pre];
        return $this;
    }
    public function preSucesso(string $pre)
    {
        $this->pre[] = ['Sucesso', $pre];
        return $this;
    }
    public function preFalha(string $pre)
    {
        $this->pre[] = ['Falha', $pre];
        return $this;
    }
}
