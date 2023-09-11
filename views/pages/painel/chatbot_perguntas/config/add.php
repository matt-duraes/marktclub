<?php

use App\Classes\Geral\Status;
use Helpers\ApiHelper;

$Painel = new PainelConfig\Add('chatbot_perguntas');

$categorias = (new ApiHelper(token: true))
    ->json(['pagina' => 1])
    ->get('/chatbot-categoria')
    ->array()['dado']['lista'] ?? [];

$categoriasSelect[''] = "Escolha uma categoria";

foreach ($categorias as $categoria) {
    if ($categoria['status'] == Status::ATIVO) {
        $categoriasSelect[$categoria['categoria']] = $categoria['categoria'];;
    }
}

$Painel->coluna(callback: function () use ($Painel, $categoriasSelect) {
    $Painel->fieldset('Dados', function () use ($Painel, $categoriasSelect) {
        $Painel
            ->input(name: 'pergunta', label: 'Pergunta')
            ->editorBalao(name: 'resposta', label: 'Resposta')
            ->select(
                name: 'status',
                label: 'Status',
                lista: (new Status())->select('Escolha uma opção'),
                obrigatorio: true
            )
            ->select(
                name: 'categoria',
                label: 'Categoria',
                lista: $categoriasSelect,
                obrigatorio: true
            );
    });
});

return $Painel;
