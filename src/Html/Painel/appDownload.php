<?php

painelAppDownload(app: $app);
echo '<input type="hidden" name="pesquisa" value="' . $pesquisa . '">';
echo '<input type="hidden" name="filtro" value="' . $filtro . '">';
echo '<input type="hidden" name="ordem" value="' . $ordem . '">';
echo formCheckbox(name: '', label: 'Marcar/Desmarcar todos', check: false, class: 'botao_marcar_desmarcar_download');
foreach ($html as $bloco) {
    echo '<div class="bloco">';
    if ($bloco['titulo']) {
        echo '<h1>' . preg_replace('/\:$/', '', $bloco['titulo']) . ':</h1>';
    }
    foreach ($bloco['lista'] as $ind => $val) {
        echo formCheckbox(name: 'campo[]', value: $ind, label: $val, check: false, class: 'input_download');
    }
    echo '</div>';
}
painelAppDownloadEnd(2);
