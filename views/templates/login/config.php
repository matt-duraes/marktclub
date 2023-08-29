<?php

use App\Models\Site\ClubeModel;

$Clube = new ClubeModel();
$clube = sessao('CLUBE');

$menu = isset($menu) ? $menu : '';

define('CLUBE_LOGO', $clube->link_logo);
define('CLUBE_TITULO', $clube->titulo);
define('CLUBE_ID', $clube->id);
define('CLUBE_COR', $clube->cor);
define('CLUBE_FACEBOOK', '');
define('CLUBE_TWITTER', '');
define('CLUBE_INSTAGRAM', '');
define('LINK_LOGIN', $clube->link_login);

define('CLUBE_FINALIDADE', 1);

define('CONTATO_TELEFONE', $clube->contato_telefone);
define('CONTATO_WHATSAPP', $clube->contato_whatsapp);
define('CONTATO_EMAIL', $clube->contato_email);
define('CONTATO_HORARIO', $clube->horario_atendimento);
define('CONTATO_ENDERECO', $clube->contato_endereco);

define('MENU_FAQ', $clube->menu->faq);
define('MENU_PRIMEIRO_ACESSO', $clube->menu->primeiro_acesso);
define('MENU_BAIXAR_APP', $clube->menu->baixar_app);
define('MENU_COMO_FUNCIONA', $clube->menu->como_funciona);
define('MENU_DEPENDENTE', $clube->menu->dependente);
define('MENU_CASHBACK', $clube->menu->cashback);
define('MENU_EXTENSAO', true);
define('API', $clube->api);

define('MENU_HOVER', isset($menu) ? $menu : '');
define('LINK_APPLE', $clube->link_app_ios);
define('LINK_ANDROID', $clube->link_app_android);

$temMais = [];
/*
|--------------------------------------------------------------------------
| SAÚDE
|--------------------------------------------------------------------------
*/
$maisSaude = [];
if ($clube->menu->medicamento) {
    $temMais[] = [
        'id'         => 'id_mais_saude',
        'icone'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"  height="40"><path d="M16.5 3C19.5376 3 22 5.5 22 9C22 16 14.5 20 12 21.5C10.0226 20.3135 4.91699 17.563 2.86894 13.001L1 13V11L2.21045 11.0009C2.07425 10.3633 2 9.69651 2 9C2 5.5 4.5 3 7.5 3C9.35997 3 11 4 12 5C13 4 14.64 3 16.5 3ZM16.5 5C15.4241 5 14.2593 5.56911 13.4142 6.41421L12 7.82843L10.5858 6.41421C9.74068 5.56911 8.5759 5 7.5 5C5.55906 5 4 6.6565 4 9C4 9.68542 4.09035 10.3516 4.26658 11.0004L6.43381 11L8.5 7.55635L11.5 12.5563L12.4338 11H17V13H13.5662L11.5 16.4437L8.5 11.4437L7.56619 13L5.10789 13.0006C5.89727 14.3737 7.09304 15.6681 8.64514 16.9029C9.39001 17.4955 10.1845 18.0485 11.0661 18.6038C11.3646 18.7919 11.6611 18.9729 12 19.1752C12.3389 18.9729 12.6354 18.7919 12.9339 18.6038C13.8155 18.0485 14.61 17.4955 15.3549 16.9029C18.3337 14.533 20 11.9435 20 9C20 6.64076 18.463 5 16.5 5Z"></path></svg>',
        'titulo'     => 'Tem Mais Saúde',
        'texto'      => 'Emita sua carteirinha digital e aproveite a maior rede de drogarias e farmácias por todo o Brasil.',
        'desconto'   => 'ATÉ 45% DE DESCONTO*',
        'observacao' => '*Consulte as condições no site.',
        'parceiro'   => $maisSaude
    ];
}

/*
|--------------------------------------------------------------------------
| TURISMO
|--------------------------------------------------------------------------
*/

$maisTurismo = [];
if ($clube->menu->turismo) {
    $temMais[] = [
        'id'         => 'id_mais_turismo',
        'icone'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="40"><path fill="none" d="M0 0h24v24H0z"/><path d="M14 8.947L22 14v2l-8-2.526v5.36l3 1.666V22l-4.5-1L8 22v-1.5l3-1.667v-5.36L3 16v-2l8-5.053V3.5a1.5 1.5 0 0 1 3 0v5.447z"/></svg>',
        'titulo'     => 'Tem Mais Turismo',
        'texto'      => 'Cotações personalizadas com super descontos nas principais companhias aéreas do Brasil.',
        'desconto'   => 'ATÉ 20% DE DESCONTO*',
        'observacao' => '*Consulte as condições no site.',
        'parceiro'   => $maisTurismo
    ];
}

