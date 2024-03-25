<div id="bloco_app_lista">
    <?php
        $URI = preg_replace(['/\&?pagina\=[0-9]+/', '/^\//'], '', URI);
    $URI = str_contains($URI, '?') ? $URI . '&' : $URI . '?';
    $appLink = str_replace('_', '-', $app);
    $replace = $config->index->replace;
    ?>
    <?php if ($filtro || !empty($busca->ordem)) : ?>
        <div id="bloco_app_filtro" class="bloco_filtro">

            <?php if ($filtro) : ?>
                <div class="filtro">
                    <?php foreach ($filtro as $ind => $val) : ?>
                        <div class="bloco"><?= $val[0] ?>:<span><?= painelConverterFiltroParaUsuario($val[1]) ?></span><button type="button" data-indice="<?= $ind ?>" class="botao_filtro_limpar"><?= iconeFechar(8) ?></button></div>
                    <?php endforeach; ?>
                </div>
                <?php if (!empty($busca->ordem)) : ?>
                    <a href="<?= LINK ?>/app/<?= $appLink ?>?ordem=<?= $busca->ordem ?? '' ?>" class="limpar" data-ajuda="Limpar todos os filtros"><?= iconeFechar(8) ?></a>
                <?php else : ?>
                    <a href="<?= LINK ?>/app/<?= $appLink ?>" class="limpar" data-ajuda="Limpar todos os filtros"><?= iconeFechar(8) ?></a>
                <?php endif; ?>
            <?php endif; ?>

            <?php if ($filtro && !empty($busca->ordem)) : ?>
                <div class="linha"></div>
            <?php elseif (!$filtro && !empty($busca->ordem)) : ?>
                <div class="filtro"></div>
            <?php endif; ?>

            <?php
            if (!empty($busca->ordem)) :
                $valorOrder = $busca->ordem;
                ?>
                <div class="bloco ordem">ordem:<span><?= $busca->ordem_titulo ?></span><button type="button" data-indice="ordem" class="botao_filtro_limpar"><?= iconeFechar(8) ?></button></div>
                <?php
            endif;
    ?>
        </div>
    <?php endif; ?>

    <div class="bloco_lista <?= $config->index->ultima_linha ? 'ultima_linha_destaque' : ''?>">
        <div class="lista titulo form_geral" id="bloco_app_titulo">
            <?php if ($config->permissao->drag) : ?>
                <div class="drag"></div>
            <?php endif; ?>
            <?php if ($config->permissao->deletar) : ?>
                <div class="checkbox">
                    <?= formCheckbox(name: 'marcar_todos', value: 1, label: '', check: false); ?>
                </div>
            <?php endif; ?>
            <div class="dado">
                <?php $primeiro = true; ?>
                <?php foreach ($config->index->grade as $grade) : ?>
                    <?php if (array_key_exists('formatar', $grade) && $grade['formatar'] == 'imagem' || array_key_exists('campo', $grade) && $grade['campo'] == 'usuario') : ?>
                        <div class="td imagem_usuario imagem"></div>
                    <?php elseif ($grade['tipo'] == 'status') : ?>
                        <div class="td status" data-titulo="<?= echoView($grade['nome']) ?>"><span class="bola" data-ajuda="<?= echoView($grade['nome']) ?>"></span></div>
                        <div class="barra"></div>
                    <?php elseif (in_array($grade['tipo'] ?? '', ['grande', 'normal', 'pequeno'])) : ?>
                        <div class="td <?= $primeiro ? 'primeiro' : '' ?> <?= $grade['tipo'] ?>"><?= echoView($grade['nome']) ?></div>
                        <div class="barra"></div>
                        <?php
                if ($primeiro) {
                    $primeiro = false;
                }
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="lista titulo titulo_fake form_geral">
            <?php if ($config->permissao->drag) : ?>
                <div class="drag"></div>
            <?php endif; ?>
            <?php if ($config->permissao->deletar) : ?>
                <div class="checkbox"></div>
            <?php endif; ?>
            <div class="dado">
                <?php $primeiro = true; ?>
                <?php foreach ($config->index->grade as $grade) : ?>
                    <?php if (array_key_exists('formatar', $grade) && $grade['formatar'] == 'imagem' || array_key_exists('campo', $grade) && $grade['campo'] == 'usuario') : ?>
                        <div class="td imagem_usuario imagem"></div>
                    <?php elseif ($grade['tipo'] == 'status') : ?>
                        <div class="td status" data-titulo="<?= echoView($grade['nome']) ?>"><span class="bola" data-ajuda="<?= echoView($grade['nome']) ?>"></span></div>
                        <div class="barra"></div>
                    <?php elseif (in_array($grade['tipo'] ?? '', ['grande', 'normal', 'pequeno'])) : ?>
                        <div class="td <?= $primeiro ? 'primeiro' : '' ?> <?= $grade['tipo'] ?>"><?= echoView($grade['nome']) ?></div>
                        <div class="barra"></div>
                        <?php
                        if ($primeiro) {
                            $primeiro = false;
                        }
                        ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <?php if (isset($dado->lista) && !vazio($dado->lista)) : ?>
            <?php foreach ($dado->lista as $r) : ?>
                <?php
                    $r = is_array($r) ? (object)$r : $r;
                $id = $r->id ?? '';
                ?>
                <div class="linha">
                    <div class="lista geral form_geral">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <?php if ($config->permissao->drag) : ?>
                            <div class="drag"><?= iconeDrag() ?></div>
                        <?php endif; ?>

                        <?php if ($config->permissao->deletar) : ?>
                            <div class="checkbox">
                                <?= formCheckbox(
                                    name: 'id_' . $id,
                                    value: $id,
                                    label: '',
                                    check: false
                                ); ?>
                            </div>
                        <?php endif; ?>

                        <?php if ($config->permissao->editar || $config->permissao->visualizar) : ?>
                            <a href="<?= str_replace(['{app}', '{id}'], [$appLink, $id], $config->abrir) ?>" class="dado">
                        <?php else : ?>
                                <div class="dado">
                        <?php endif; ?>
                                <?php $primeiro = true; ?>
                                <?php foreach ($config->index->grade as $grade) : ?>
                                    <?php if (array_key_exists('formatar', $grade) && $grade['formatar'] == 'imagem' || array_key_exists('campo', $grade) && $grade['campo'] == 'usuario') : ?>
                                        <div class="td imagem_usuario imagem"><figure data-ajuda="<?= $r->usuario->nome ?>" style="background-image: url(<?= $r->usuario->imagem ?>)"></figure></div>
                                    <?php elseif ($grade['tipo'] == 'status') : ?>
                                            <?php
                                                                            $statusValor = painelValor($r, $grade['campo']);
                                        $texto = '';
                                        $cor = '';
                                        if (array_key_exists($statusValor, $grade['valor'])) {
                                            $texto = $grade['valor'][$statusValor]['nome'];
                                            $cor = painelCor($grade['valor'][$statusValor]['cor']);
                                        }
                                        ?>
                                        <?php if (empty($texto)) : ?>
                                        <div class="td status"  data-titulo="">
                                        </div>
                                        <?php else : ?>
                                        <div class="td status" data-titulo="<?= echoView($texto) ?>" data-ajuda="<?= echoView($texto) ?>">
                                            <span style="background-color: <?= $cor ?>"></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="barra"></div>
                                    <?php elseif (in_array($grade['tipo'] ?? '', ['grande', 'normal', 'pequeno']) && isset($grade['copiar']) && $grade['copiar']) : ?>
                                        <div class="td <?= $grade['tipo'] ?> bloco_copiar">
                                            <?php
                                            $valor = painelValor($r, $grade['campo'], formatar: $grade['formatar'] ?? '');
                                        if (array_key_exists($grade['campo'], $replace)) {
                                            $valor = $replace[$grade['campo']][$valor] ?? $valor;
                                        }
                                        echo $valor;
                                        ?>
                                            <div class="botao_copiar copiar" data-ajuda="Copiar"><?= iconeCopiar() ?></div>
                                        </div>
                                        <div class="barra"></div>
                                    <?php elseif (in_array($grade['tipo'] ?? '', ['grande', 'normal', 'pequeno'])) : ?>
                                        <div class="td <?= $primeiro == true ? 'primeiro' : ''?> <?= $grade['tipo'] ?>">
                                            <?php
                                            $valor = painelValor($r, $grade['campo'], formatar: $grade['formatar'] ?? '');
                                        if (array_key_exists($grade['campo'], $replace)) {
                                            $valor = $replace[$grade['campo']][$valor] ?? $valor;
                                        }
                                        echo $valor;
                                        ?>
                                        </div>
                                        <div class="barra"></div>
                                        <?php
                                        if ($primeiro) {
                                            $primeiro = false;
                                        }
                                        ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if ($config->permissao->editar || $config->permissao->visualizar) : ?>
                            </a>
                                <?php else : ?>
                    </div>
                                <?php endif; ?>
                </div>
    </div>
            <?php endforeach; ?>
        <?php elseif ($filtro) : ?>
    <div class="sem_registro">
        <div class="conteudo">
            <i><?= iconeBuscar() ?></i>
            <h2>SEM REGISTROS PELA BUSCAR INFORMADA</h2>
            <p>Refaça ou remova sua busca para continuar</p>
            <a class="limpar" href="<?= LINK ?>/app/<?= $appLink ?><?= !empty($busca->ordem) ? '?ordem=' . $busca->ordem : '' ?>">LIMPAR BUSCA</a>
        </div>
    </div>
        <?php elseif ($dado->pagina->total > 0 && $dado->pagina->atual > $dado->pagina->total) : ?>
    <div class="sem_registro">
        <div class="conteudo">
            <i><?= iconeAdd(20) ?></i>
            <h2>SEM REGISTROS NA PÁGINA</h2>
            <p>Não existem registro na página procurada, por favor, clique no botão abaixo para voltar ao início</p>
            <a class="add" href="<?= LINK ?>/<?= $URI ?>pagina=1">VOLTAR</a>
        </div>
    </div>
        <?php else : ?>
    <div class="sem_registro">
        <div class="conteudo">
            <i><?= iconeAdd(20) ?></i>
            <h2>SEM REGISTROS CADASTRADOS</h2>
            <p>Não existem registros cadastrados, por favor, cadastre um novo registro caso tenha permissão</p>
            <?php if ($config->permissao->add) : ?>
                <a class="add" href="<?= LINK ?>/app/add/<?= $appLink ?>">ADD</a>
            <?php endif; ?>
        </div>
    </div>
        <?php endif; ?>
