<?php

echo formHash('painel_add', 'hash_id');
painelAppAdd();
if (!vazio($r) && object_key_exists('id', $r)) {
    echo '<input type="hidden" name="id" value="' . $r->id . '">';
}

if (!vazio($status)) {
    echo '<input type="hidden" id="input_status_sistema" name="status_sistema" value="' . $status . '">';
}

echo '
<div class="login">
    <input type="text" autocomplete="username" value="">
    <input type="password" autocomplete="current-password" value="">
</div>
';

$permissaoEditar = $config->permissao->editar == 1 ? 'sim' : 'nao';
$permissaoVisualizar = $config->permissao->visualizar == 1 ? 'sim' : 'nao';
echo '<input type="hidden" id="inputinterno_permissao_editar" value="' . $permissaoEditar . '">';
echo '<input type="hidden" id="inputinterno_permissao_visualizar" value="' . $permissaoVisualizar . '">';

foreach ($html as $coluna) {
    $colunaQuantidade = count($coluna);
    if (isset($coluna['coluna'])) {
        $colunaQuantidade = $coluna['coluna'];
        unset($coluna['coluna']);
    }
    painelColuna($colunaQuantidade);
    foreach ($coluna as $fieldset) {
        painelFieldset($fieldset['titulo'] ?? null, $fieldset['abrir'] ?? false, $fieldset['row'] ?? false);
        painelInputLista($fieldset['lista'], $r);
        painelFieldsetEnd();
    }
    painelColunaEnd();
}
painelAppAddEnd();
