<?php

namespace PainelConfig;

final class Ajax
{
    private array $request = [];
    private string $permissao = '';
    private string $rota = '';
    private string $metodo = 'post';
    private string $scope = '';

    public function request(array $request)
    {
        $this->request = $request;
        return $this;
    }
    public function permissao(string $permissao)
    {
        $this->permissao = $permissao;
        return $this;
    }
    public function rota(string $rota)
    {
        $this->rota = $rota;
        return $this;
    }
    public function metodo(string $metodo)
    {
        $this->metodo = $metodo;
        return $this;
    }
    public function scope(string $scope)
    {
        $this->scope = $scope;
        return $this;
    }

    public function pegarPermissao()
    {
        if (sessao('USUARIO.dev')) {
            return true;
        }

        $permissaoUsuario = sessao('USUARIO.permissao', padrao: []);
        $permissaoPainel = sessao('PAINEL.permissao.lista', padrao: []);
        return in_array($this->permissao, $permissaoUsuario) && in_array($this->permissao, $permissaoPainel);
    }
    public function pegarRequest()
    {
        return $this->request;
    }
    public function pegarRota()
    {
        return $this->rota;
    }
    public function pegarMetodo()
    {
        return strCaixaBaixa($this->metodo);
    }
    public function pegarScope()
    {
        return $this->scope;
    }
}
