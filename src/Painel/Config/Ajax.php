<?php

namespace PainelConfig;

final class Ajax
{
    private string $indice;
    private array $request = [];
    private array $permissao = [];
    private array $rota = [];
    private array $metodo = [];

    public function __construct()
    {
        $this->indice = 'geral';
    }

    public function indice(?string $indice)
    {
        if (empty($indice)) {
            return;
        }
        $this->indice = $indice;
    }

    public function grupo(string $indice, \Closure $callback)
    {
        $this->indice = $indice;
        call_user_func($callback);
        return $this;
    }

    public function request(array $request)
    {
        $this->request[$this->indice] = $request;
        return $this;
    }

    public function permissao(string $permissao)
    {
        $this->permissao[$this->indice] = $permissao;
        return $this;
    }

    public function rota(string $rota)
    {
        $this->rota[$this->indice] = $rota;
        return $this;
    }

    public function metodo(string $metodo)
    {
        $this->metodo[$this->indice] = $metodo;
        return $this;
    }

    public function pegarPermissao()
    {
        if (sessao('USUARIO.dev')) {
            return true;
        }

        $permissaoUsuario = sessao('USUARIO.permissao', padrao: []);
        $permissaoPainel = sessao('PAINEL.permissao', padrao: []);
        $permissao = $this->permissao[$this->indice] ?? '';

        return !empty($permissao) && in_array($permissao, $permissaoUsuario) && in_array($permissao, $permissaoPainel);
    }

    public function pegarRequest()
    {
        return $this->request[$this->indice] ?? '';
    }

    public function pegarRota()
    {
        return $this->rota[$this->indice] ?? '';
    }

    public function pegarMetodo()
    {
        return strCaixaBaixa($this->metodo[$this->indice] ?? 'POST');
    }
}
