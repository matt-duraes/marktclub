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
        $busca = $this
            ->validar(status: 404)
            ->get('/view-pagina/' . $this->url)
            ->object()->dado;
        $busca->html = $this->montarRetorno($busca->html);
        $this->busca = $busca;
        sessao($sessao, $this->busca);
    }

    private function montarRetorno($dado)
    {
        $empresa = sessao('CLUBE')->empresa;
        $retorno = [];
        foreach ($dado as $r) {
            if (
                $r->status != 'sim' ||
                (!empty($r->empresa_ativa) && !in_array($empresa, $r->empresa_ativa)) ||
                (!empty($r->empresa_inativa) && in_array($empresa, $r->empresa_inativa))
            ) {
                continue;
            }
            foreach ($r as $ind => $val) {
                if (
                    in_array($ind, ['titulo', 'texto', 'tabela', 'lista_valor', 'link_empresa', 'api_body']) &&
                    !empty($val) && is_string($val)
                ) {
                    $r->$ind = jsonDecode($val, true, true);
                }
            }
            if ($r->lista) {
                $r->lista = $this->montarRetorno($r->lista);
            }
            $retorno[] = $r;
        }
        return $retorno;
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
