<?php

use App\Classes\Geral\Status;
use Modules\Botao;
use PainelConfig\Add;

$Painel = new Add('album_dado', $acao);

$imagem = sessao('PAINEL.upload_grupo')->imagem ?? '';
$Botao = new Botao();
$Status = new Status();
$Painel->fieldset('Informações Básicas', function () use ($Painel, $imagem) {
    $Painel
        ->imagem('imagem', $imagem)
        ->input('titulo', 'Título do álbum', 'Título do álbum', obrigatorio: true)
        ->editorBalao(
            'texto',
            'Descrição do álbum',
            'Descrição do álbum',
            barBalao: 'bold italic underline | fontColor | link removeFormat'
        );
});

$Painel->fieldset('Detalhes de Publicação', function () use ($Painel, $Botao, $Status) {
    $Painel
        ->dataHora(
            'data_inicio',
            'Data da publicação',
            'Data da publicação',
            obrigatorio: true,
            ajuda: 'Preencher com a data que o álbum deverá estar disponível.'
        )
        ->dataHora(
            'data_final',
            'Data de remoção',
            'Data de remoção',
            ajuda: 'Preencher caso queira que o álbum saia do ar em uma data específica.'
        )
        ->select(
            'permissao_restrita',
            $Botao->select('Escolha uma opção'),
            'Área Restrita',
            'Área Restrita'
        )
        ->select(
            'permissao_site',
            $Botao->select('Escolha uma opção'),
            'Público',
            'Público'
        )
        ->select(
            'status',
            $Status->select(),
            'Status',
            'Status',
            obrigatorio: true
        );
});

return $Painel;
