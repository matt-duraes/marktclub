<?php

use App\Classes\EnqueteMercado\Experiencia;
use App\Classes\EnqueteMercado\Fidelidade;
use App\Classes\EnqueteMercado\Frequencia;
use App\Classes\EnqueteMercado\Gasto;
use App\Classes\EnqueteMercado\Importancia;
use App\Classes\EnqueteMercado\Produtos;
use App\Classes\EnqueteMercado\Padrao as PesquisaPadrao;
use PainelConfig\Visualizar;

$Painel = new Visualizar('enquete_mercado');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Respostas', callback: function () use ($Painel) {
        $Painel
            ->linha('fidelidade', 'O que você prefere nesse programa de fidelidade?')
            ->linha('produtos', 'Quais produtos você mais procura?')
            ->linha(
                'gasto',
                'Se você fosse comprar uma smart tv para a sua sala, você estaria disposto a gastaraté quanto?'
            )
            ->linha(
                'importancia',
                'Se você precisasse escolher apenas UMA opção, o que mais importa para escolha de um produto/marca?'
            )
            ->linha('cashback', 'Você acredita que cashback realmente funciona?')
            ->linha('frequencia', 'Você utiliza o cashback aqui com muita frequência?')
            ->linha('resgate', 'Você já resgatou seu cashback nesse portal alguma vez?')
            ->linha('desconto', 'Sobre as parcerias e convênios, você já efetuou alguma compra com desconto?')
            ->linha('experiencia', 'Como foi a sua experiência?')
            ->linha('indicaria', 'Você indicaria para outra pessoa utilizar?');
    });
});

$Painel->replace(campo: 'fidelidade', lista: (new Fidelidade())->select());
$Painel->replace(campo: 'produtos', lista: (new Produtos())->select());
$Painel->replace(campo: 'gasto', lista: (new Gasto())->select());
$Painel->replace(campo: 'importancia', lista: (new Importancia())->select());
$Painel->replace(campo: 'cashback', lista: (new PesquisaPadrao())->select());
$Painel->replace(campo: 'frequencia', lista: (new Frequencia())->select());
$Painel->replace(campo: 'resgate', lista: (new PesquisaPadrao())->select());
$Painel->replace(campo: 'desconto', lista: (new PesquisaPadrao())->select());
$Painel->replace(campo: 'experiencia', lista: (new Experiencia())->select());
$Painel->replace(campo: 'indicaria', lista: (new PesquisaPadrao())->select());

return $Painel;
