<?php

namespace PainelConfig;

final class Download
{
    private array $camposAceitos = [];
    private array $html = [];
    private array $campo = [];
    private array $replace = [];
    private string $titulo = '';

    private int $bloco;

    public function __construct(
        private string $app
    ) {
        $this->app = $app;

        $this->bloco = -1;
        $campo = sessao('PAINEL.campo', padrao: []);

        if (is_array($campo) && array_key_exists($this->app, $campo) && $campo[$this->app]) {
            $this->camposAceitos = $campo[$this->app]['download'] ?? $campo[$this->app]['geral'] ?? [];
        }
    }

    public function bloco(string $titulo = '', ?\Closure $callback = null)
    {
        if (is_null($callback)) {
            $this->erroCallback();
        }
        $this->bloco++;
        $this->titulo = $titulo;
        call_user_func($callback);
        return $this;
    }

    public function campo(string $campo, string $titulo)
    {
        if (!empty($this->camposAceitos) && !in_array($campo, $this->camposAceitos)) {
            return $this;
        }

        if (!empty($this->titulo)) {
            $this->html[$this->bloco]['titulo'] = $this->titulo;
            $this->titulo = '';
        }

        $this->campo[] = $campo;
        $this->replace[$campo] = $titulo;
        $this->html[$this->bloco]['lista'][$campo] = $titulo;

        return $this;
    }

    public function pegarHtml()
    {
        return $this->html;
    }
    public function pegarCampo()
    {
        return $this->campo;
    }
    public function pegarReplace()
    {
        return $this->replace;
    }

    private function erroCallback()
    {
        mensagemStatus(500, localhost: 'Você deve passar uma callback.');
    }
}
