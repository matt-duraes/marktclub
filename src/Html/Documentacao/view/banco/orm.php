<?php

$Doc = new DocumentacaoConfig\Fw('ORM', 'O ORM é a classe que vai cuidar de todo o PDO, com ele, você não precisa se preocupar com a segurança do banco desde que utilize as boas práticas do ORM.');

$Doc
    ->paragrafo('O ORM é uma classe abstrata, logo, você não pode chamar ela diretamente, sempre irá ser preciso usar uma classe e extender o ORM. Fora os vários métodos que podemos usar no ORM, ele precisa de uma propriedade protegida chamada _tabela que deve conter o nome do banco que iremos usar.')
    ->codigo('
<?php

namespace App\Models\Exemplo;

use ORM\ORM;

final class ExemploModel extends ORM
{
    protected string $ormTabela = TABELA_EXEMPLO;
}
    ')
    ->paragrafo('Como um bom CRUD, nosso ORM conta com várias vacilidades para você poder fazer suas querys no banco de dados e vamos listar elas abaixo:');

// INSERT
$Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('INSERT')
            ->paragrafo('Vamos começar pelo INSERT, primeiro de tudo, vamos analizar o que precisamos para um INSERT:')
            ->codigo('
INSERT INTO `NOME_DA_TABELA` (`campo_1`, `campo_2`) VALUES ("valor 01", "valor 02");
        ')
            ->paragrafo('Como vimos no script acima, o insert conta com basicamente 3 partes, o nome do banco, os campos a serem usados no insert e o seus valores, para isso, iremos usar apenas 2 métodos, o método dado() onde iremos passar um array com os campos e valores e o método insert() onde mandamos o ORM salvar os dados. Lembrando que a tabela já passamos na propriedade _tabela da classe.')
            ->codigo('
<?php
...
protected string $ormTabela = TABELA_USUARIO_CLIENTE;
...
public function salvarNovoUsuario(string $nome, string $email)
{
    $salvar = $this->dado([
        "nome" => $nome,
        "email" => $email
    ])->insert();
}
...
        ')
            ->paragrafo('Agora vamos analisar os métodos usados:')
            ->margin(30)
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('dado')
                    ->paragrafo('Método para tratar os dados da ORM, ele pode ser usado tanto no insert como no update')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('array', '$dado', 'Array com o formato: ["campo_no_banco" => "valor que deseja salvar"]');
                    })
                    ->codigo('dado(array $dado): self')
                    ->retorno('self', '')
                    ->throw('\Erro\Excecao', 'Retorna uma exceção caso o array esteja vazio');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('insert')
                    ->paragrafo('Método que manda o ORM salvar os dados no banco')
                    ->codigo('insert(): array')
                    ->retorno('array', 'Retorna um array com os campos envidos para salvar e o id podendo ser o ID ou UUID do registro')
                    ->throw('\Erro\Excecao', 'Retorna uma exceção caso o array de dados esteja vazio ou de erro na hora de salvar os dados na base');
            })
            ->paragrafo('Como vimos, ao usar o insert ele te retorna o indice id contento o ID real ou UUID, com isso, podemos validar se realmente os dados foram salvos (OK, se der erro, vai gerar uma exceção mas nunca é demais validar retornos).')
            ->codigo('
...
$salvar = $this->dado([
    "nome" => $nome,
    "email" => $email
])->insert();

if(existeErro($salvar, "id")) {
    mensagemErro("Erro!", "Ocorreu um erro ao salvar o usuário.", 500);
}
...
            ');
    });

