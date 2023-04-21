<!-- COMEÇO DO ERRO DO SISTEMA -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ERROR 500 - <?= $_mensagem ?> - linha: <?= $_linha ?></title>

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;600&display=swap" rel="stylesheet">
</head>

<body>
    <script id="__bs_script__">
        //<![CDATA[
        document.write("<script async src='/browser-sync/browser-sync-client.js?v=2.27.5'><\/script>".replace("HOST", location.hostname));
        //]]>
    </script>
    <div id="site" class="erro_<?= $_tipo ?>">

        <header>
            <div class="tipo_erro">
                <?php if ($_tipo == 'alerta') : ?>
                    <h2><strong>ALERTA</strong> - <span>Este erro não irá aparecer em produção.</span></h2>
                    <p>Alertas não aparecem em produção mas devem ser corrigidos para evitar que o sistema se comporte de maneira não esperada.</p>
                <?php elseif ($_tipo == 'depreciado') : ?>
                    <h2><strong>DEPRECIADO</strong> - <span>Este erro não irá aparecer em produção.</span></h2>
                    <p>Este erro não vai parar o código, mas você deve mudá-lo para uma versão que não esteja depreciada.</p>
                <?php else : ?>
                    <h2><strong>ERRO FATAL</strong> - <span>Este erro irá mostrar uma tela 500 em produção.</span></h2>
                    <p>Erro fatal irá "quebrar" seu código, você deve corrigir esse erro para continuar.</p>
                <?php endif; ?>
            </div>

            <?php if ($_tipo == 'alerta') : ?>
                <h1>ALERTA DE ERRO</h1>
            <?php elseif ($_tipo == 'depreciado') : ?>
                <h1>DEPRECIADO</h1>
            <?php else : ?>
                <h1>ERRO NA APLICAÇÃO</h1>
            <?php endif; ?>

            <div class="titulo">
                <div class="bloco_titulo">
                    <h2><?= $_arquivo ?></h2>
                    <?php if (empty($_arquivoAlerta)) : ?>
                        <span class="alerta">*<?= $_arquivoAlerta ?></span>
                    <?php endif; ?>
                    <?php if (!empty($_editor)) : ?>
                        <a class="editor" href="<?= $_editor ?>" target="_blank">
                            <svg width="20" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 30 30" x="0px" y="0px">
                                <path d="M14.307 14.728c-0.173 0.173-0.323 0.388-0.409 0.604l-1.469 3.256c-0.237 0.605 0.261 1.102 0.84 0.843l3.26-1.445c0.215-0.109 0.434-0.239 0.604-0.411l7.98-7.83c0.801-0.799 0.801-2.074 0-2.847-0.8-0.799-2.074-0.799-2.851 0l-7.956 7.83z" />
                                <path d="M23.237 17.187c-0.667 0-1.208 0.538-1.208 1.205v3.707c0 0.667-0.538 1.208-1.208 1.208h-10.936c-0.67 0-1.208-0.54-1.208-1.208v-10.96c0-0.669 0.538-1.206 1.208-1.206h3.556c0.665 0 1.208-0.545 1.208-1.21 0-0.667-0.542-1.208-1.208-1.208h-3.556c-1.986 0.023-3.601 1.642-3.601 3.625v10.959c0 1.986 1.614 3.601 3.601 3.601h10.959c1.984 0 3.602-1.614 3.602-3.601v-3.707c0-0.666-0.54-1.205-1.21-1.205z" />
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
                <p><?= $_mensagem ?></p>
                <p class="linha"><span>Linha: </span><strong><?= $_linha ?></strong></p>
            </div>

            <?php if (is_array($_sugestao) && isset($_sugestao['titulo']) && !empty($_sugestao['titulo'])) : ?>
                <div class="solucao">
                    <h3><?= $_sugestao['titulo'] ?></h3>
                    <?php if (isset($_sugestao['texto'])) : ?>
                        <p><?= $_sugestao['texto'] ?></p>
                    <?php endif; ?>
                    <?php if (isset($_sugestao['lista']) && $_sugestao['lista']) : ?>
                        <h4>Sugestões de correção:</h4>
                        <ul>
                            <?php foreach ($_sugestao['lista'] as $lista) : ?>
                                <li><?= $lista ?></li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($_traceString) : ?>
                <h3 class="titulo_pilha">Pilha de restreio:</h3>
                <ul class="bloco_trace">
                    <?php foreach ($_traceString as $linha) : ?>
                        <li class="lista"><?= $linha ?></li>
                    <?php endforeach; ?>
                </ul>
                <div class="trace_abrir"><button>Abrir pilha</button></div>
            <?php endif; ?>

        </header>

        <?php if ($_trace) : ?>
            <div class="bloco_codigo" id="bloco_codigo_geral">
                <div class="bloco_lista_arquivo" id="bloco_menu_trace">
                    <div class="titulo_arquivo">
                        Arquivos da pilha
                        <button class="menu_trace_fechar" id="botao_menu_trace_fechar">
                            <svg width="14" xmlns="http://www.w3.org/2000/svg" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" clip-rule="evenodd" fill-rule="evenodd" stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="5" version="1.1" viewBox="0 0 32 40" xml:space="preserve" x="0px" y="0px">
                                <path d="m5.4902 4.4902a1.0001 1.0001 0 0 0 -0.69727 1.7168l9.793 9.793-9.793 9.793a1.0001 1.0001 0 1 0 1.4141 1.4141l9.793-9.793 9.793 9.793a1.0001 1.0001 0 1 0 1.4141 -1.4141l-9.793-9.793 9.793-9.793a1.0001 1.0001 0 0 0 -0.72656 -1.7168 1.0001 1.0001 0 0 0 -0.6875 0.30273l-9.793 9.793-9.793-9.793a1.0001 1.0001 0 0 0 -0.7168 -0.30273z" clip-rule="nonzero" color="#000000" color-rendering="auto" dominant-baseline="auto" fill-rule="nonzero" image-rendering="auto" shape-rendering="auto" solid-color="#000000" style="font-feature-settings:normal;font-variant-alternates:normal;font-variant-caps:normal;font-variant-ligatures:normal;font-variant-numeric:normal;font-variant-position:normal;isolation:auto;mix-blend-mode:normal;text-decoration-color:#000000;text-decoration-line:none;text-decoration-style:solid;text-indent:0;text-orientation:mixed;text-transform:none;white-space:normal;" />
                            </svg>
                        </button>
                    </div>
                    <ul class="lista_arquivo" id="bloco_lista_trace">
                        <?php
                        $arquivoHover = false;
            foreach ($_trace as $id => $r) :
                $hover = '';
                if ($r['arquivo'] == $_arquivo && $r['linha'] == $_linha && !$arquivoHover) {
                    $arquivoHover = true;
                    $hover = 'hover';
                }
                ?>
                            <li class="botao_escolher_codigo lista <?= $hover ?>" data-linha="<?= $r['linha'] ?>" data-id="<?= $id ?>">
                                <p class="nome">
                                    <?= $r['arquivo'] ?>
                                    <?php if (!empty($r['alerta'])) : ?>
                                        <span class="alerta">*<?= $r['alerta'] ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($r['classe'])) : ?>
                                    <p class="classe"><?= $r['classe'] ?></p>
                                <?php endif; ?>
                                <?php if (!empty($r['funcao'])) : ?>
                                    <p class="funcao"><?= $r['funcao'] ?></p>
                                <?php endif; ?>
                                <p class="linha">Linha: <span><?= $r['linha'] ?></span></p>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php
                $codigoAtivo = false;
            foreach ($_trace as $id => $r) :
                $ativo = '';
                if ($r['arquivo'] == $_arquivo && $r['linha'] == $_linha && !$codigoAtivo) {
                    $codigoAtivo = true;
                    $ativo = 'codigo_ativo';
                }
                ?>
                    <div class="codigo bloco_codigo_geral <?= $ativo ?>" id="<?= $id ?>">
                        <div class="titulo" id="bloco_trace_titulo">
                            <button class="menu_trace botao_menu_trace">
                                <svg width="23" xmlns:dc="http://purl.org/dc/elements/1.1/" xmlns:cc="http://creativecommons.org/ns#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:svg="http://www.w3.org/2000/svg" xmlns="http://www.w3.org/2000/svg" xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd" xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape" version="1.1" x="0px" y="0px" viewBox="0 0 100 125">
                                    <g transform="translate(0,-952.36218)">
                                        <path d="m 16,969.36218 c -3.3137,0 -6,2.6862 -6,6 0,3.3136 2.6863,6 6,6 l 68,0 c 3.3137,0 6,-2.6864 6,-6 0,-3.3138 -2.6863,-6 -6,-6 z m 0,27 c -3.3137,0 -6,2.6862 -6,6.00002 0,3.3136 2.6863,6 6,6 l 68,0 c 3.3137,0 6,-2.6864 6,-6 0,-3.31382 -2.6863,-6.00002 -6,-6.00002 z m 0,27.00002 c -3.3137,0 -6,2.6862 -6,6 0,3.3136 2.6863,6 6,6 l 68,0 c 3.3137,0 6,-2.6864 6,-6 0,-3.3138 -2.6863,-6 -6,-6 z" style="text-indent:0;text-transform:none;direction:ltr;color:#000000;enable-background:accumulate;" fill="#999999" fill-opacity="1" stroke="none" marker="none" visibility="visible" display="inline" overflow="visible" />
                                    </g>
                                </svg>
                            </button>
                            <?php if (!empty($r['classeFuncao']['html'])) : ?>
                                <div class="funcao"><?= $r['classeFuncao']['html'] ?></div>
                            <?php endif; ?>
                            <div class="nome">
                                <p>
                                    <?= $r['arquivo'] ?>
                                    <?php if (!empty($r['alerta'])) : ?>
                                        <span class="alerta">*<?= $r['alerta'] ?></span>
                                    <?php endif; ?>
                                </p>
                                <?php if (!empty($r['editor'])) : ?>
                                    <a href="<?= $r['editor'] ?>" target="_blank">
                                        <svg width="20" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 32 40" x="0px" y="0px">
                                            <path d="M14.307 14.728c-0.173 0.173-0.323 0.388-0.409 0.604l-1.469 3.256c-0.237 0.605 0.261 1.102 0.84 0.843l3.26-1.445c0.215-0.109 0.434-0.239 0.604-0.411l7.98-7.83c0.801-0.799 0.801-2.074 0-2.847-0.8-0.799-2.074-0.799-2.851 0l-7.956 7.83z" />
                                            <path d="M23.237 17.187c-0.667 0-1.208 0.538-1.208 1.205v3.707c0 0.667-0.538 1.208-1.208 1.208h-10.936c-0.67 0-1.208-0.54-1.208-1.208v-10.96c0-0.669 0.538-1.206 1.208-1.206h3.556c0.665 0 1.208-0.545 1.208-1.21 0-0.667-0.542-1.208-1.208-1.208h-3.556c-1.986 0.023-3.601 1.642-3.601 3.625v10.959c0 1.986 1.614 3.601 3.601 3.601h10.959c1.984 0 3.602-1.614 3.602-3.601v-3.707c0-0.666-0.54-1.205-1.21-1.205z" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <pre class="conteudo"><?= $r['codigo'] ?></pre>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="bloco_info">
            <h1>Informações gerais</h1>

            <div class="bloco">
                <h2>Requisição</h2>
                <div class="dado">
                    <div class="nome">GET:</div>
                    <pre class="valor"><?php print_r($_GET) ?></pre>
                </div>
                <div class="dado">
                    <div class="nome">POST:</div>
                    <pre class="valor"><?php print_r($_POST) ?></pre>
                </div>
                <div class="dado">
                    <div class="nome">PUT:</div>
                    <pre class="valor"><?php print_r($_PUT) ?></pre>
                </div>
                <div class="dado">
                    <div class="nome">FILES:</div>
                    <pre class="valor"><?php print_r($_FILES) ?></pre>
                </div>
                <div class="dado">
                    <div class="nome">Método:</div>
                    <p class="valor"><?= $_metodo ?></p>
                </div>
                <div class="dado">
                    <div class="nome">Protocolo:</div>
                    <p class="valor"><?= $_url_protocolo ?></p>
                </div>
                <div class="dado">
                    <div class="nome">URL:</div>
                    <p class="valor"><?= $_url_dominio ?></p>
                </div>
                <div class="dado">
                    <div class="nome">URI:</div>
                    <p class="valor"><?= $_url_uri ?></p>
                </div>
                <div class="dado">
                    <div class="nome">Porta:</div>
                    <p class="valor"><?= $_url_porta ?></p>
                </div>
            </div>

            <div class="bloco">
                <h2>Header</h2>
                <?php if ($_header) : ?>
                    <?php foreach ($_header as $ind => $val) : ?>
                        <div class="dado">
                            <div class="nome"><?= $ind ?>:</div>
                            <p class="valor"><?= $val ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <div class="bloco">
                <h2>SERVER</h2>
                <?php if ($_server) : ?>
                    <?php foreach ($_server as $ind => $val) : ?>
                        <div class="dado">
                            <div class="nome"><?= $ind ?>:</div>
                            <p class="valor"><?= $val ?></p>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        </div>

        <?php if ($_alerta) : ?>
            <ul class="bloco_alerta">
                <?php foreach ($_alerta as $linha => $texto) : ?>
                    <li><?= $linha ?> - <?= $texto ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>
    <style>
        <?php include 'resetar.css' ?><?php include 'erro.css' ?>
    </style>
    <script>
        <?php include 'erro.js' ?>
    </script>
</body>

</html>
<!-- FINAL DO ERRO DO SISTEMA -->
