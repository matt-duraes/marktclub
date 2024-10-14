<?php

namespace App\Models\Site\Pagina;

use stdClass;
use App\Helpers\ClubeApiHelper;

final class BuscarModel extends ClubeApiHelper
{
    public array $html = [];
    public string $titulo = '';
    public string $descricao = '';
    public string $imagem = '';
    private stdClass $busca;

    public function __construct(
        private string $url
    ) {
        parent::__construct();
        $this->busca = (object)[];
        $this->buscarPagina();
        if (!object_key_exists('id', $this->busca)) {
            return;
        }
        $this->setarPropriedade();
    }

    private function buscarPagina()
    {
        $sessao = 'PAGINA_' . strCaixaAlta(str_replace('/', '_', $this->url));
        if (sessaoExiste($sessao) && eProducao()) {
            $this->busca = sessao($sessao);
            return;
        }
        $this->busca = $this
            ->validar(status: 404)
            ->get('/view-pagina/' . $this->url)
            ->object()->dado;
        sessao($sessao, $this->busca);
    }

    private function setarPropriedade()
    {
        $busca = $this->busca;
        $this->html = (array)$busca->html;
        $this->titulo = $busca->titulo;
        $this->descricao = '';
        $this->imagem = '';
    }
}
