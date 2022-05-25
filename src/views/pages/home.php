
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
                <div class="texto status"></div>
                <div class="texto status">STATUS</div>
                <div class="editar"></div>
                <div class="deletar"></div>
            </li>
            <?php foreach($user as $user):?>    
            <li class="dado">
                <div class="texto nome"><?=$user['name'];?></div>
                <div class="texto cpf"><?=$user['cpf'];?></div>
                <div class="texto email"><?=$user['email'];?></div>
                <div class="texto data "></div>
                <div class="texto status">
                 <?php if($user['status'] == 1):?>
                    Ativo
                    <?php else:?>
                    Inativo
                 <?php endif;?>
                </div>    
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
            
                <?php for($q=1;$q<=$pages;$q++):?>
                <a href="<?php $base;?>?p=<?php echo $q;?>">
                    <?php echo $q;?>
                </a>
            <?php endfor;?>
        </div>

                
    </div>
</body>

