<?= $render('header', ['loggedUser'=>$loggedUser]);?>
    <?php echo $loggedUser->name;?>
    <?php echo $loggedUser->permissao; ?>

       
        <?php $permissoes = explode(',', $loggedUser->permissao);?>
        <?php if(in_array('usuario_add', $permissoes)):?>
            <a href="<?=$base;?>/cadastro" class='botao_add'>Adicionar novo</a>
        
        <?php endif;?>
        

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
            <?php foreach($user as $user):?>    
            <li class="dado">
                <div class="texto nome"><?=$user['name'];?></div>
                <div class="texto cpf"><?=$user['cpf'];?></div>
                <div class="texto email"><?=$user['email'];?></div>
                <div class="texto data">10/10/2021</div>
                <div class="texto status"><?=$user['status'];?></div>
                <div class="editar"><a href="form.php"><img src="<?=$base;?>/assets/images/editar.svg"></a></div>
                <div class="deletar">
                    <a href="">
                        <img src="<?=$base;?>/assets/deletar.svg">
                    </a>
                </div>
            </li>
            <?php endforeach?>
        </ul>
        <div class="pagina">
            
                <p class="resultado"> <?php echo $total?> resultados</p>
                <form method="post"> 
                       <a href=""><input type="submit" name="anterior" value="Anterior"/></a>
                <a><input type="submit" name="proxima" value="Proxima"/></a>
            </form>
            <?php 
                if(isset_($_POST['anterior'])){
                    $offset = $offset - 5;
                }
                if(isset_($_POST['proxima'])){
                    $offset = $offset + 5;
                }

            ?>
            
            
        </div>

                
    </div>
</body>

</html>