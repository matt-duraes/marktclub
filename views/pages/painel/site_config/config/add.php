<?php

use App\Classes\Geral\Status;
use App\Classes\SiteConfig\Helper;
use App\Classes\SiteConfig\Template;

$Painel = new PainelConfig\Add(app: 'site_config', acao: $acao);
$imagem = sessao('PAINEL.upload_grupo')['site_config'] ?? '';

$Painel->coluna(callback: function () use ($Painel, $imagem) {
    $Painel->fieldset('Imagens', function () use ($Painel, $imagem) {
        $Painel->imagem(name: 'logo_principal', diretorio: $imagem, label: 'Logo principal');
        $Painel->imagem(name: 'favicon', diretorio: $imagem, label: 'Favicon');
    });
    $Painel->fieldset('Dados do site', function () use ($Painel) {
        $Painel
            ->select(
                name: 'empresa',
                label: 'Empresa',
                lista: 'empresa',
                acao: 'add',
                permissao: Helper::PERMISSAO_EMPRESA,
                obrigatorio: true
            )
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título público',
                contador: 60,
                obrigatorio: true
            )
            ->input(
                name: 'titulo_painel',
                label: 'Título interno',
                placeholder: 'Digite um título interno',
                contador: 60,
                obrigatorio: true
            )
            ->input(
                name: 'descricao',
                label: 'Descrição',
                placeholder: 'Digite uma descrição geral',
                contador: 160,
                obrigatorio: true
            )
            ->select(
                name: 'template',
                label: 'Template',
                placeholder: 'Escolha um template',
                lista: (new Template())->select('Escolha uma opção'),
                obrigatorio: true
            )
            ->url(
                name: 'link_site',
                label: 'Link do site',
                placeholder: 'Digite o link do site',
                obrigatorio: true
            )
            ->cor(
                name: 'cor_principal',
                label: 'Cor do site'
            )
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Escolha um status',
                lista: (new Status())->select('Escolha uma opção'),
                obrigatorio: true
            );
    });
});
$Painel->coluna(callback: function () use ($Painel, $imagem) {
    $Painel->fieldset('Contatos', function () use ($Painel) {
        $Painel
            ->telefone(
                name: 'contato_telefone',
                label: 'Telefone',
                placeholder: 'Digite um telefone'
            )
            ->telefone(
                name: 'contato_celular',
                label: 'Celular',
                placeholder: 'Digite um celular'
            )
            ->telefone(
                name: 'contato_whatsapp',
                label: 'WhatsApp',
                placeholder: 'Digite um WhatsApp'
            )
            ->email(
                name: 'contato_email',
                label: 'E-mail',
                placeholder: 'Digite um e-mail'
            )
            ->input(
                name: 'contato_endereco',
                label: 'Endereço',
                placeholder: 'Digite um endereço'
            );
    });
    $Painel->fieldset('Rede social', function () use ($Painel, $imagem) {
        $Painel
            ->url(name: 'rede_youtube', label: 'You Tube', placeholder: 'Digite o link do You Tube')
            ->url(name: 'rede_facebook', label: 'Facebook', placeholder: 'Digite o link do Facebook')
            ->url(name: 'rede_instagram', label: 'Instagram', placeholder: 'Digite o link do Instagram')
            ->url(name: 'rede_x', label: 'X (Twitter)', placeholder: 'Digite o link do x')
            ->url(name: 'mapa_link', label: 'Link', placeholder: 'Digite o link do google maps')
            ->imagem(name: 'mapa_imagem', diretorio: $imagem, label: 'Imagem do mapa');
    });
});

return $Painel;
