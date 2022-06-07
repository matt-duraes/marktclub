<?php if ($form): ?>
<form action="<?=$action?>" method="<?=$method?>" <?=!empty($id) ? 'id="' . $id . '"' : '' ?> class="form_geral bloco_pagina_popup">
<?php else: ?>
<div <?=!empty($id) ? 'id="' . $id . '"' : '' ?> class="form_geral bloco_pagina_popup">
<?php endif; ?>

    <header class="header_pagina_popup">
        <?php if ($fechar): ?>
        <i class="mobile botao_fechar_popup"><?= iconeVoltar() ?></i>
        <?php endif; ?>
        <h1><?=$titulo?></h1>
        <?php if ($fechar): ?>
        <i class="desktop botao_fechar_popup"><?= iconeFechar() ?></i>
        <?php endif; ?>
    </header>
    <div class="conteudo_pagina_popup">
