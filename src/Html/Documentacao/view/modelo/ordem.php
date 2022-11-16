<?php

$Doc = new DocumentacaoConfig\Fw('MODELO DE ORDEM', 'Os modelos de ordenação servem para criar os comandando para o ORM.order, nele você consegue passar os valores da ordenação para o PDO ou resgatar esses valores se deseja.');

echo $Doc
    ->funcao(ROOT . '/src/Order/Order.php')
    ->paragrafo('Todo modelo de ordem deve ser um OrderInterface e a maneira mais fácil de fazer isso é extendendo sua class ao \Order\Order, para melhor exemplificar, irei montar uma ordem de exemplo:')
    ->codigo('
<?php

namespace App\Classes\Exemplo;

use Order\Order;


final class Ordem extends Order
{
    public function __construct(
        protected null|string $valor = null
    ) {
        $this
            ->tabela(TABELA_EXEMPLO)
            ->maisNovo()
            ->maisVelho()
            ->campo("nome-asc", "Nome mais novo", "nome", "ASC");
    }
}
    ')
    ->paragrafo('Como visto acima, todo OrderInterface deve ter um construtor onde você poderá passar ou não a ordem.');
