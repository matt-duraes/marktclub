<?php

namespace PainelModel\Historico;

use Helpers\ApiHelper;
use Helpers\CryptHelper;

final class Equipe
{
    private array $equipe = [];

    public function pegarListaEquipe()
    {
        $lista = $this->buscarEquipeApi();
        if (!object_key_exists('dado', $lista)) {
            return [];
        }
        $this->montarDadoEquipe($lista);

        $paginaTotal = $lista->dado->pagina->total;
        if ($paginaTotal > 1) {
            for ($i = 2; $i <= $paginaTotal; $i++) {
                $this->montarDadoEquipe($this->buscarEquipeApi($i));
            }
        }
        return $this->equipe;
    }
    private function buscarEquipeApi(int $pagina = 1)
    {
        $Api = new \Helpers\ApiHelper(token: true);
        return $Api->json([
            'pagina' => $pagina,
            'quantidade' => 1
        ])->get('/usuario-equipe')->object();
    }

    private function montarDadoEquipe($lista)
    {
        if (!object_key_exists('dado', $lista)) {
            return;
        }

        $chave = (new ApiHelper('admin:chave_privada'))->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $Crypt = new CryptHelper(chavePrivada: $chave);
        foreach ($lista->dado->lista as $r) {
            $this->equipe[] = object([
                'id' => $r->id,
                'perfil' => $Crypt->decode($r->perfil),
                'nome' => $Crypt->decode($r->nome),
                'imagem' => $Crypt->decode($r->imagem)
            ]);
        }
    }
}