// UPDATE
$Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('UPDATE')
            ->paragrafo('Agora que já sabemos salvar um registro, precisamos alterar o valor dele, como antes, iremos analisar a query de um update:')
            ->codigo('UPDATE `NOME_DA_TABELA` SET `campo_1` = "valor 01", `campo_2` = "valor 02" WHERE `id` = 1')
            ->paragrafo('Fora a visual mudança para o INSERT, no UPDATE colocamos um elmento novo que é o WHERE, ele diz para qual registro ou grupo de registro iremos fazer essa atualização, vamos ver como fazer isso no ORM:')
            ->codigo('
<?php
...
protected string $ormTabela = TABELA_USUARIO_CLIENTE;
...
public function atualizarUsuarioExistente(string $nome, string $email, int $id)
{
    $atualizar = $this->dado([
        "nome" => $nome,
        "email" => $email
    ])->where(["id", $id])->update();
}
...
            ')
            ->paragrafo('Aqui tivemos 2 métodos novos, o where e o update, vou começar pelo update porque o WHERE vai precisar de muita explicação para mostrar tudo o que é capaz de fazer com ele.')
            ->margin(30)
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('update')
                    ->paragrafo('Método que manda o ORM atualizar os dados no banco')
                    ->codigo('update(): array')
                    ->retorno('array', 'Retorna um array com os campos envidos para atualizar e o id podendo ser o ID ou UUID do registro')
                    ->throw('\Erro\Excecao', 'Retorna uma exceção caso o array de dados esteja vazio ou de erro na hora de atualizar os dados na base');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('where')
                    ->paragrafo('Cria uma lista de condições para a busca do banco')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('array', '$where', 'Array contendo os filtros')
                            ->parametro('bool', '$obrigatorio', 'Se for true, vai gerar uma Excecao se o array do where seja vazio')
                            ->parametro('string', '$separador', 'Separador do where podendo ser AND ou OR, ele vai servir para ficar entre cada condição da busca, por exemplo, `nome` = "x" AND `email` = "y"');
                    })
                    ->codigo('where(array $where, bool $obrigatorio = true, string $separador = "AND"): self')
                    ->retorno('self', 'Retorna a própria classe')
                    ->throw('\Erro\Excecao', 'Retorna uma exceção caso o array do where seja obrigatório')
                    ->paragrafo('Vamos lá, o valor do Where é um array, mas e ai? Como fazemos para fazer a buscas? Basicamento cada array no where é uma condição, grupos de arraies são subgrupos das queries que são agrupados por () (colchetes) e cada array deve ter 2 ou 3 valores para fazer a busca ou começar com AND ou OR para falar qual vai ser o separador daquele bloco. Difícil de entender né? Vamos aos exemplos que fica mais fácil:')
                    ->codigo('
...
$this->where(["nome", $nome]);
// Isso será transformado em:
// `tabela`.`nome` = "valor"
...
$this->where([
    ["nome", $nome],
    ["status", "!=", 1]
]);
// Isso será transformado em:
// (`tabela`.`nome` = "valor" AND `tabela`.`status` != 1)
...
                    ');
            })
            ->paragrafo('Incialmente, vemos que podemos escrever o where sem um [] principal quando tem apenas 1 continução, a partir da segunda condição, você é obrigado a usar um [] principal, outro ponto é, o número de indices no array, quando existe 2 indice e o segundo é um valor, entende que ele irá usar a condição = para buscar, como no exemplo do nome, já no segundo exemplo do status, foi passado explicitamente o "!=" para falar que o status deveria ser diferente do valor.')
            ->paragrafo('As condições permitidas são: ">", ">=", "=", "<>", "<", "<=", "!=", "like", "notlike", "null", "!null", "in", "notin", "between", "notbetween", alguns condições como o "in", "notint", "between" e "notbetween" devem ter um array com os valores esperado, já o "null" e "!null" não tem o terceiro indice que seria o valor.')
            ->codigo('
...
$this->where([
    ["nome", $nome],
    ["status", ">", 1],
    ["email", "link", "%{$email}%"],
    ["tipo", "in", [1, 2, 3]],
    ["data_ativacao", "!null"]
]);
// Isso será transformado em:
(`tabela`.`nome` = "$nome" AND `tabela`.`status` > 1 AND `tabela`.`email` LIKE "%$email%" AND `tabela`.`tipo` IN(1, 2, 3) AND `tabela`.`data_ativacao` IS NOT NULL)
...
            ')
            ->paragrafo('Agora vamos para a parte mais difícil de entender, vamos criar grupos dentro do where mudando o tipo  consulta entre eles, misturando AND e OR')
            ->codigo('
...
$this->where([
    ["nome", $nome],
    [
        "OR",
        ["status", ">", 1],
        ["tipo", 1]
    ]
]);
// Isso será transformado em:
(`tabela`.`nome` = "$nome" AND (`tabela`.`status` > 1 OR `tabela`.`tipo` = 1))
...
            ')
            ->paragrafo('Você pode ter quantos grupos quiser dentro do array principal e caso queira mudar de AND para OR, basta passar esse valor no primeiro índice do array.');
    });

