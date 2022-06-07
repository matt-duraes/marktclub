<?php

$Doc = new DocumentacaoConfig\Fw('BOAS PRÁTICAS', 'Agora que você já sabe criar páginas, vamos a algo muito importante, manual de boas práticas.');

$Doc
    ->paragrafo('Para uma boa gestão do sistema e facilidade para o trabalho em equipe, foi elaborado um manual de boas práticas sendo esse obrigatório a não ser que seja de impossível aplicação:')
    ->tabela(function () use ($Doc) {
        $Doc
            ->tr(['Não é permitido abreviar classes, variáveis e afins.'])
            ->tr(['Não utilizar palavras no plural a não ser na criação de URIs que devem seguir a lógica semantica da língua nativa da aplicação, por exemplo: /produtos/novos'])
            ->tr(['Usar nomes e termos apenas no masculino sempre que possível, por exemplo: buscarCantor() e não buscarCantora()'])
            ->tr(['Não usar nome próprio para classes, variáveis e afins.'])
            ->tr(['O nome não pode ter várias interpretações, por exemplo: buscarUsuarioPorNome() e não buscar()'])
            ->tr(['Crie nome sucinto e objetivo.'])
            ->tr(['Evitar o uso de preposição (de, da) e artigos definidos (a, o), por exemplo: buscarListaUsuario() e não buscaListaDeUsuarios().'])
            ->tr(['Em ações, sempre usar verbos, por exemplo: buscarProdutoAtivo e não buscaProdutoAtivo']);
    })

    ->titulo('MySql')
    ->paragrafo('Para o MySql, deve-se sempre pensar no futuro, ao contrário de um código, o banco de dados é muito complexo para fazer uma migração de nomeclaturas, por isso, todo cuidado é pouco ao fazer um novo banco/tabela.')
    ->tabela(function () use ($Doc) {
        $Doc
            ->tr(['Banco de dados sempre tem que ter um prefixo, por exemplo: usuario_cliente e não cliente'])
            ->tr(['Campo das tabela sempre que possível também devem ter prefixos.'])
            ->tr(['Tabelas e campos não podem ter mais de 30 caracteres'])
            ->tr(['Tabelas e campos devem usar _ (snakecase) para separar as palavras']);
    })

    ->titulo('JavaScript')
    ->tabela(function () use ($Doc) {
        $Doc
            ->tr(['Sempre que possível, usar const para declaração de variáveis'])
            ->tr(['Usar arrow function sempre que possível'])
            ->tr(['Criar funções dentro de constantes para impedir a sua reescrita'])
            ->tr(['Em funções com apenas um parâmetro, não colocá-lo entre parentes'])
            ->tr(['Usar await sempre que for usar uma promise'])
            ->tr(['Não usar else a não ser extritamente necessário'])
            ->tr(['Toda a escrita deve ser feita com CamelCase']);
    })
    ->paragrafo('Exemplo de arrow function:')
    ->codigo('
setTimeout(() => {
    // Código aqui
}, 2000);
    ')
    ->paragrafo('Exemplo de função como constante:')
    ->codigo('
const contarNumeroUsuarios = () => {
    // Código aqui
};

contarNumeroUsuario();
    ')
    ->paragrafo('Exemplo de função como apenas um parâmetro:')
    ->codigo('
const pegarDiaDaSemana = data => {
    // Código aqui
};

pegarDiaDaSemana("2022-01-01");
    ')
    ->paragrafo('Exemplo de um fetch e um return sem usar else:')
    ->codigo('
const buscarListaUsuario = async () => {
    const resposta = await fetch("https://localhos/usuario", {
        method: "POST"
    });

    let json;
    try {
        json = await resposta.json();
    } catch(erro) {
        json = {};
    }

    if(resposta.status == 201) {
        // Chama função de sucesso
        return;
    }
    // Chama função de erro
};
    ');

echo $Doc;
