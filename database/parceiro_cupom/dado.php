<?php

$titulos = [
    "Cupom de Desconto Moda10",
    "Oferta Beleza20",
    "Eletro15 Cupom",
    "CupomGourmet5",
    "CupomTurismo",
    "TechDiscount25",
    "CupomPetShop10",
    "BemEstar15 Cupom",
    "CupomLivraria20",
    "CupomEsportes",
    "CupomSaude10X",
    "Bebe15 Cupom",
    "CupomGames20",
    "ModaInfantil10",
    "CupomFerias15",
    "CupomAutomotivo5",
    "CupomJardinagem",
    "CupomEletronico20",
    "Viagem10 Cupom",
    "CupomCasa15",
    "CupomCafe5X",
    "CupomLivros10",
    "CupomCinema20",
    "BelezaCasa10",
    "CupomEletronicos",
    "CupomFitness15",
    "ModaSustentavel10",
    "CupomEstilo20",
    "CupomViagens10X",
    "CupomGastronomia",
    "CupomAutomovel10",
    "CupomGadgets5",
    "CupomFitness20",
    "CupomCulinaria15",
    "CupomCrianca10X",
    "CupomDecoracao20",
    "CupomHobbies10",
    "CupomSaude15X",
    "CupomAcessorios",
    "CupomBebe10",
    "CupomJogos15",
    "CupomLar10X",
    "CupomPapelaria",
    "CupomRelaxamento",
    "CupomModa5X",
    "CupomEsportivo10",
    "CupomJardim15",
    "CupomPasseio20",
    "CupomTecnologia",
    "CupomEntretenimento"
];

$dado = [];

for ($i = 1; $i < 50; $i++) {
    $slug = strtolower(preg_replace('/[ -]+/', '-', $titulos[$i]));
    $cupom = strtoupper(preg_replace('/[ -]+/', '', $titulos[$i]));
    $dado[] = [
        'id_parceiro_loja' => 1,
        'descricao'        => $titulos[$i],
        'cupom'            => $cupom,
        'desconto'         => rand(1, 100) . '%',
        'categoria'        => rand(1, 5),
        'link'             => 'https://google.com/' . $slug,
        'validade'         => dataFuturaAleatorio(),
        'status'           => 1,
        'auditado'         => 1,
    ];
}

return $dado;
