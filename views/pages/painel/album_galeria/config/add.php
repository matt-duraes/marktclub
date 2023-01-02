<?php

use App\Classes\AlbumGaleria\DimensaoTipo;

$Painel = new \PainelConfig\Add('album_galeria');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do álbum', callback: function () use ($Painel) {
        $Painel->input(name: 'titulo', label: 'Título do álbum', obrigatorio: 1);
        $Painel->editorBalao(
            name: 'texto',
            label: 'Descrição do álbum',
            obrigatorio: 1,
            bar: '',
            barBalao: 'bold italic underline | fontColor | link removeFormat'
        );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Publicação', callback: function () use ($Painel) {
        $Painel->dataHora(name: 'data_publicacao', label: 'Data de publicação', obrigatorio: 1);
        $Painel->dataHora(name: 'data_remocao', label: 'Data de remoção', ajuda: 'Preencher caso queira que o album saia do ar em uma data específica.');
        $Painel->switch(name: 'status', label: 'Ativar o álbum?');
    });
    $Painel->fieldset('Dimensão da imagem', callback: function () use ($Painel) {
        $Painel->select(name: 'dimensao_tipo', label: 'Tipo de álbum', obrigatorio: 1, lista: (new DimensaoTipo())->select('Escolha uma opção'));
        $Painel->numero(name: 'dimensao_largura', label: 'Largura', placeholder: 'Largura em pixel', maximo: 4, obrigatorio: 1);
        $Painel->numero(name: 'dimensao_altura', label: 'Altura', placeholder: 'Altura em pixel', maximo: 4, obrigatorio: 1);
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dimensão da imagem', callback: function () use ($Painel) {
        $Painel->fieldsetCheckbox(todos: 'Marcar todos', callback: function () use ($Painel) {
            $Painel->checkbox(name: 'extensao[]', label: 'PNG', value: 'png');
            $Painel->checkbox(name: 'extensao[]', label: 'JPG', value: 'jpg');
            $Painel->checkbox(name: 'extensao[]', label: 'GIF', value: 'gif');
            $Painel->checkbox(name: 'extensao[]', label: 'SVG', value: 'svg');
        });
    });
});

return $Painel;
