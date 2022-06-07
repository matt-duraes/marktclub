    </div>

    <?php if (!empty($botao)) : ?>
        <footer class="footer_pagina_popup">
            <?= $html ?>
            <div class="flex"></div>
            <button type="button" <?= !empty($id) ? 'id="' . $id . '"' : '' ?> class="button botao_loading_geral">
                <p><?= $botao ?></p>
                <span><?= iconeLoadingBola() ?></span>
            </button>
        </footer>
    <?php endif; ?>

    <?php if ($form) : ?>
        </form>
    <?php else : ?>
        </div>
    <?php endif; ?>
