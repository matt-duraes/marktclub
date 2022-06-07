<li class="linha_erro">
    <p class="arquivo"><?= $linha->arquivo ?><span class="linha"><?= $linha->linha ?></span></p>
    <p class="mensagem"><?= $linha->mensagem ?></p>
    <ul>
        <?php foreach ($linha->trace as $trace) : ?>
            <li><?= $trace ?></li>
        <?php endforeach ?>
    </ul>
</li>
