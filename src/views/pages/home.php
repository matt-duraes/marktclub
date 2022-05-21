<?= $render('header', ['loggedUser'=>$loggedUser]);?>
    <?php echo $loggedUser->name;?>
    <?php echo $loggedUser->permissao; ?>
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
        <?php 
        $permissoes = explode(',', $loggedUser->permissao);
        if(in_array('usuario_add', $permissoes)){
            echo '<a href="<?=$base;?>/cadastro" class="botao_add">Adicionar novo</a>';
        };
        ?>
        <div class="pagina">
            <p class="resultado">4 resultados</p>
            <a href="">Anterior</a>
            <a href="">Próxima</a>
        </div>
       
    </div>
</body>

</html>