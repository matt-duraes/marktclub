<?php

$Doc = new DocumentacaoConfig\Fw('BANCO DE DADOS', 'Para facilitar a vida de todos e sempre termos um banco atualizado em todas as máquinas, o sistema conta com um gerênciar do de bancos que veremos abaixo como funciona.');

$Doc
    ->paragrafo('O primeiro ponto que você deve saber é que ele fica em database, cada diretório no database é uma tabela do banco e seu nome deve ser o mesmo nome da tabala no banco, por exemplo, o diretório usuario_equipe é o responsável por gerênciar a tabela usuario_equipe no banco de dados. Dentro de cada diretório temos que ter um arquivo obrigatório chamado de base.php, aqui vai ficar a construção da tabela. Também pode ter um arquivo chamado de dado.php que irá popular a base.')
    ->paragrafo('Para começar, vamos listar os métodos para a criação de uma tabela:')
    ->funcao(ROOT . '/src/Database/DataBase.php')
    ->paragrafo('Como podemos ver, são muitos métodos mas todo auto explicativos, mesmo assim, irei deixar um exemplo de tabela aqui:')
    ->codigo('
<?php

return (new \DataBase\DataBase())
    ->id()
    ->uuid()
    ->varchar("id_facebook")->tamanho(170)->null()->unico()
    ->varchar("id_google")->tamanho(170)->null()->unico()
    ->nome("nome_completo")
    ->cpf("documento_cpf")->unico()
    ->email("email")->null()
    ->text("salt")->null()
    ->telefone("telefone")->null()
    ->int("genero")->tamanho(1)->null()
    ->date("data_nascimento")->null()
    ->char("hash_codigo")->tamanho(32)->null()
    ->int("hash_numero")->tamanho(8)->zero()->null()
    ->datetime("hash_validade")->null()
    ->varchar("hash_tipo")->tamanho(50)->null()
    ->imagem("imagem_arquivo")->null()
    ->dataCriacao()
    ->dataAtualizacao()
    ->datetime("data_acesso")->null()
    ->datetime("data_senha")->null()
    ->datetime("data_termo")->null()
    ->int("primeiro_acesso")->null()->tamanho(1)
    ->int("mudar_senha")->null()->tamanho(1)
    ->status()->null();
    ')
    ->paragrafo('Como falei, também podemos deixar valores fixo para serem inseridos no banco de dados, para isso, basta criar um arquivo com os valores em arrais assim:')
    ->codigo('
<?php

return [
    [
        "nome_completo" => nomeAleatorio(),
        "documento_cpf" => cpfAleatorio(),
        "email" => emailAleatorio(),
        "telefone" => celularAleatorio(),
        "salt" => password("123456"),
        "data_criacao" => date("Y-m-d H:i:s"),
        "data_atualizacao" => date("Y-m-d H:i:s"),
        "data_acesso" => "",
        "primeiro_acesso" => 2,
        "mudar_senha" => 2,
        "status" => 1,
    ],
    [
        "nome_completo" => nomeAleatorio(),
        "documento_cpf" => cpfAleatorio(),
        "telefone" => celularAleatorio(),
        "salt" => password("123456"),
        "data_criacao" => date("Y-m-d H:i:s"),
        "data_atualizacao" => date("Y-m-d H:i:s"),
        "data_acesso" => "",
        "primeiro_acesso" => 2,
        "mudar_senha" => 2,
        "status" => 1
    ]
];
    ')
    ->paragrafo('O script acima irá adicionar 2 usuários a tabela do banco de dados, para terminar, falta apenas acessar a URL onde gerenciamos os bancos que é: {{LINK}}/__base, nessa URL basta colocar o login e senha do banco, escolher as tabelas que queira subir e pronto!');

echo $Doc;
