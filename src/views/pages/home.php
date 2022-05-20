<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?=$base;?>/assets/css/index.css">
</head>

<body>
    <div id="site">
        <header>
            <h1>USUÁRIOS</h1>
            <form class="busca" action="">
                <i><img src="<?=$base;?>/assets/images/lupa.svg"></i>
                <input type="text" name="pesquisa" placeholder="Pesquisar...">
            </form>
            <figure></figure>
            <a class="sair" href="<?=$base;?>/login.php">sair</a>
        </header>

        <ul>
            <li class="titulo">
                <div class="texto nome">Nome</div>
                <div class="texto cpf">CPF</div>
                <div class="texto email">E-MAIL</div>
                <div class="texto data">DATA</div>
                <div class="texto status">STATUS</div>
                <div class="editar"></div>
                <div class="deletar"></div>
            </li>
            <li class="dado">
                <div class="texto nome">Nome do usuário</div>
                <div class="texto cpf">000.000.000-00</div>
                <div class="texto email">email@dominio.com.br</div>
                <div class="texto data">10/10/2021</div>
                <div class="texto status">Ativo</div>
                <div class="editar"><a href="form.php"><img src="<?=$base;?>/images/editar.svg"></a></div>
                <div class="deletar"><img src="<?=$base;?>/images/deletar.svg"></div>
            </li>
            <li class="dado">
                <div class="texto nome">Nome do usuário</div>
                <div class="texto cpf">000.000.000-00</div>
                <div class="texto email">email@dominio.com.br</div>
                <div class="texto data">10/10/2021</div>
                <div class="texto status">Ativo</div>
                <div class="editar"><a href="form.php"><img src="<?=$base;?>/assets/images/editar.svg"></a></div>
                <div class="deletar"><img src="<?=$base;?>/assets/deletar.svg"></div>
            </li>
            <li class="dado">
                <div class="texto nome">Nome do usuário</div>
                <div class="texto cpf">000.000.000-00</div>
                <div class="texto email">email@dominio.com.br</div>
                <div class="texto data">10/10/2021</div>
                <div class="texto status">Ativo</div>
                <div class="editar"><a href="form.php"><img src="<?=$base;?>/assetsimages/editar.svg"></a></div>
                <div class="deletar"><img src="<?=$base;?>/assets/images/deletar.svg"></div>
            </li>
            <li class="dado">
                <div class="texto nome">Nome do usuário</div>
                <div class="texto cpf">000.000.000-00</div>
                <div class="texto email">email@dominio.com.br</div>
                <div class="texto data">10/10/2021</div>
                <div class="texto status">Ativo</div>
                <div class="editar"><a href="form.php"><img src="<?=$base;?>/assets/images/editar.svg"></a></div>
                <div class="deletar"><img src="<?=$base;?>/assets/images/deletar.svg"></div>
            </li>
        </ul>
        <div class="pagina">
            <p class="resultado">4 resultados</p>
            <a href="">Anterior</a>
            <a href="">Próxima</a>
        </div>
        <a href="<?=$base;?>/cadastro" class="botao_add">Adicionar novo</a>
    </div>
</body>

</html>
