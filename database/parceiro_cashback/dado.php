<?php

$dado = [];

$titulos = [
    'TecnoBrasil Soluções',
    'EcoVida Consultoria',
    'AlphaTech Inovações',
    'Vanguarda Comércio e Serviços',
    'Sustentare Energia',
    'GlobalNet Informática',
    'PrimeiroPlano Publicidade',
    'BioCiclo Reciclagem',
    'OuroPulse Joalheria',
    'BellaVista Arquitetura',
    'InovaMais Tecnologia',
    'CéuAberto Turismo',
    'EcoGestão Ambiental',
    'CaféFino Indústria e Comércio',
    'BrasilMar Transportes',
    'MasterFit Academia',
    'Sol Nascente Engenharia',
    'QualiVita Saúde e Bem-Estar',
    'ClickFácil Desenvolvimento Web',
    'AstroBrasil Astronomia',
    'Pampa Moda Vestuário',
    'TerraVerde Paisagismo',
    'PrimeCasa Construtora',
    'BemEstar Farmácia',
    'AquaMundi Engenharia Ambiental',
    'NovaVisão Ótica',
    'ExpressoBrasil Logística',
    'LuzSolar Energia Renovável',
    'SolMar Turismo',
    'BrasilInova Consultoria Empresarial',
    'CriativaMente Design Gráfico',
    'EnergiaVerde Sustentabilidade',
    'ArteViva Galeria de Arte',
    'FinoPaladar Gastronomia',
    'BrasilDigital Marketing Digital',
    'AçoBrasil Indústria Metalúrgica',
    'SigaSeguro Corretora de Seguros',
    'LifeFit Saúde e Fitness',
    'BrasilData Tecnologia da Informação',
    'PuraEssência Cosméticos',
    'ArtVet Clínica Veterinária',
    'BioBrasil Pesquisa Científica',
    'PrimeiroPasso Recursos Humanos',
    'EcoServiços Ambientais',
    'CasaBrasil Decoração',
    'MasterTech Soluções Tecnológicas',
    'BrasilTrade Comércio Internacional',
    'Flor de Cerrado Floricultura',
    'TerraBrasil Agricultura Sustentável',
    'BrasilPet Produtos para Animais'
];

for ($i = 1; $i <= 50; $i++) {
    $nome = $titulos[$i - 1] ?? nomeAleatorio();
    $slug = strtolower(preg_replace('/[ -]+/', '-', $nome));

    $dado[] = [
        'id'               => $i,
        'id_admin_empresa' => [numeroAleatorio(1, 40)],
        'titulo'           => $nome,
        'texto_descricao'  => 'Um dos maiores ecommerces do mundo, com preços agressivos e excelente promoções. Não perca essa! AliExpress possui mais de 5900 tipos de produto de mais de 44 indústrias, incluindo: vestuário e acessórios, carros e motos, celulares etc.',
        'texto_restricao'  => 'Não contempla venda de produtos ofertados via hotsites, paralelos ao site principal. Os pontos referentes à compras via boletos bancários serão computados apenas após o pagamento do mesmo.',
        'texto_outro'      => '',
        'categoria'        => '2',
        'comissao_minima'  => rand(1, 10),
        'comissao_maxima'  => rand(1, 10),
        'url'              => $slug,
        'link_site'        => 'https://' . $slug . '.com.br',
        'imagem'           => 1,
        'status'           => 1
    ];
}

return $dado;
