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

foreach ($html as $coluna) {
    $colunaQuantidade = count($coluna);
    if (isset($coluna['coluna'])) {
        $colunaQuantidade = $coluna['coluna'];
        unset($coluna['coluna']);
    }
    painelColuna($colunaQuantidade);
    foreach ($coluna as $fieldset) {
        painelFieldset($fieldset['titulo'] ?? null);
        painelInputLista($fieldset['lista'], $r);
        painelFieldsetEnd();
    }
    painelColunaEnd();
}
painelAppAddEnd();
