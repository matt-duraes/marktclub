
<div class="pagina">
            <p class="resultado">4 resultados</p>
            <a href="">Anterior</a>
            <a href="">Próxima</a>
        </div>
       
    </div>
</body>

</html>


$permissoes = explode(',', $loggedUser->permissao);
        if(in_array('usuario_add', $permissoes)){
            
            echo '<a href="<?=$base;?>/cadastro" class="botao_add">Adicionar novo</a>';
            };