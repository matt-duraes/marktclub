<?php

$Doc = new DocumentacaoConfig\Fw('MODELOS', 'Modulos são classes que foram feitas para facilita sua vida, você poderá usar os modulos nas interfaces como tipo das propriedades que o sistema irá saber como tratá-los, tanto na hora de salvar como montar os dados.');

$Doc
    ->paragrafo('Existem 3 tipos de modelos no sistema que são:')
    ->titulo('Modulos de ordens')
    ->paragrafo('Os modulos de ordem tem que ser um OrderInterface, para isso basta extender a class \Order\Order na sua classe de ordem.')
    ->titulo('Modulos de status')
    ->paragrafo('Os modulos de status tem que ser um StatusInterface, para isso basta extender a class \Status\Status na sua classe de status.')
    ->titulo('Modulos padrões')
    ->paragrafo('O sistema tem uma serie de modelos padrões, ao contrário dos tipo StatusInterface e OrderInterface, esses modelos não podem ser criados, apenas usados. Todos os Modulos padrões devem ser chamados pelo namespace \Modules, por exemplo: \Modules\Telefone, \Modules\Email, etc.')
    ->margin(30)
    ->bloco(function () use ($Doc) {
        $Doc
            ->titulo('Métodos gerais')
            ->paragrafo('Existem 2 métodos que existem em todos os modelos que são:')

            ->titulo('vazio')
            ->descricao('Verifica se o valor do modelo é vazio')
            ->codigo('vazio(): bool')
            ->retorno('bool', 'Retorna true caso o modelo for vazio')

            ->titulo('valido')
            ->descricao('Verifica se o valor do modelo é válido')
            ->codigo('valido(): bool')
            ->retorno('bool', 'Retorna true caso o modelo for valido')

            ->paragrafo('Sempre que deseja validar um modelo, basta usar os métodos acima como no exemplo:')
            ->codigo('
<?php

use \Modules\Email;
use \Modules\Telefone;
...
protected Email $email;
protected Telefone $telefone;
...
function validarDadosParaSalvar()
{
    $email = $this->email;
    $telefone = $this->telefone;

    if(!$email->vazio() && !$email->valido()) {
        mensagemErro("Campo inválido!", "O E-mail informado não está no formato válido.");
    } elseif (!$telefone->vazio() && !$telefone->valido()) {
        mensagemErro("Campo inválido!", "O Telefone informado não está no formato válido.");
    }
}
...
            ');
    });

echo $Doc;
