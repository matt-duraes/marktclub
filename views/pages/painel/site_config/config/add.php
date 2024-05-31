<?php

use App\Classes\Geral\Status;
use App\Classes\SiteConfig\Helper;
use App\Classes\SiteConfig\DiretoriaTipo;
use App\Classes\SiteConfig\TemplateFooter;
use App\Classes\SiteConfig\TemplateHeader;

$Painel = new PainelConfig\Add(app: 'site_config', acao: $acao);
$imagem = sessao('PAINEL.upload_grupo')['site_config'] ?? '';

$Painel->coluna(callback: function () use ($Painel) {
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
                placeholder: 'Digite um título para o site',
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
            ->url(
                name: 'login_link',
                label: 'Link do Login',
                placeholder: 'Digite o link do login (deixar vazio para padrão)'
            )
            ->input(
                name: 'login_texto',
                label: 'Texto de Login',
                placeholder: 'Digite um texto para o login'
            )
            ->url(
                name: 'clube_link',
                label: 'Link do Clube',
                placeholder: 'Digite o link do clube (deixar vazio para padrão)'
            )
            ->tag(
                name: 'link_site',
                label: 'Link do site',
                placeholder: 'Digite o link do site',
                tipo: $Painel::TAG_TIPO_URL
            )
            ->select(
                name: 'status',
                label: 'Status',
                placeholder: 'Escolha um status',
                lista: (new Status())->select('Escolha uma opção'),
                obrigatorio: true
            );
    });
    $Painel->fieldset('Template', function () use ($Painel) {
        $Painel
            ->input(
                name: 'mensagem_topo',
                label: 'Mensagem Topo',
                placeholder: 'Mensagem do topo'
            )
            ->select(
                name: 'template_header',
                label: 'Template Cabeçalho',
                placeholder: 'Template do cabeçalho',
                lista: (new TemplateHeader())->select('Escolha uma opção'),
                obrigatorio: true
            )
            ->numero(
                name: 'altura_header',
                label: 'Altura do Cabeçalho',
                placeholder: 'Altura do cabeçalho em pixel'
            )
            ->select(
                name: 'template_footer',
                label: 'Template Footer',
                placeholder: 'Template do footer',
                lista: (new TemplateFooter())->select('Escolha uma opção'),
                obrigatorio: true
            )
            ->select(
                name: 'diretoria_tipo',
                label: 'Template Diretoria',
                placeholder: 'Template da diretoria',
                lista: (new DiretoriaTipo())->select('Escolha uma opção'),
                obrigatorio: true
            )
            ->cor(
                name: 'cor_principal',
                label: 'Cor do site'
            )
            ->cor(
                name: 'cor_header',
                label: 'Cor do cabeçalho'
            )
            ->cor(
                name: 'cor_footer',
                label: 'Cor do footer'
            )
            ->cor(
                name: 'cor_texto',
                label: 'Cor do texto'
            );
    });
    $Painel->fieldset('Home', function () use ($Painel) {
        $Painel
            ->switch(
                name: 'home_noticia_principal',
                label: 'Banner de noticia?'
            )
            ->numero(
                name: 'home_noticia_lista',
                label: 'Quantidade de Notícias na Página Inicial',
                placeholder: 'Digite um número'
            )
            ->switch(
                name: 'home_galeria',
                label: 'Galeria na página inicial?'
            )
            ->switch(
                name: 'home_video',
                label: 'Vídeo na página inicial?'
            )
            ->switch(
                name: 'home_parceiro',
                label: 'Parceiros na página inicial?'
            )
            ->switch(
                name: 'rede_header',
                label: 'Rede Sociais no cabeçalho?'
            )
            ->switch(
                name: 'rede_footer',
                label: 'Rede Sociais no footer?'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel, $imagem) {
    $Painel->fieldset('Imagens', function () use ($Painel, $imagem) {
        $Painel->imagem(name: 'logo_principal', diretorio: $imagem, label: 'Logo principal');
        $Painel->imagem(name: 'favicon', diretorio: $imagem, label: 'Favicon');
        $Painel->imagem(name: 'imagem_header', diretorio: $imagem, label: 'Imagem Cabeçalho');
    });
    $Painel->fieldset('Imagens', function () use ($Painel, $imagem) {
        $Painel->imagem(name: 'imagem_social', diretorio: $imagem, label: 'Imagem Social');
        $Painel->imagem(name: 'noticia_imagem', diretorio: $imagem, label: 'Imagem Notícia');
        $Painel->imagem(name: 'mapa_imagem', diretorio: $imagem, label: 'Imagem do mapa');
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
            ->switch(
                name: 'contato_chat',
                label: 'Vai usar o WhatsApp como chat?'
            )
            ->email(
                name: 'contato_email',
                label: 'E-mail',
                placeholder: 'Digite um e-mail'
            )
            ->input(
                name: 'contato_endereco',
                label: 'Endereço',
                placeholder: 'Endereço do footer'
            )
            ->url(name: 'mapa_link', label: 'Link do Google Maps', placeholder: 'Link do Google Maps');
    });
    $Painel->fieldset('Rede social', function () use ($Painel, $imagem) {
        $Painel
            ->url(name: 'rede_youtube', label: 'You Tube', placeholder: 'Digite o link do YouTube')
            ->url(name: 'rede_facebook', label: 'Facebook', placeholder: 'Digite o link do Facebook')
            ->url(name: 'rede_instagram', label: 'Instagram', placeholder: 'Digite o link do Instagram')
            ->url(name: 'rede_twitter_x', label: 'X (Twitter)', placeholder: 'Digite o link do x')
            ->url(name: 'rede_linkedin', label: 'LinkedIn', placeholder: 'Digite o link do LinkedIn')
            ->url(name: 'rede_spotify', label: 'Spotify', placeholder: 'Digite o link do spotify');
    });
});

return $Painel;