</div>

<?php if ($dado->registro->total > 0 && $dado->pagina->atual > 0 && $dado->pagina->atual <= $dado->pagina->total) : ?>
    <div class="bloco_paginacao_numero">
        <div class="bg">
            <div class="registro"><?= $dado->registro->inicio ?> - <?= $dado->registro->final ?> de <?= $dado->registro->total ?></div>
            <?php if (isset($dado->pagina) && $dado->pagina->total > 1) : ?>
                <?php if ($dado->pagina->atual == 1) : ?>
                    <div class="seta"><?= iconeSetaEsquerda(16) ?></div>
                    <a href="<?= LINK ?>/<?= $URI ?>pagina=2" class="seta"><?= iconeSetaDireita(16) ?></a>
                <?php elseif ($dado->pagina->atual == $dado->pagina->total) : ?>
                    <a href="<?= LINK ?>/<?= $URI ?>pagina=<?= $dado->pagina->atual - 1 ?>" class="seta"><?= iconeSetaEsquerda(16) ?></a>
                    <div class="seta"><?= iconeSetaDireita(16) ?></div>
                <?php elseif ($dado->pagina->atual < $dado->pagina->total) : ?>
                    <a href="<?= LINK ?>/<?= $URI ?>pagina=<?= $dado->pagina->atual - 1 ?>" class="seta"><?= iconeSetaEsquerda(16) ?></a>
                    <a href="<?= LINK ?>/<?= $URI ?>pagina=<?= $dado->pagina->atual + 1 ?>" class="seta"><?= iconeSetaDireita(16) ?></a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<?php echo botaoControle(
    $app,
    add: 'botao_add_geral',
    addLink: LINK . '/app/add/' . $appLink,
    addPermissao: $config->permissao->add,
    deletar: 'botao_deletar_geral',
    deletarPermissao: $config->permissao->deletar && $dado->registro->total > 0 && $dado->pagina->atual <= $dado->pagina->total,
    download: 'botao_download_geral',
    downloadPermissao: $config->permissao->download && $dado->registro->total > 0 && $dado->pagina->atual <= $dado->pagina->total,
    downloadQuantidade: $dado->registro->total,
    copiarPermissao: $config->index->copiar && object_key_exists('lista', $dado) && !vazio($dado->lista)
) ?>

</div>

<form action="/" id="app_lista_form">
    <?= formHash('HASH_DELETAR', 'input_hash_deletar') ?>
    <?= formHash('HASH_ORDEM', 'input_hash_ordem') ?>
    <input type="hidden" name="ordem" value="<?= $busca->ordem ?>">
    <input type="hidden" name="pesquisa" value="<?= $busca->pesquisa ?>">
    <input type="hidden" name="filtro" value="<?= $busca->filtro ?>">
    <input type="hidden" name="pagina" value="<?= $dado->pagina->atual ?>">
</form>
