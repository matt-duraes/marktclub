<div id="bloco_scope">
    <div class="bloco_acao">
        <div class="botao_acao">Abrir Todos</div>
        <div class="botao_acao">Marcar todos</div>
    </div>
    <div class="bloco_grupo">
        <header>
            <h2>Token</h2>
            <i class="mais"><?= iconeMais(10) ?></i>
            <i class="menos"><?= iconeMenos(2) ?></i>
        </header>
        <div class="lista">
            <div class="item">
                <?= formCheckbox(label: 'Salvar', value: 'salvar') ?>
            </div>
            <div class="item">
                <?= formCheckbox(label: 'Listar', value: 'listar') ?>
            </div>
            <div class="item">
                <?= formCheckbox(label: 'Revogar', value: 'revogar') ?>
            </div>
        </div>
    </div>
    <div class="bloco_grupo">
        <header>
            <h2>Usuário</h2>
            <i class="mais"><?= iconeMais(10) ?></i>
            <i class="menos"><?= iconeMenos(2) ?></i>
        </header>
        <div class="lista">
            <div class="item item_campo">
                <?= formCheckbox(label: 'Salvar', value: 'salvar') ?>
                <div class="campo">
                    <h3>Campos</h3>
                    <?= formCheckbox(label: 'Id', value: 'id') ?>
                    <?= formCheckbox(label: 'Nome', value: 'nome') ?>
                    <?= formCheckbox(label: 'E-mail', value: 'email') ?>
                </div>
            </div>
            <div class="item item_campo">
                <?= formCheckbox(label: 'Buscar', value: 'bsucar') ?>
                <div class="campo">
                    <h3>Campos</h3>
                    <?= formCheckbox(label: 'Id', value: 'id') ?>
                    <?= formCheckbox(label: 'Nome', value: 'nome') ?>
                    <?= formCheckbox(label: 'E-mail', value: 'email') ?>
                </div>
            </div>
        </div>
    </div>
</div>
