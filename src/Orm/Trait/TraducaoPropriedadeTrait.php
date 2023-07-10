<?php

namespace ORM\Trait;

trait TraducaoPropriedadeTrait
{
    private array $ormTraduzirErro = [
        'Table'                         => 'Tabela',
        'doesn\'t exist'                => 'não existe.',
        'Duplicate entry'               => 'Valor duplicado',
        'for key'                       => 'para o campo',
        'Out of range value'            => 'Valor maior que o permitido',
        ' at row 1'                     => '.',
        'Incorrect decimal value:'      => 'Incorreto valor decimal',
        'Incorrect datetime value:'     => 'Incorreto valor datetime',
        'for column'                    => 'na coluna',
        'Column'                        => 'Coluna',
        'cannot be null'                => 'não pode ser nula',
        'Unknown column'                => 'Não existe a coluna',
        ' in '                          => ' na ',
        '\'field list\''                => 'tabela.',
        'Field '                        => 'Campo ',
        'doesn\'t have a default value' => 'não contém um valor padrão.',
    ];

    private function ormTraduzirErro(string $erro): string
    {
        return str_replace(array_keys($this->ormTraduzirErro), array_values($this->ormTraduzirErro), $erro);
    }
}
