<?php

use App\Classes\ParceiroLoja\Procedimento;
use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\Tipo;

$estados = [
    'AC', 'AL', 'AP', 'AM', 'BA', 'CE', 'DF', 'ES', 'GO', 'MA', 'MT', 'MS', 'MG',
    'PA', 'PB', 'PR', 'PE', 'PI', 'RJ', 'RN', 'RS', 'RO', 'RR', 'SC', 'SP', 'SE', 'TO'
];
$titulos = [
    'Soluções Tecnológicas',
    'Inovação em Serviços',
    'Consultoria Estratégica',
    'Desenvolvimento de Software',
    'Marketing Digital',
    'Design Criativo',
    'Assessoria Jurídica',
    'Logística Eficiente',
    'Energia Renovável',
    'Construção Civil',
    'Saúde e Bem-Estar',
    'Educação de Excelência',
    'Moda Sustentável',
    'Gastronomia de Classe',
    'Turismo de Aventura',
    'Beleza e Estilo',
    'Transporte Urbano',
    'Entretenimento Digital',
    'Recursos Humanos',
    'Agricultura Sustentável'
];
$textoProcedimento = [
    'Realizaremos uma consulta médica completa para avaliar sua condição de saúde.',
    'O paciente passará por uma cirurgia minimamente invasiva para tratar a condição.',
    'Iniciaremos o tratamento de fisioterapia para reabilitação após a cirurgia.',
    'Será realizado um exame de sangue para diagnóstico preciso.',
    'O procedimento inclui a administração de medicamentos para alívio dos sintomas.',
    'O paciente será submetido a um raio-x para avaliar a estrutura óssea.',
    'Realizaremos uma ressonância magnética para obter imagens detalhadas.',
    'O tratamento incluirá sessões de terapia ocupacional para melhorar a funcionalidade.',
    'Um procedimento endoscópico será realizado para avaliar o trato gastrointestinal.',
    'O paciente receberá anestesia local antes do procedimento.',
    'Será feita uma biópsia para determinar a presença de qualquer anomalia.',
    'Iniciaremos a quimioterapia como parte do tratamento contra o câncer.',
    'Realizaremos uma colonoscopia para rastreamento de doenças do cólon.',
    'O paciente será encaminhado para uma avaliação cardiológica abrangente.',
    'O tratamento incluirá sessões de psicoterapia para apoio emocional.',
    'Iniciaremos uma dieta personalizada como parte do plano de reabilitação.',
    'Será realizado um teste de função pulmonar para avaliar a capacidade respiratória.',
    'O procedimento cirúrgico será conduzido por uma equipe de especialistas experientes.',
    'O paciente passará por um tratamento de radioterapia para combater o câncer.',
    'Iniciaremos a monitorização contínua da pressão arterial durante o procedimento.'
];
$textoDesconto = [
    'Ganhe 20% de desconto em sua próxima compra.',
    'Aproveite 50% de desconto em todos os produtos da loja.',
    'Compre um item e leve o segundo com 30% de desconto.',
    'Receba R$ 10 de desconto em sua primeira compra online.',
    'Desconto de 15% em roupas e acessórios femininos.',
    'Economize 25% em eletrônicos durante a promoção de aniversário.',
    'Leve 3 produtos e pague apenas por 2, com o terceiro item grátis.',
    'Desconto exclusivo para clientes fiéis: 10% de desconto em sua próxima compra.',
    'Aproveite a oferta especial: todos os sapatos com 40% de desconto.',
    'Compre hoje e ganhe um cupom de R$ 50 para sua próxima compra.',
    'Economize 20% em produtos de beleza e cuidados pessoais.',
    'Desconto de R$ 15 em sua próxima refeição no restaurante.',
    'Ganhe 10% de desconto em ingressos para shows e eventos.',
    'Aproveite 30% de desconto em serviços de limpeza a seco.',
    'Compre um serviço de manicure e pedicure e ganhe 20% de desconto em um tratamento facial.',
    'Desconto de R$ 5 em serviços de entrega em domicílio.',
    'Economize 15% em todos os produtos de cuidados para animais de estimação.',
    'Ganhe um desconto de R$ 20 em sua próxima consulta médica.',
    'Aproveite 25% de desconto em serviços de pintura para sua casa.',
    'Compre um pacote de viagem e ganhe um upgrade de quarto gratuitamente.'
];
$textoVouchers = [
    'Desfrute de um jantar romântico para dois em nosso restaurante.',
    'Ganhe 20% de desconto na sua próxima compra.',
    'Receba uma massagem relaxante no nosso spa.',
    'Aproveite um fim de semana em nosso resort com 50% de desconto.',
    'Compre um produto e ganhe outro grátis.',
    'Tenha um dia de spa por nossa conta.',
    'Desconto de R$ 50 em qualquer serviço de manutenção de veículos.',
    'Ganhe um ingresso gratuito para o cinema.',
    'Receba uma aula de culinária particular com nosso chef renomado.',
    'Aproveite 3 noites pelo preço de 2 em nosso hotel.',
    'Desconto de 10% em todos os produtos de beleza.',
    'Tenha um dia de diversão em nosso parque temático.',
    'Ganhe uma sessão de treinamento pessoal gratuita na academia.',
    'Receba um vale-compras de R$ 100 para gastar como quiser.',
    'Desfrute de uma degustação de vinhos em nossa vinícola.',
    'Compre um bilhete para um concerto e ganhe outro de graça.',
    'Receba uma consulta médica gratuita em nossa clínica.',
    'Ganhe uma noite de hospedagem em nosso bed and breakfast.',
    'Desconto de 15% em todos os produtos de moda.',
    'Tenha um dia de golfe em nosso campo de campeonato.'
];
$sites = [
    'https://www.example.com',
    'https://www.site1.com',
    'https://www.site2.com',
    'https://www.site3.com',
    'https://www.site4.com',
    'https://www.site5.com',
    'https://www.site6.com',
    'https://www.site7.com',
    'https://www.site8.com',
    'https://www.site9.com',
    'https://www.site10.com',
    'https://www.site11.com',
    'https://www.site12.com',
    'https://www.site13.com',
    'https://www.site14.com',
    'https://www.site15.com',
    'https://www.site16.com',
    'https://www.site17.com',
    'https://www.site18.com',
    'https://www.site19.com'
];
$listaProcedimento = (new Procedimento())->listarNumero();
$listaTipo = (new Tipo())->listarNumero();
$listaStatus = (new Status())->listarNumero();
$seeds = [];
$seeds[] = [
    'cod'                 => 'f10e05c0-5b02-4bff-8e22-719a8797f0d6',
    'url'                 => 'parceiro-normal',
    'titulo'              => 'Parceiro Normal',
    'categoria_principal' => '1',
    'categoria_todas'     => '["1","2","3","4","5","6","7","8"]',
    'imagem'              => 'a82055f1446026f37fda574d337fcf06.png',
    'site'                => valorAleatorio($sites),
    'capa'                => 'a9e26316b4ecec7fad6883f8ad48ca23.jpg',
    'texto'               => '
            Quando confrontados com a traição, é importante lembrar que essa experiência não define sua autoestima.
            Embora a dor e a decepção sejam naturais, não permita que essas emoções dominem sua vida.
            Use esse desafio como uma oportunidade de crescimento pessoal e lembre-se de que merece relacionamentos baseados em confiança e respeito.
            Mantenha a fé na possibilidade de um futuro mais brilhante, pois sua resiliência e força interior o levarão a encontrar a felicidade novamente.
        ',
    'desconto'            => valorAleatorio($textoDesconto),
    'desconto_texto'      => valorAleatorio($textoDesconto),
    'procedimento'        => valorAleatorio($listaProcedimento),
    'procedimento_texto'  => valorAleatorio($textoProcedimento),
    'voucher_texto'       => valorAleatorio($textoVouchers),
    'destaque'            => ['1', '2', '66'],
    'empresa'             => ['1', '2', '66'],
    'data_publicacao'     => dataPassadaAleatorio(),
    'estado'              => $estados,
    'tipo'                => 1,
    'status'              => 4
];
for ($i = 0; $i < env('QTD_SEEDS', 50); $i++) {
    $titulo = valorAleatorio($titulos) . $i;
    $seeds[] = [
        'cod'                 => uuid(),
        'url'                 => strSlug($titulo),
        'titulo'              => $titulo,
        'categoria_principal' => '1',
        'categoria_todas'     => '["1","2","3","4","5","6","7","8"]',
        'imagem'              => 'a82055f1446026f37fda574d337fcf06.png',
        'site'                => valorAleatorio($sites),
        'capa'                => 'a9e26316b4ecec7fad6883f8ad48ca23.jpg',
        'texto'               => '
            Quando confrontados com a traição, é importante lembrar que essa experiência não define sua autoestima.
            Embora a dor e a decepção sejam naturais, não permita que essas emoções dominem sua vida.
            Use esse desafio como uma oportunidade de crescimento pessoal e lembre-se de que merece relacionamentos baseados em confiança e respeito.
            Mantenha a fé na possibilidade de um futuro mais brilhante, pois sua resiliência e força interior o levarão a encontrar a felicidade novamente.
        ',
        'desconto'            => valorAleatorio($textoDesconto),
        'desconto_texto'      => valorAleatorio($textoDesconto),
        'procedimento'        => valorAleatorio($listaProcedimento),
        'procedimento_texto'  => valorAleatorio($textoProcedimento),
        'voucher_texto'       => valorAleatorio($textoVouchers),
        'destaque'            => ['2'],
        'empresa'             => ['1', '2', '66'],
        'data_publicacao'     => dataPassadaAleatorio(),
        'estado'              => $estados,
        'tipo'                => valorAleatorio($listaTipo),
        'status'              => valorAleatorio($listaStatus)
    ];
}
return $seeds;
