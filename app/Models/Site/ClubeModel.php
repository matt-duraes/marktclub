<?php

namespace App\Models\Site;

use Helpers\ApiHelper;

final class ClubeModel extends ApiHelper
{
    private string $id;

    public function __construct()
    {
        parent::__construct('construtor_clube:buscar');
        $this->id = env('CONSTRUTOR_VERSAO', '');
        $this->buscarClube();
    }

    private function buscarClube()
    {
        if (sessaoExiste('CLUBE_' . $this->id) && sessaoExiste('CLUBE')) {
            // return;
        }
        $host = eLocalhost() ? 'clube.marktclub.com.br' : str_replace(['http://', 'https://', '/'], '', LINK);
        $dado = $this
            ->validar(status: 404)
            ->get('/construtor-clube/clube/' . $host)
            ->object();
        sessao('CLUBE_' . $this->id, true);
        sessao('CLUBE', $this->montarClube($dado->dado));
    }

    public function montarClube($dado)
    {
        $dado->contato_telefone = strTelefone($dado->contato_telefone);
        $dado->contato_whatsapp = strTelefone($dado->contato_whatsapp);
        $menu = [];
        foreach ($dado->menu as $ind => $val) {
            $menu[$ind] = $val == 'sim';
        }
        $dado->menu = (object)$menu;
        $dado->api = $dado->api == 'sim';
        return $dado;
    }
}
