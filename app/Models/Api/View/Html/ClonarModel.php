<?php

namespace App\Models\Api\View\Html;

use ORM\ORM;

final class ClonarModel extends RetornoModel
{
    protected string $ormTabela = TABELA_VIEW_HTML;
    private array $original = [];
    protected array $dado = [];
    private array $replaceId = [];
    public array $retorno = [];

    public function __construct(
        string $id
    )
    {
        parent::__construct();
        $this->buscarEstrutura($id);
        $this->salvarLista();
        $this->dado[0]->id_view_html = null;
        $this->montarRetorno();
    }

    private function salvarLista()
    {
        foreach($this->original as $item) {
            $pai = $item['item']['id_view_html'];
            $item['item']['id_view_html'] = $this->replaceId[$pai] ?? $pai;
            $dado = $this->dado($item['item'])->insert();
            $this->dado[] = (object)$dado;
            $this->replaceId[$item['id']] = $dado['id'];
        }
    }

    private function buscarEstrutura(string $id)
    {
        $busca = $this->where(['uuid', $id])->primeiro();
        $id = $busca->id;
        $this->original[] = $this->adicionarItem($busca);
        $this->buscarEstruturaItem($id);
    }

    private function buscarEstruturaItem(int $id)
    {
        $filho = $this->where(['id_view_html', $id])->read();
        if(!$filho) {
            return;
        }

        foreach($filho as $r) {
            $id = $r->id;
            $this->original[] = $this->adicionarItem($r);
            $this->buscarEstruturaItem($id);
        }
    }

    private function adicionarItem($item)
    {
        $id = $item->id;
        unset($item->id, $item->uuid);
        $this->replaceId[$id] = null;
        return [
            'id' => $id,
            'item' => (array)$item
        ];
    }
}
