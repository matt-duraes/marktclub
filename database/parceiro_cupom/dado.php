<?php

use App\Classes\ParceiroCupom\Status;
use App\Classes\ParceiroCupom\Tipo;

$titulos = [
    'Cupom de Desconto Moda10',
    'Oferta Beleza20',
    'Eletro15 Cupom',
    'CupomGourmet5',
    'CupomTurismo',
    'TechDiscount25',
    'CupomPetShop10',
    'BemEstar15 Cupom',
    'CupomLivraria20',
    'CupomEsportes',
    'CupomSaude10X',
    'Bebe15 Cupom',
    'CupomGames20',
    'ModaInfantil10',
    'CupomFerias15',
    'CupomAutomotivo5',
    'CupomJardinagem',
    'CupomEletronico20',
    'Viagem10 Cupom',
    'CupomCasa15',
    'CupomCafe5X',
    'CupomLivros10',
    'CupomCinema20',
    'BelezaCasa10',
    'CupomEletronicos',
    'CupomFitness15',
    'ModaSustentavel10',
    'CupomEstilo20',
    'CupomViagens10X',
    'CupomGastronomia',
    'CupomAutomovel10',
    'CupomGadgets5',
    'CupomFitness20',
    'CupomCulinaria15',
    'CupomCrianca10X',
    'CupomDecoracao20',
    'CupomHobbies10',
    'CupomSaude15X',
    'CupomAcessorios',
    'CupomBebe10',
    'CupomJogos15',
    'CupomLar10X',
    'CupomPapelaria',
    'CupomRelaxamento',
    'CupomModa5X',
    'CupomEsportivo10',
    'CupomJardim15',
    'CupomPasseio20',
    'CupomTecnologia',
    'CupomEntretenimento'
];
$listaTipo = (new Tipo())->listarNumero();
$listaStatus = (new Status())->listarNumero();
$tipoCodigo = (new Tipo(Tipo::CODIGO))->numero();
$tipoLink = (new Tipo(Tipo::LINK))->numero();
$seeds = [];
for ($i = 1; $i < 50; $i++) {
    $slug = strtolower(preg_replace('/[ -]+/', '-', $titulos[$i - 1]));
    $cupom = strtoupper(preg_replace('/[ -]+/', '', $titulos[$i - 1]));
    $tipo = valorAleatorio($listaTipo);
    $seeds[] = [
        'titulo'        => valorAleatorio($titulos),
        'tipo'          => $tipo,
        'texto'         => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla euismod, nisl eget aliquam ultricies, nunc nisl aliquet nunc, quis aliquam nisl',
        'data_validade' => dataFuturaAleatorio(),
        'cupom'         => $tipo == $tipoCodigo ? $cupom : null,
        'link'          => $tipo == $tipoLink ? 'https://google.com/' . $slug : null,
        'imagem'        => 'https://via.placeholder.com/300x300.png?text=' . $slug,
        'status'        => valorAleatorio($listaStatus),
    ];
}
return $seeds;
