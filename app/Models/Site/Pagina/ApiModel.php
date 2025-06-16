<?php

namespace App\Models\Site\Pagina;

use App\Helpers\ClubeApiHelper;

final class ApiModel extends ClubeApiHelper
{
    public array $retorno = [];
    private array $busca = [];
    private array $body = [];
    private string $uri = '';

    public function __construct(
        private array $dado,
        private ?string $tipo = null
    ) {
        parent::__construct();
        $this->validarDado();
        $this->montarBody();
        $this->setarUri();
        $this->fazerRequisicao();
        $this->validarBusca();
        $this->limparCampo();
    }

    private function limparCampo()
    {
        $campo = $this->pegarCampoRetorno();
        if(empty($campo)) {
            return;
        }

        $retorno = [];
        foreach($this->retorno as $item) {
            $dado = [];
            foreach($item as $ind => $val) {
                if(!in_array($ind, $campo)) {
                    continue;
                }
                $dado[$ind] = $val;
            }
            $retorno[] = $dado;
        }
        $this->retorno = $retorno;
    }

    private function pegarCampoRetorno(): array
    {
        $tipo = !empty($this->tipo) ? $this->tipo : '';
        return [
            'banner' => ['imagem_desktop', 'imagem_mobile', 'link']
        ][$tipo] ?? [];
    }

    private function validarBusca()
    {
        $busca = $this->busca;
        if(!validarIndiceExiste($busca, ['status', 'dado']) || $busca['status'] !== 'sucesso') {
            mensagemStatus(500, localhost: 'Não foi possível validar a busca.');
        }
        $busca = $busca['dado'];
        $this->retorno = validarIndiceExiste($busca, ['lista', 'pagina']) ? $busca['lista'] : $busca;
    }

    private function montarBody()
    {
        foreach($this->dado['body'] ?? [] as $r) {
            if(!validarIndiceExiste($r, ['valor', 'indice'])) {
                continue;
            }
            $this->body[$r['indice']] = $r['valor'];
        }
    }
    private function setarUri()
    {
        $uri = $this->dado['uri'] ?? '';
        $this->uri = !empty($uri) ? '/' . preg_replace('/^\//', '', $uri) : '';
    }

    private function validarDado()
    {
        if(
            empty($this->dado) ||
            !validarIndiceExiste($this->dado, ['metodo', 'uri']) ||
            !in_array($this->dado['metodo'], ['GET', 'POST'])
        ) {
            mensagemStatus(403);
        }
    }

    private function fazerRequisicao()
    {
        $metodo = [
            'GET' => 'get',
            'POST' => 'post'
        ][$this->dado['metodo']];
        $body = [
            'get' => 'parametro',
            'post' => 'body'
        ][$metodo];

        if($this->body) {
            $this->busca = $this->$body($this->body)->$metodo($this->uri)->array();
            return;
        }
        $this->busca = $this->$metodo($this->uri)->array();
    }
}
