
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
   <link rel="stylesheet" href="<?=$base;?>/assets/css/pesquisa.css"> 
</head>

<body>
    <div id="site">
        <header>
            <h1>USUÁRIOS</h1>
            <form class="busca" action="<?=$base;?>/pesquisa" method="get">
                <label>  
                    <input type="text" name="search" placeholder="Pesquisar...">
                </label>
            <button><i><img src="<?=$base;?>/assets/images/lupa.svg"></i></button>    
            </form>
            <a href="<?=$base;?>">
                <figure></figure>
            </a>
            <a class="sair" href="<?=$base;?>/sair">sair</a>
        </header>
    
    <ul>
            <li class="titulo">
                <div class="texto nome">Nome</div>
            </li>
            <li class="titulo">Você pesquisou por:  <?php echo $searchTerm?></li>
            <?php foreach($users as $user):?>     
            <li class="dado">
            <div class="texto nome"><?=$user->name;?></div>
               
            </li>
            <?php endforeach?>
        </ul>
</body>

</html>

