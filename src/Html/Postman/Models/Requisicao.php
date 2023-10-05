<?php

namespace System\Html\Postman\Models;

final class Requisicao
{
    public array $requisicao = [];
    private string $path;
    private string $id;

    public function __construct(
        ?string $path = null,
        ?string $pai = null,
        ?string $id = null
    ) {
        $this->id = !empty($id) ? $id : 'id_' . md5(uniqid(time()));

        $path .= !empty($pai) ? str_replace(' ', ' ', $this->setarNome($pai)) . '/' : '';
        $path .= $this->id . '.json';
        $this->path = $path;

        if (empty($id)) {
            $this->montarRequisicao([]);
            return;
        } elseif (!file_exists($this->path)) {
            mensagemErro('Erro!', 'Não foi encontrado a requisição.');
        }
        $this->montarRequisicao(jsonDecode(file_get_contents($path), true, true));
    }

    private function montarRequisicao(array $dado)
    {
        $this->requisicao = [
            'id'           => $this->id,
            'token'        => $dado['token'] ?? 'sem_token',
            'metodo'       => $dado['metodo'] ?? 'GET',
            'nome'         => $dado['nome'] ?? 'Temporario',
            'uri'          => $dado['uri'] ?? '{{LINK}}/',
            'parametro'    => jsonDecode($dado['parametro'] ?? [], true, true),
            'body'         => jsonDecode($dado['body'] ?? [], true, true),
            'header'       => jsonDecode($dado['header'] ?? [], true, true),
            'variavel'     => jsonDecode($dado['variavel'] ?? [], true, true),
            'json'         => jsonDecode($dado['json'] ?? [], true, true),
            'documentacao' => [
                'status'     => $dado['documentacao']['status'] ?? false,
                'descricao'  => $dado['documentacao']['descricao'] ?? '',
                'requisicao' => $dado['documentacao']['requisicao'] ?? '',
                'resposta'   => $dado['documentacao']['resposta'] ?? '',
                'scope'      => $dado['documentacao']['scope'] ?? '',
            ]
        ];
    }

    private function setarNome($nome)
    {
        if (empty($nome)) {
            return '';
        }
        return preg_replace(['/[^A-Za-z\:\/\ \-\_0-9à-úÀÚ]/', '/\ {1,}/'], ['', ' '], trim($nome));
    }

    public function nome(string $nome)
    {
        $this->requisicao['nome'] = $this->setarNome($nome);
        return $this;
    }

    public function token(string $token)
    {
        $this->requisicao['token'] = $token;
        return $this;
    }

    public function metodo(string $metodo)
    {
        $this->requisicao['metodo'] = $metodo;
        return $this;
    }

    public function uri(string $uri)
    {
        $this->requisicao['uri'] = $uri;
        return $this;
    }

    public function parametro(array $parametro)
    {
        $this->requisicao['parametro'] = $parametro;
        return $this;
    }

    public function body(array $body)
    {
        $this->requisicao['body'] = $body;
        return $this;
    }

    public function header(array $header)
    {
        $this->requisicao['header'] = $header;
        return $this;
    }

    public function variavel(array $variavel)
    {
        $this->requisicao['variavel'] = $variavel;
        return $this;
    }

    public function json(array $json)
    {
        $this->requisicao['json'] = $json;
        return $this;
    }

    public function documentacao(bool $documentacao)
    {
        $this->requisicao['documentacao']['status'] = $documentacao;
        return $this;
    }

    public function descriaco(string $descriaco)
    {
        $this->requisicao['documentacao']['descricao'] = $descriaco;
        return $this;
    }

    public function requisicao(string $requisicao)
    {
        $this->requisicao['documentacao']['requisicao'] = $requisicao;
        return $this;
    }

    public function resposta(string $resposta)
    {
        $this->requisicao['documentacao']['resposta'] = $resposta;
        return $this;
    }

    public function scope(string $scope)
    {
        $this->requisicao['documentacao']['scope'] = $scope;
        return $this;
    }

    public function salvar()
    {
        if (!criarArquivo($this->path, jsonEncode($this->requisicao))) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar o arquivo.');
        }
    }

    public function deletar()
    {
        unlink($this->path);
    }
}
