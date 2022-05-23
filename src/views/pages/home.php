
<?= $render('header', ['loggedUser'=>$loggedUser]);?>
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
                <?php if(in_array('usuario_editar', $permissoes)):?>
                <div class="editar"><a href="<?=$base;?>/usuario/<?=$user['id']?>/editar"><img src="<?=$base;?>/assets/images/editar.svg"></a></div>
                <?php endif;?>
                <?php if(in_array('usuario_deletar', $permissoes)):?>
                <div class="deletar">
                    <a href="<?=$base;?>/usuario/<?=$user['id']?>/excluir" onclick="return confirm('Deseja excluir?')">
                        <img src="<?=$base;?>/assets/images/deletar.svg">
                    </a>
                </div>
                <?php endif;?>
            </li>
            <?php endforeach?>
        </ul>
        <div class="pagina">
                
                <p class="resultado"> <?php echo $total?> resultados</p>
            
             
                <a href=""><input type="submit" name="anterior" value="Anterior"/></a>
                <a><input type="submit" name="proxima" value="Proxima"/></a>
                

            
        </div>

                
    </div>
</body>

</html>