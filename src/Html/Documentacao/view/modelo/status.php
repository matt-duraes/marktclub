<?php

$Doc = new DocumentacaoConfig\Fw('MODELO DE STATUS', 'Os modelos de status serverm para criar formas mais faceis de criar lista de valores padrões.');

echo $Doc
    ->funcao(ROOT . '/src/Status/Status.php')
    ->paragrafo('Todo modelo de status deve ser um StatusInterface e a maneira mais fácil de fazer isso é extendendo sua class ao \Status\Status, para melhor exemplificar, irei montar um status de exemplo:')
    ->codigo('
<?php

namespace App\Classes\Exemplo;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(lista: [
            "indice_01" => "Indice 01",
            "indice_02" => "Indice 02"
        ]);
    }
}
    ')
    ->paragrafo('Como visto acima, todo StatusInterface deve ter um construtor onde você poderá passar ou não o valor para o status, esse valor deve ser uma string quando você setar o valor ou inteiro quando vier do banco de dados.')
    ->paragrafo('Caso você não passe o indice no array do parâmetro lista, ele será criado automaticamente criando um slug do valor passado.')
    ->codigo('
<?php

namespace App\Classes\Exemplo;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(lista: [
            "Indice 01", // irá criar o indice: indice_01
            "Indice 02", // irá criar o indice: indice_02
        ]);
    }
}
    ')

    ->paragrafo('O próximo parâmetro do construtor é a cor, esse campo é específico para a criação da lista de dados do painel, nele você terá que passar um array com o indice sendo o indice do parâmetro lista e o valor a cor que deseja usar, você poderá usar as cores padrões: verde, vermelho, azul, laranja, preto, branco, cinza, rosa, roxo, amarelo ou marrom. Você também pode passar o valor hexadecimal da cor, por exemplo: #F0F0F0')
    ->codigo('
<?php

namespace App\Classes\Exemplo;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                "indice_01" => "Indice 01",
                "indice_02" => "Indice 02"
            ],
            cor: [
                "indice_01" => "verde",
                "indice_02" => "#F6F6F6"
            ]
        );
    }
}
    ')

    ->paragrafo('Outro ponto é o parâmetro número, caso você não passe, ele vai criar automaticamento o número começando o 1, no exemplo acima o indice_01 terá o valor inteiro 1 e o valor indice_02 terá o valor 2, no banco de dados sempre será salvo esse valor inteiro.')
    ->paragrafo('Agora, no caso dos números não serem sequênciais, você deverá passar os valor.')
    ->codigo('
<?php

namespace App\Classes\Exemplo;

use Status\Status;

final class Tipo extends Status
{
    public function __construct(
        protected null|string|int $valor = null
    ) {
        parent::__construct(
            lista: [
                "indice_01" => "Indice 01",
                "indice_02" => "Indice 02"
            ],
            numero: [2, 5]
        );
    }
}
    ')
    ->paragrafo('No exemplo acima o indice_01 terá o valor 2 e o indice_02 terá o valor 5.');
