<?php

namespace App\Models\Api\View\Html;

use ORM\ORM;

final class GrupoModel extends ORM
{
    protected string $ormTabela = TABELA_VIEW_HTML;

    public function __construct(
        private array $grupo
    ) {
        parent::__construct();
        $this->atualizarGrupo();
    }

    private function atualizarGrupo()
    {
        $idLista = [];
        foreach ($this->grupo as $r) {
            $pai = $r['pai'];
            if (!in_array($pai, $idLista)) {
                $idLista[$pai] = $this->campo(['id'])->where(['uuid', $pai])->primeiro('id');
            }
            $dado = $this
                ->dado([
                    'id_view_html'    => $idLista[$pai],
                    'margem_topo'     => $r['margem_topo'],
                    'margem_direita'  => $r['margem_direita'],
                    'margem_baixo'    => $r['margem_baixo'],
                    'margem_esquerda' => $r['margem_esquerda'],
                    'ordem'           => $r['ordem'],
                ])
                ->where(['uuid', $r['id']])
                ->update();
            if (!is_array($dado) || !array_key_exists('id', $dado)) {
                mensagemErro('Erro!', 'Não foi possível atualizar um ou mais itens.');
            }
        }
    }
}
