<?php

$Doc = new DocumentacaoConfig\Fw('RESPONSE', 'Response são todas as resposta que vão para o cliente, a grosso modo, o controller é obrigado a retornar uma response.');

$Doc
    ->funcao(ROOT . '/src/Http/Response.php')
    ->paragrafo('Na grande maioria das vezes, você vai precisar apenas usar o construtor da response como nos exemplos abaixo:')
    ->codigo('
use Http\Response;

...
public function postSalvar()
{
    // Retorna um json e um status 201
    return new Response(json: ["id" => 1], status: 201);
}

public function postAtualizar()
{
    // Retonar um body vazio e o status 204
    return new Response(status: 204);
}

public function getImagem()
{
    // Retonar uma imagem para o navegador
    return new Response(arquivo: "images/arquivo.png");
}

public function getPdf()
{
    // Faz o download do arquivo PDF
    return new Response(download: "pdf/arquivo.pdf");
}

public function getIrParaGoogle()
{
    // Redireciona o usuário para o Google
    return new Response(url: "https://google.com");
}
...
    ')
    ->paragrafo('Você também pode e deve usar a função mensagemSucesso quando o retorno for um json:')
    ->codigo('
...
public function postSalvar()
{
    // Retorna uma estrutura padrão igual a:
    // [
    //     "status" => "sucesso",
    //     "dado" => ["id" => 1]
    // ]
    return mensagemSucesso(["id" => 1], status: 201);
}
..
    ');

echo $Doc;
