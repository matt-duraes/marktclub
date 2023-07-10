<?php

namespace App\Models\Api\AdminConstrutor;

final class ClubeModel
{
    public array $construtor;

    public function __construct(
        private ConstrutorEntity $Construtor
    ) {
        $this->montarClube($Construtor);
    }

    private function montarClube(ConstrutorEntity $Construtor)
    {
        $this->construtor = [
            'id'           => $Construtor->id,
            'titulo'       => $Construtor->titulo,
            'cor'          => $Construtor->cor,
            'link_logo'    => $Construtor->link_logo,
            'menu'         => [
                'convenio'       => $Construtor->menu_convenio->valor(),
                'convenio_mapa'  => $Construtor->menu_convenio_mapa->valor(),
                'cinema'         => $Construtor->menu_cinema->valor(),
                'turismo'        => $Construtor->menu_turismo->valor(),
                'promocao'       => $Construtor->menu_promocao->valor(),
                'sicoob_credito' => $Construtor->menu_sicoob_credito->valor(),
                'medicamento'    => $Construtor->menu_medicamento->valor(),
                'automovel'      => $Construtor->menu_automovel->valor(),
                'saude_vitoria'  => $Construtor->menu_saude_vitoria->valor(),
                'saude_amil'     => $Construtor->menu_saude_amil->valor(),
                'saude_seguros'  => $Construtor->menu_saude_seguros->valor(),
                'cashback'       => $Construtor->menu_cashback->valor(),
                'indicacao'      => $Construtor->menu_indicacao->valor(),
                'cupom'          => $Construtor->menu_cupom->valor(),
                'odontologia'    => $Construtor->menu_odontologia->valor(),
                'premium'        => $Construtor->menu_premium->valor(),
                'dependente'     => $Construtor->menu_dependente->valor(),
                'carteiria'      => $Construtor->menu_carteiria->valor(),
                'salavip'        => $Construtor->menu_salavip->valor(),
            ]
        ];
    }
}
