<?php

namespace System\Html\Postman\Models;

final class Requisicao
{
    public array $requisicao = [];
    private string $path;
    private string $id;
    public function __construct(
        string $path,
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
            'id' => $this->id,
            'token' => $dado['token'] ?? 'sem_token',
            'metodo' => $dado['metodo'] ?? 'GET',
            'nome' => $dado['nome'] ?? 'Temporario',
            'uri' => $dado['uri'] ?? '{{LINK}}/',
            'parametro' => $dado['parametro'] ?? [],
            'body' => $dado['body'] ?? [],
            'header' => $dado['header'] ?? [],
            'variavel' => $dado['variavel'] ?? [],
            'json' => $dado['json'] ?? '',
            'documentacao' => [
                'descricao' => $dado['documentacao']['descricao'] ?? '',
                'requisicao' => $dado['documentacao']['requisicao'] ?? '',
                'resposta' => $dado['documentacao']['resposta'] ?? '',
            ]
        ];
    }

    private function setarNome($nome)
    {
        if (empty($nome)) {
            return '';
        }
        return preg_replace(['/[^A-Za-z\ \-\_0-9à-úÀÚ]/', '/\ {1,}/'], ['', ' '], trim($nome));
    }

    public function nome(string $nome)
    {
        $this->requisicao['nome'] = $this->setarNome($nome);
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