// READ
$Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('READ')
            ->paragrafo('Agora que já sabemos salvar e atualizar dados, vamos a parte do CRUD com mais métodos possíveis de ser usados, mas, primeiro vamos analisar sua query:')
            ->codigo('
SELECT * FROM `NOME_DA_TABELA` WHERE `campo` = "valor" ORDER BY `id` DESC LIMIT 0, 1;
        ')
            ->paragrafo('Em primeiro lugar, isso ai é o básico de uma consulta, não daria para colocar tudo ai de cara, temos os JOINS, HAVING, GROUP entre vários outras coisas que podemos fazer, por enquando, vamos começar listando todos os métodos que podemos usar no READ:')
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('select')
                    ->paragrafo('Select para a buscar, esse campo pode ser omitido que o sistema irá criar o SELECT padrão')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$select', 'Select que deseja usar');
                    })
                    ->codigo('select(string $select): self')
                    ->retorno('self', '')
                    ->paragrafo('O parâmetro select tem algumas observações, primeiro, ele deve começar com a string "SELECT" e segundo, ele não pode ter a string "FROM".');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('selectText')
                    ->paragrafo('Select para a buscar em texto simples, esse campo pode ser omitido que o sistema irá criar o SELECT padrão')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$select', 'Select que deseja usar');
                    })
                    ->codigo('selectTexto(string $select): self')
                    ->retorno('self', '')
                    ->paragrafo('Nesse select, você deve começar com "SELECT" igual sua versão normal, o resto do select deve ser toda passada por você incluindo os campos da busca, por isso, use ele apenas quando for extritamente necessário.');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('campo')
                    ->paragrafo('Campos permitos na busca')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('array', '$campo', 'Lista com os campos que devem ser buscados podendo ser uma lista simples ["campo_1", "campo_2"] ou um array composto onde o primeiro indice é o campo e o segundo é a alias [["campo_1", "nome_campo_1"], ["campo_2", "campo_nome_2"]]')
                            ->parametro('string', '$as', 'Alias padrão para o as, por exemplo, $as = usuario: campo1 vira usuario_campo1, campo2 vira usuario_campo2, etc');
                    })
                    ->codigo('campo(array $campo, ?string $as = null): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('campoTexto')
                    ->paragrafo('Campos permitos na busca em formato de texto puro, cuidado ao usá-lo.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$campo', 'Campos em texto puro');
                    })
                    ->codigo('campoTexto(string $campo): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('whereTexto')
                    ->paragrafo('Where em texto puro, muito cuidado ao usar esse método, recomendamos que você use o WHERE visto no UPDATE.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$where', 'WHERE em texto puro no formato de leitura para PDO')
                            ->parametro('array', '$valor', 'Valores para serem substituidos na query')
                            ->parametro('bool', '$obrigatorio', 'Se o where vai ser obrigatório ou não, true por padrão');
                    })
                    ->codigo('whereTexto(string $where, array $valor, bool $obrigatorio): self')
                    ->retorno('self', '')
                    ->throw('Excecao', 'Dispara uma exeção caso o $where seja vazio e o $obrigatorio seja true');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('having')
                    ->paragrafo('Having para a busca.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('array', '$having', 'Array com as condições igual vimos no where')
                            ->parametro('bool', '$obrigatorio', 'Se o having será obrigatório, true por padrão')
                            ->parametro('string', '$separador', 'O tipo de separador padrão podendo ser AND ou OR, AND por padrão');
                    })
                    ->codigo('having(array $having, bool $obrigatorio = true, string $separador = "AND"): self')
                    ->retorno('self', '')
                    ->throw('Excecao', 'Dispara uma exeção caso o $having seja vazio e o $obrigatorio seja true');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('havingTexto')
                    ->paragrafo('Having em texto puro para a busca.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$having', 'Having em texto puro segingo padrão para o PDO')
                            ->parametro('array', '$valor', 'Valores para serem substituidos na query')
                            ->parametro('bool', '$obrigatorio', 'Se o having será obrigatório, true por padrão');
                    })
                    ->codigo('having(string $having, array $valor, bool $obrigatorio = true): self')
                    ->retorno('self', '')
                    ->throw('Excecao', 'Dispara uma exeção caso o $having seja vazio e o $obrigatorio seja true');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('group')
                    ->paragrafo('Group para a busca.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$campo', 'Campo que será usado no group');
                    })
                    ->codigo('group(string $campo): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('groupTexto')
                    ->paragrafo('Group em texto puro para a busca, cuidado ao usá-lo.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$group', 'Texto para o group');
                    })
                    ->codigo('group(string $group): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('order')
                    ->paragrafo('Ordem para a busca.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string|array|OrderInterface', '$campo', 'Campo para a busca podendo ser uma string, um array nos formatos ["campo_1", "campo_2"] ou [["campo_1", "ASC"], ["campo_2", "DESC"]] ou um OrderInterface')
                            ->parametro('string', '$direcao', 'Direção da ordenação podendo ser ASC ou DESC, ASC por padrão');
                    })
                    ->codigo('order(string|array|OrderInterface $campo, string $direcao = "ASC"): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('orderTexto')
                    ->paragrafo('Ordem em texto puro, cuidado ao usá-lo')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$order', 'Ordem tem texto puro');
                    })
                    ->codigo('orderTexto(string $order): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('join')
                    ->paragrafo('Faz um JOIN com outra tabela.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$campo', 'Campo da tabela atual')
                            ->parametro('string', '$relacao', 'Campo da tabela original ou se tiver passado o parâmetro $tabela')
                            ->parametro('string', '$condicao', 'Condição para o JOIN')
                            ->parametro('string', '$tabela', 'Tabela caso não queira usar a tabela original')
                            ->parametro('string', '$tipo', 'Qual tipo de JOIN será usado podendo ser: INNER, LEFT, RIGHT, CROSS ou FULL');
                    })
                    ->codigo('join(string $campo, string $relacao = "", string $condicao = "=", string $tabela = "", string $tipo = "INNER"): self')
                    ->retorno('self', '')
                    ->throw('\Erro\Excexao', 'Retorna um exceção caso passe um valor inválido para o $tipo, $condicao ou esta tentando fazer um join para a mesma tabela')
                    ->paragrafo('Você também pode chamar os métodos innerJoin, leftJoin, rightJoin, crossJoin e fullJoin diretamente, neles só não tem a última propriedade que é o tipo já que estão sendo declarados explicitamente.')
                    ->codigo('
innerJoin(string $campo, string $relacao = "", string $condicao = "=", string $tabela = ""): self
leftJoin(string $campo, string $relacao = "", string $condicao = "=", string $tabela = ""): self
rightJoin(string $campo, string $relacao = "", string $condicao = "=", string $tabela = ""): self
crossJoin(string $campo, string $relacao = "", string $condicao = "=", string $tabela = ""): self
fullJoin(string $campo, string $relacao = "", string $condicao = "=", string $tabela = ""): self
                    ');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('joinTexto')
                    ->paragrafo('Join em texto puro, cuidado ao usá-lo.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$join', 'Query do join');
                    })
                    ->codigo('joinTexto(string $join): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('limit')
                    ->paragrafo('Limit para a busca.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('int', '$inicio', 'Valor inicial para o limit')
                            ->parametro('int', '$quantidade', 'Quantidade de registro a serem buscados');
                    })
                    ->codigo('limit(int $inicio, int $quantidade): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('pagina')
                    ->paragrafo('Página que deseja buscar começando de 1.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('int', '$pagina', 'Página inicial')
                            ->parametro('int', '$quantidade', 'Quantidade de registro por página, 20 por padrão');
                    })
                    ->codigo('pagina(int $pagina, int $quantidade = 20): self')
                    ->retorno('self', '');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('tabela')
                    ->paragrafo('Muda a tabela atual para a tabela informada.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$tabela', 'Nome da nova tabela a ser usada');
                    })
                    ->codigo('tabela(string $tabela): self')
                    ->retorno('self', '')
                    ->paragrafo('Uma vez mudado a tabela, qualquer método que for usado após ela terá seus dados vinculados a nova tabela.');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('primeiro')
                    ->paragrafo('Pega o primeiro resultado da busca.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$campo', 'Pega apenas um campo da busca e retorna ele')
                            ->parametro('mixed', '$padrao', 'Valor padrão casa não exista o campo buscado')
                            ->parametro('string', '$retorno', 'Retorno podendo ser object ou array, object por padrão');
                    })
                    ->codigo('primeiro(string $campo = "", $padrao = null, string $retorno = "object"): mixed')
                    ->retorno('mixed', 'Retorna um array/object com o registro buscado ou o campo buscado ou o valor padrão informado');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('read')
                    ->paragrafo('Faz a busca no banco.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('null|int', '$indice', 'Pega o indice da busca, por exemplo, 1 pegaria o segundo registro')
                            ->parametro('string', '$campo', 'Pega apenas um campo da busca e retorna ele')
                            ->parametro('mixed', '$padrao', 'Valor padrão casa não exista o campo buscado')
                            ->parametro('string', '$retorno', 'Retorno podendo ser object ou array, object por padrão');
                    })
                    ->codigo('read(?int $indice = null, string $campo = "", $padrao = null, string $retorno = "object"): mixed')
                    ->retorno('mixed', 'Retorna um array com a lista de registro buscado ou o campo buscado ou o valor padrão informado');
            })
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('readTexto')
                    ->paragrafo('Faz a busca no banco via texto puro.')
                    ->blocoParametro(function () use ($Doc) {
                        $Doc
                            ->parametro('string', '$query', 'Query em texto puro')
                            ->parametro('array', '$valor', 'Valor para ser substituido na query')
                            ->parametro('string', '$retorno', 'Retorno podendo ser object ou array, object por padrão');
                    })
                    ->codigo('readTexto(string $query, array $valor = [], string $retorno = "object"): array')
                    ->retorno('array', 'Array com a lista de registros')
                    ->throw('\Erro\Execao', 'Retorna um exceção caso');
            })
            ->paragrafo('Agora que temos todos o métodos listados, vamos a alguns exemplos:')
            ->codigo('
protected string $ormTabela = TABELA_EXEMPLO;
...
$this
    ->where(["status", 1])
    ->campo(["campo_1", "campo_2"])
    ->read();
// SELECT `exemplo`.`campo_1`, `exemplo`.`campo_2` FROM `exemplo` WHERE `status` = 1;
...
            ')
            ->paragrafo('Agora, vamos criar uma busca com ordem e limite:')
            ->codigo('
protected string $ormTabela = TABELA_EXEMPLO;
...
$this
    ->where(["status", 1])
    ->campo(["campo_1", "campo_2"])
    ->order("id", "DESC")
    ->limit(0, 1)
    ->read();
// SELECT `exemplo`.`campo_1`, `exemplo`.`campo_2` FROM `exemplo` WHERE `status` = 1 ORDER BY `exemplo`.`id` DESC LIMIT 0, 1;
...
            ')
            ->paragrafo('A próxima, será feita com paginação, para isso, deixa-se de usar limit() e usa pagina()')
            ->codigo('
protected string $ormTabela = TABELA_EXEMPLO;
...
$this
    ->where(["status", 1])
    ->campo(["campo_1", "campo_2"])
    ->order("id", "DESC")
    ->pagina(3, 20)
    ->read();
// SELECT SQL_CALC_FOUND_ROWS `exemplo`.`campo_1`, `exemplo`.`campo_2` FROM `exemplo` WHERE `status` = 1 ORDER BY `exemplo`.`id` DESC LIMIT 40, 20;
...
            ')
            ->paragrafo('Como vimos acima, o limit começou no número 40 que seria a terceira página da paginação, também tem o contador de registros, no final, o retorno dessa query vai ser isso:')
            ->codigo('
(object)[
    "lista" => [] // Lista de registros encontrados,
    "registro" => (object) [
        "inicio" => 40, // Número do primeiro registro. 40 porque essa é a terceira página
        "final" => 60, // Número do último registro
        "atual" => 20, // Quantidade de registros encontrados nessa busca
        "total" => 1000 // Quantidade de registros totais no banco que casam com a busca realizada
    ],
    "pagina" => (object) [
        "total" => 50, // Quantidade de páginas da busca
        "atual" => 3, // Página atual
        "paginacao" => [1, 2 , 3, 4, 5, 6, 7] // Array com 3 números para trás e 3 números para frente da página atual
    ]
];
            ')
            ->paragrafo('Agora vamos para as partes mais difíceis de entender, vamos fazer alguns exemplos de JOIN')
            ->codigo('
protected string $ormTabela = TABELA_UM;
...
$this
    ->campo(["campo_1", "campo_2"])
    ->where(["status", 1])
    ->order("id", "DESC")
    ->tabela(TABELA_DOIS)
    ->join("id", "id_tabele_dois")
    ->campo("campo_3")
    ->where(["tipo", 1])
    ->order("status", "ASC")
    ->limit(0, 1)
    ->read();
// SELECT `tabela_um`.`campo_1`, `tabela_um`.`campo_2`, `tabela_dois`.`campo_3` FROM `tabela_um` INNER JOIN `tabela_dois` ON `tabela_dois`.`id` = `tabela_um`.`id_tabela_dois` WHERE (`tabela_um`.`status` = 1) AND (`tabela_dois`.`tipo` = 1) ORDER BY `tabela_um`.`id` DESC, `tabela_dois`.`status` LIMIT 0, 1;
...
            ')
            ->paragrafo('Como visto no exemplo acima, a coisa parece mais caótica no join mas basta você entender que, tudo que vem depois da nova tabela, faz parte das condições dessa tabela, então ao repetir os métodos campo, where e order, o sistema simplesmente está colocando a tabela no início dos campos, já o join, simplesmente liga a tabela_um com a tabela_dois. Infelizmente para entender melhor o JOIN, você realmente vai ter que dar uma estudada no MySql, mas acredito que com o exemplo acima, ficou bem fácil de usar.');
    });

// INSERT
$Doc
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('DELETE')
            ->paragrafo('A última operação do CRUD que iremos analisar é o Delete, para isso, vamos analisar sua query padrão.')
            ->codigo('
DELETE FROM `NOME_DA_TABELA` WHERE `id` = 1;
        ')
            ->paragrafo('O delete é bem parecido com o update, mas só precisa de uma where para falar o que deve ser deletado, usando o ORM ficaria assim:')
            ->codigo('
<?php
...
protected string $ormTabela = TABELA_USUARIO_CLIENTE;
...
public function deletarUsuario(int $id)
{
    $salvar = $this->where(["id", $id])->delete();
}
...
        ')
            ->paragrafo('Agora vamos analisar o método delete, lembrando que o where é o mesmo de sempre:')
            ->margin(30)
            ->bloco(function () use ($Doc) {
                $Doc
                    ->titulo('delete')
                    ->paragrafo('Método que manda o ORM deletar o registro')
                    ->codigo('delete(): bool')
                    ->retorno('bool', 'Em caso de sucesso, retorna true')
                    ->throw('\Erro\Excecao', 'Retorna uma exceção caso ocorra um erro ao deletar');
            })
            ->paragrafo('Aqui não tem muito o que analisar, se deletar retorna true, se não, retorna uma Exceção.');
    });

echo $Doc;
