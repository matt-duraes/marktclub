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
                    'id_view_html'            => $idLista[$pai],
                    'margem_topo_desktop'     => $r['margem_topo_desktop'],
                    'margem_topo_mobile'      => $r['margem_topo_mobile'],
                    'margem_direita_desktop'  => $r['margem_direita_desktop'],
                    'margem_direita_mobile'   => $r['margem_direita_mobile'],
                    'margem_baixo_desktop'    => $r['margem_baixo_desktop'],
                    'margem_baixo_mobile'     => $r['margem_baixo_mobile'],
                    'margem_esquerda_desktop' => $r['margem_esquerda_desktop'],
                    'margem_esquerda_mobile'  => $r['margem_esquerda_mobile'],
                    'minimizado'              => $r['minimizado'] == 'sim',
                    'ordem'                   => $r['ordem'],
                ])
                ->where(['uuid', $r['id']])
                ->update();
            if (!is_array($dado) || !array_key_exists('id', $dado)) {
                mensagemErro('Erro!', 'Não foi possível atualizar um ou mais itens.');
            }
        }
    }
}
