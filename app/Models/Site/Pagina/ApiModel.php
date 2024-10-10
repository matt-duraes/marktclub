<?php

namespace App\Models\Site\Pagina;

use App\Helpers\ClubeApiHelper;

final class ApiModel extends ClubeApiHelper
{
    private array $componente = [];

    public function __construct(
        private string $url,
        private string $id
    ) {
        parent::__construct();
        $this->pegarComponente();
        ppe($this->componente);
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
                    // 'parametro' => $r->api_parametro,
                    'status' => $r->api_status,
                ];
                break;
            }
            if ($lista) {
                $this->selecionarComponente($lista);
            }
        }
    }
}
