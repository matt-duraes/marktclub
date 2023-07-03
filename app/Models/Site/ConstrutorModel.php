<?php

namespace App\Models\Site;

final class ConstrutorModel
{
    /**
     * @return object
     */
    public function buscar(): object
    {
        $dado = true;

        if ($dado) :
            return $this->montarUnico($dado);
        endif;

        return [];
    }

    /**
     * @return object
     */
    public function montarUnico(): object
    {
        sessaoDeletar('CLUBE'); //TODO: unset colocado temporariamente enquanto rota do construtor ainda não existe
        $clube = $this->buscarClube();

        if ($clube) {
            return $clube;
        }

        $construtor = (object) [
            'saude' => (object) [
                'cnu'                 => true,
                'seguros'             => true,
                'vitoria'             => true,
                'unimedflorianopolis' => true,
                'amil'                => true,
            ],
        ];

        sessao('CLUBE', $construtor);

        return $construtor;
    }

    private function buscarClube()
    {
        return sessao('CLUBE', padrao: '');
    }

    /**
     * @return object
     */
    public function montaPlanoDeSaude(): object
    {
        $clube = $this->montarUnico();

        if (!$clube || !property_exists($clube, 'saude')) {
            return (object)[];
        }

        $saude = $clube->saude ?? null;
        if (!$saude) {
            return (object)[];
        }

        $cnu = $saude->cnu ?? false;
        $seguros = $saude->seguros ?? false;
        $vitoria = $saude->vitoria ?? false;
        $amil = $saude->amil ?? false;
        $unimedflorianopolis = $saude->unimedflorianopolis ?? false;

        return (object)[
            'cnu'                 => $cnu,
            'seguros'             => $seguros,
            'vitoria'             => $vitoria,
            'amil'                => $amil,
            'unimedflorianopolis' => $unimedflorianopolis,
        ];
    }
}
