<?php

namespace App\Models\Site\Pagina;

use App\Helpers\ClubeApiHelper;

final class ApiModel extends ClubeApiHelper
{
    public int     $pagina  = 0;
    public array   $retorno = [];
    private array  $busca   = [];
    private array  $body    = [];
    private string $uri     = '';

    public function __construct(
        private readonly array $dado,
        private readonly ?string $tipo = null,
        private readonly null|string|array $replace = null
    ) {
        parent::__construct();
        $this->validarDado();
        $this->montarBody();
        $this->setarUri();
        $this->fazerRequisicao();
        $this->validarBusca();
        $this->limparCampo();
        $this->adicionarCampoNovo();
    }

    private function adicionarCampoNovo()
    {
        if($this->tipo === 'loja' && $this->retorno) {
            $this->montarLoja();
        }
    }

    private function montarLoja()
    {
        foreach($this->retorno as $ind => $r) {
            $link = '';
            if($r['tipo_loja'] === 'plano-saude') {
                $link = LINK . '/saude/' . $this->replaceUrl($r['url']);
            }
            $this->retorno[$ind]['link'] = $link;
        }
    }

    private function replaceUrl(string $url)
    {
        if(!str_contains($url, '?')) {
            return $url;
        }
        $parse = parse_url($url);
        $replace = $this->replace;
        $replace['&amp;'] = '&';
        $query = str_replace(array_keys($replace), array_values($replace), $parse['query']);
        $query = preg_replace('/(\{\{[A-Z0-9_]+\}\})/', '', $query);
        $explode = explode('&', $query);
        $final = [];
        foreach($explode as $item) {
            if(str_ends_with($item, '=')) {
                continue;
            }
            $final[] = $item;
        }
        if(empty($final)) {
            return $parse['path'];
        }
        return $parse['path'] . '?' . implode('&', $final);
    }

    private function limparCampo(): void
    {
        $campo = $this->pegarCampoRetorno();
        if (empty($campo)) {
            return;
        }

        $retorno = [];
        foreach ($this->retorno as $item) {
            $dado = [];
            foreach ($item as $ind => $val) {
                if (!in_array($ind, $campo)) {
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
            'banner' => ['imagem_desktop', 'imagem_mobile', 'link'],
            'loja' => ['imagem', 'imagem_logo', 'id', 'tipo_loja', 'url', 'titulo']
        ][$tipo] ?? [];
    }

    private function validarBusca(): void
    {
        $busca = $this->busca;
        if (!validarIndiceExiste($busca, ['status', 'dado']) || $busca['status'] !== 'sucesso') {
            mensagemStatus(500, localhost: 'Não foi possível validar a busca.');
        }
        $busca = $busca['dado'];
        if (validarIndiceExiste($busca, ['lista', 'pagina'])) {
            $this->pagina = $busca['pagina']['total'];
            $this->retorno = $busca['lista'];
            return;
        }
        $this->retorno = $busca;
    }

    private function montarBody(): void
    {
        $replace = $this->replace;
        foreach ($this->dado['body'] ?? [] as $r) {
            if (!validarIndiceExiste($r, ['valor', 'indice'])) {
                continue;
            }
            $indice = $r['indice'];
            $valor = $r['valor'];
            $this->body[$indice] = $replace[$valor] ?? $valor;
        }
    }

    private function setarUri(): void
    {
        $uri = $this->dado['uri'] ?? '';
        $this->uri = !empty($uri) ? '/' . preg_replace('/^\//', '', $uri) : '';
    }

    private function validarDado(): void
    {
        if (
            empty($this->dado) ||
            !validarIndiceExiste($this->dado, ['metodo', 'uri']) ||
            !in_array($this->dado['metodo'], ['GET', 'POST'])
        ) {
            mensagemStatus(403);
        }
    }

    private function fazerRequisicao(): void
    {
        $metodo = [
            'GET'  => 'get',
            'POST' => 'post',
        ][$this->dado['metodo']];
        $body = [
            'get'  => 'parametro',
            'post' => 'body',
        ][$metodo];

        if ($this->body) {
            $this->busca = $this->$body($this->body)->$metodo($this->uri)->array();
            return;
        }
        $this->busca = $this->$metodo($this->uri)->array();
    }
}
