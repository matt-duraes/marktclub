<?php

namespace App\Models\Site\Pagina;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class ApiModel
{
    private array $componente = [];
    public stdClass $retorno;

    public function __construct(
        private string $url,
        private string $id
    ) {
        $this->retorno = object([]);
        $this->pegarComponente();
        $this->fazerRequisicao();
    }

    private function fazerRequisicao()
    {
        $com = $this->componente;
        if (!array_key_exists('status', $com) || $com['status'] != 'sim') {
            return [];
        }

        $metodo = $com['metodo'];
        $body = $com['body'];
        $uri = $com['uri'];

        $Api = new ClubeApiHelper();

        $get = $metodo == 'GET';
        $post = $metodo == 'POST';

        if ($body && $post) {
            $Api->body($body);
        } elseif ($body && $get) {
            $Api->json($body);
        }

        if ($get) {
            $Api->get($uri);
        } elseif ($post) {
            $Api->post($uri);
        }
        $this->retorno = $Api->object();
    }

    private function pegarComponente()
    {
        $sessao = 'PAGINA_' . strCaixaAlta(str_replace('/', '_', $this->url));
        if (!sessaoExiste($sessao)) {
            mensagemStatus(404);
        }
        $this->selecionarComponente(sessao($sessao)->html);
    }

    private function selecionarComponente($html)
    {
        foreach ($html as $r) {
            if (object_key_exists('lista', $r)) {
                $lista = $r->lista;
                unset($r->lista);
            }
            if ($r->id == $this->id) {
                $this->componente = [
                    'uri'    => '/' . $r->api_uri,
                    'metodo' => $r->api_metodo,
                    'body'   => $this->montarBody($r->api_body),
                    'status' => $r->api_status,
                ];
                break;
            }
            if ($lista) {
                $this->selecionarComponente($lista);
            }
        }
    }

    private function montarBody($body): array
    {
        $retorno = [];
        foreach ($body as $r) {
            $retorno[$r->indice] = $r->valor;
        }
        return $retorno;
    }
}
