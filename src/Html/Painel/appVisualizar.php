<?php

painelAppVisualizar();
echo '<input type="hidden" id="input_visualizar_id" value="' . $r->id . '">';
echo '<input type="hidden" id="input_visualizar_app" value="' . $app . '">';

foreach ($config->visualizar->html as $coluna) {
    $colunaQuantidade = count($coluna);
    if (array_key_exists('coluna', $coluna)) {
        $colunaQuantidade = $coluna['coluna'];
        unset($coluna['coluna']);
    }
    painelColuna($colunaQuantidade);
    if (array_key_exists('lista', $coluna)) {
        painelLinhaLista($coluna['lista'], $r, $config->visualizar->replace);
    } else {
        foreach ($coluna as $fieldset) {
            if (array_key_exists('titulo', $fieldset)) {
                painelFieldset($fieldset['titulo'] ?? null, $fieldset['abrir'] ?? false);
            }
            painelLinhaLista($fieldset['lista'], $r, $config->visualizar->replace);
            if (array_key_exists('titulo', $fieldset)) {
                painelFieldsetEnd();
            }
        }
    }
    painelColunaEnd();
}
painelAppVisualizarEnd();

if ($config->permissao->editar->permissao) {
    $editar = true;
    foreach ($config->permissao->editar->campo as $ind => $val) {
        if (!object_key_exists($ind, $r) || $r->$ind != $val) {
            $editar = false;
            break;
        }
    }
    if ($editar) {
        echo botaoControle($app, editar: 'botao_editar_visualizar', editarTexto: 'Editar', editarLink: str_replace('{id}', $r->id, $config->link->editar));
    }
}