/*
|--------------------------------------------------------------------------
| EDUÇAÇÃO
|--------------------------------------------------------------------------
*/
$maisEducacao = [];
$temMais[] = [
    'id'         => 'id_mais_educacao',
    'icone'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="40"><path d="M4 11.3333L0 9L12 2L24 9V17.5H22V10.1667L20 11.3333V18.0113L19.7774 18.2864C17.9457 20.5499 15.1418 22 12 22C8.85817 22 6.05429 20.5499 4.22263 18.2864L4 18.0113V11.3333ZM6 12.5V17.2917C7.46721 18.954 9.61112 20 12 20C14.3889 20 16.5328 18.954 18 17.2917V12.5L12 16L6 12.5ZM3.96927 9L12 13.6846L20.0307 9L12 4.31541L3.96927 9Z"></path></svg>',
    'titulo'     => 'Tem Mais Educação',
    'texto'      => 'Encontre ou indique creches, escolas, faculdade ou pós-graduação para você e seus dependentes. Seus filhos merecem o melhor!',
    'desconto'   => 'Economia média mensal: <br> R$ 350,00',
    'observacao' => '*Consulte as condições no site.',
    'parceiro'   => $maisEducacao
];
/*
|--------------------------------------------------------------------------
| CONFORTO
|--------------------------------------------------------------------------
*/
$maisConforto = [];
$temMais[] = [
    'id'         => 'id_mais_conforto',
    'icone'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="40"><path d="M19 21.0001H5C4.44772 21.0001 4 20.5524 4 20.0001V11.0001L1 11.0001L11.3273 1.61162C11.7087 1.26488 12.2913 1.26488 12.6727 1.61162L23 11.0001L20 11.0001V20.0001C20 20.5524 19.5523 21.0001 19 21.0001ZM6 19.0001H18V9.15757L12 3.70302L6 9.15757V19.0001ZM9 10.0001H15V16.0001H9V10.0001ZM11 12.0001V14.0001H13V12.0001H11Z"></path></svg>',
    'titulo'     => 'Tem Mais Conforto',
    'texto'      => 'Seja na hora de renovar o guarda-roupas ou para trocar os móveis e eletrodomésticos da sua casa, aqui você encontra os melhores preços e opções.',
    'desconto'   => 'ATÉ 40% DE DESCONTO*',
    'observacao' => '*Consulte as condições no site.',
    'parceiro'   => $maisConforto
];

/*
|--------------------------------------------------------------------------
| TECNOLOGIA
|--------------------------------------------------------------------------
*/
$maisTecnologia = [];
$temMais[] = [
    'id'         => 'id_mais_tecnologia',
    'icone'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="40"><path d="M4 5V16H20V5H4ZM2 4.00748C2 3.45107 2.45531 3 2.9918 3H21.0082C21.556 3 22 3.44892 22 4.00748V18H2V4.00748ZM1 19H23V21H1V19Z"></path></svg>',
    'titulo'     => 'Tem Mais Tecnologia',
    'texto'      => 'Encontre tudo que precisa em um só lugar. Produtos para casa, cozinha, saúde, lazer, cuidados pessoais e entretenimento.',
    'desconto'   => 'ATÉ 30% DE DESCONTO*',
    'observacao' => '*Consulte as condições no site.',
    'parceiro'   => $maisTecnologia
];

/*
|--------------------------------------------------------------------------
| IDIOMA
|--------------------------------------------------------------------------
*/
$maisIdioma = [];
$temMais[] = [
    'id'         => 'id_mais_idioma',
    'icone'      => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" height="40"><path d="M12 22C6.47715 22 2 17.5228 2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22ZM9.71002 19.6674C8.74743 17.6259 8.15732 15.3742 8.02731 13H4.06189C4.458 16.1765 6.71639 18.7747 9.71002 19.6674ZM10.0307 13C10.1811 15.4388 10.8778 17.7297 12 19.752C13.1222 17.7297 13.8189 15.4388 13.9693 13H10.0307ZM19.9381 13H15.9727C15.8427 15.3742 15.2526 17.6259 14.29 19.6674C17.2836 18.7747 19.542 16.1765 19.9381 13ZM4.06189 11H8.02731C8.15732 8.62577 8.74743 6.37407 9.71002 4.33256C6.71639 5.22533 4.458 7.8235 4.06189 11ZM10.0307 11H13.9693C13.8189 8.56122 13.1222 6.27025 12 4.24799C10.8778 6.27025 10.1811 8.56122 10.0307 11ZM14.29 4.33256C15.2526 6.37407 15.8427 8.62577 15.9727 11H19.9381C19.542 7.8235 17.2836 5.22533 14.29 4.33256Z"></path></svg>',
    'titulo'     => 'Tem Mais Idiomas',
    'texto'      => 'Escolha entre as maiores escolas de idiomas do país.',
    'desconto'   => 'ATÉ 15% DE DESCONTO*',
    'observacao' => '*Consulte as condições no site.',
    'parceiro'   => $maisIdioma
];

include ROOT . '/resources/php/site/icone.php';
