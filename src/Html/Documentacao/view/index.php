<!DOCTYPE HTML>
<html lang="pt-br">

<head>
    <meta charset="utf-8" />
    <meta name="robots" content="noindex, nofolow">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>DOCUMENTAÇÃO</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
</head>

<body>

    <div id="site">
        <header id="header">
            <button id="botao_menu">
                <span class="linha_1"></span>
                <span class="linha_2"></span>
                <span class="linha_3"></span>
            </button>
            <h1>DOCUMENTAÇÃO</h1>
        </header>
        <nav>
            <div class="conteudo">
                <div class="grupo">
                    <p>GET Started</p>
                    <div class="menu">
                        <a href="/__documentacao/start">Depêndencias</a>
                        <a href="/__documentacao/start/configuracao">Configurações</a>
                        <a href="/__documentacao/start/env">Arquivo env</a>
                        <a href="/__documentacao/start/estrutura">Estrutura do FW</a>
                        <a href="/__documentacao/start/pagina">Criando uma página</a>
                        <a href="/__documentacao/start/plugins">Plugins</a>
                        <a href="/__documentacao/start/normas">Boas práticas</a>
                    </div>
                </div>
                <div class="grupo">
                    <p>Páginas</p>
                    <div class="menu">
                        <a href="/__documentacao/pagina/route">Route</a>
                        <a href="/__documentacao/pagina/controller">Controller</a>
                        <a href="/__documentacao/pagina/request">Request</a>
                        <a href="/__documentacao/pagina/response">Response</a>
                        <a href="/__documentacao/pagina/app">App</a>
                        <a href="/__documentacao/pagina/view">View</a>
                        <a href="/__documentacao/pagina/css">CSS</a>
                        <a href="/__documentacao/pagina/js">JS</a>
                    </div>
                </div>
                <div class="grupo">
                    <p>Modelos</p>
                    <div class="menu">
                        <a href="/__documentacao/modelo/geral">Geral</a>
                        <a href="/__documentacao/modelo/ordem">Ordem</a>
                        <a href="/__documentacao/modelo/status">Status</a>
                        <a href="/__documentacao/modelo/botao">Botão</a>
                        <a href="/__documentacao/modelo/cnpj">CNPJ</a>
                        <a href="/__documentacao/modelo/cpf">CPF</a>
                        <a href="/__documentacao/modelo/data">Data</a>
                        <a href="/__documentacao/modelo/datahora">Data e hora</a>
                        <a href="/__documentacao/modelo/decimal">Decimal</a>
                        <a href="/__documentacao/modelo/dinheiro">Dinheiro</a>
                        <a href="/__documentacao/modelo/email">E-mail</a>
                        <a href="/__documentacao/modelo/cep">CEP</a>
                        <a href="/__documentacao/modelo/estado">UF</a>
                        <a href="/__documentacao/modelo/estadocivil">Estado Civil</a>
                        <a href="/__documentacao/modelo/genero">Gênero</a>
                        <a href="/__documentacao/modelo/nome">Nome</a>
                        <a href="/__documentacao/modelo/senha">Senha</a>
                        <a href="/__documentacao/modelo/telefone">Telefone</a>
                    </div>
                </div>
                <div class="grupo">
                    <p>Banco de dados</p>
                    <div class="menu">
                        <a href="/__documentacao/banco/banco-de-dados">Criar bancos</a>
                        <a href="/__documentacao/banco/orm">ORM</a>
                        <a href="/__documentacao/banco/entity">Entity</a>
                    </div>
                </div>
                <div class="grupo">
                    <p>Funções PHP</p>
                    <div class="menu">
                        <a href="/__documentacao/funcao-php/aleatorio">Dados Aleatórios</a>
                        <a href="/__documentacao/funcao-php/data">Datas</a>
                        <a href="/__documentacao/funcao-php/form">Formulários</a>
                        <a href="/__documentacao/funcao-php/mensagem">Mensagem</a>
                        <a href="/__documentacao/funcao-php/icone">Icones</a>
                        <a href="/__documentacao/funcao-php/texto">Textos</a>
                        <a href="/__documentacao/funcao-php/validacao">Validação</a>
                        <a href="/__documentacao/funcao-php/gerais">Gerais</a>
                    </div>
                </div>
                <div class="grupo">
                    <p>Plugins JS</p>
                    <div class="menu">
                        <a href="/__documentacao/plugin-js/alerta">Alerta</a>
                    </div>
                </div>
            </div>
        </nav>
        <main id="main">
            <div class="centralizar">
                <div class="conteudo">
                    <?php
                    if (file_exists(__DIR__ . '/' . $pagina . '/' . $view . '.php')) :
                        include __DIR__ . '/' . $pagina . '/' . $view . '.php';
                    endif;
                    ?>
                </div>
            </div>
        </main>
    </div>
    <style>
        <?php
        require_once __DIR__ . '/../css/resetar.css';
                    require_once __DIR__ . '/../css/Ckeditor.system.css';
                    require_once __DIR__ . '/../css/Form.system.css';
                    require_once __DIR__ . '/../css/Codigo.system.css';
                    require_once __DIR__ . '/../css/Alerta.system.css';
                    require_once __DIR__ . '/../css/layout.css';
        ?>
    </style>

    <script>
        <?php
                    require_once __DIR__ . '/../js/all.js';
                    require_once ROOT . '/src/Html/Scripts/js/Codigo.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/ArquivoUpload.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/Ckeditor.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/Player.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/Alerta.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/Mascara.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/Calendario.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/Galeria.system.js';
                    require_once ROOT . '/src/Html/Scripts/js/Form.init.js';
                    require_once ROOT . '/src/Html/Scripts/js/Form.select.js';
                    require_once ROOT . '/src/Html/Scripts/js/Form.cor.js';
                    require_once ROOT . '/src/Html/Scripts/js/Form.tag.js';
                    require_once ROOT . '/src/Html/Scripts/js/Form.system.js';
        ?>
    </script>
</body>

</html>
