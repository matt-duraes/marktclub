<?php

namespace App\Models\Site\Pagina;

use stdClass;
use App\Helpers\ClubeApiHelper;
use App\Models\Site\Loja\RetornoModel;

final class ApiModel
{
    private array $componente = [];
    public stdClass $retorno;

    public function __construct(
        private string $url,
        private string $id,
        private array $campo
    ) {
        $this->retorno = object([]);
        $this->pegarComponente();
        $this->fazerRequisicao();
    }

    private function fazerRequisicao()
    {
        $com = $this->componente;
        if (!array_key_exists('status', $com) || $com['status'] != 'sim') {
            return mensagemStatus(403, localhost: 'Status não existe ou ele não é sim');
        }

        $metodo = $com['metodo'];
        $body = $com['body'];
        $uri = $com['uri'];

        $Api = new ClubeApiHelper();

        $get = $metodo == 'GET';
        $post = $metodo == 'POST';

        $Api->validar(status: 403);

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
        $this->retorno = $this->montarRetorno($Api->object(), $metodo, $uri);
    }

    private function montarRetorno($dado, $metodo, $uri)
    {
        if (!object_key_exists('dado', $dado) || !object_key_exists('lista', $dado->dado) || empty($dado->dado->lista)) {
            return $dado;
        }

        $retorno = [];
        if ($metodo == 'GET' && $uri == '/parceiro-loja') {
            $Retorno = new RetornoModel($dado->dado->lista);
            $retorno = $Retorno->retorno;
        } else {
            $campo = $this->campo;
            foreach ($dado->dado->lista as $item) {
                $r = [];
                foreach ($item as $ind => $val) {
                    if (!in_array($ind, $campo)) {
                        continue;
                    } elseif ($ind == 'target' && array_key_exists('target', $r)) {
                        continue;
                    }

                    $eLink = !empty($val) && is_string($val) && (
                        str_starts_with($val, 'http://') || str_starts_with($val, 'https://')
                    );
                    if ($eLink) {
                        $val = strLink($val);
                    }

                    if ($eLink && $ind == 'link' && !str_starts_with($val, LINK)) {
                        $r['target'] = '_blank';
                        $r['rel'] = 'noopener noreferrer';
                    }
                    $r[$ind] = $val;
                }
                $retorno[] = $r;
            }
        }
        $dado->dado->lista = $retorno;
        return $dado;
    }

    private function pegarComponente()
    {
        $sessao = 'PAGINA_' . strCaixaAlta(str_replace('/', '_', $this->url));
        if (!sessaoExiste($sessao)) {
            mensagemStatus(403, localhost: 'Sessão não existe.');
        }
        $this->selecionarComponente(sessao($sessao)->html);
    }

    private function selecionarComponente($html)
    {
        foreach ($html as $r) {
            $lista = [];
            if (object_key_exists('lista', $r)) {
                $lista = $r->lista;
                unset($r->lista);
            }
            if ($r->id == $this->id) {
                $this->componente = [
                    'uri'    => '/' . $r->api_uri ?? '',
                    'metodo' => $r->api_metodo ?? '',
                    'body'   => $this->montarBody($r->api_body ?? []),
                    'status' => $r->api_status ?? 'nao',
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
