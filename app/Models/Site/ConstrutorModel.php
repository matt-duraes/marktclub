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
            return $this->montar_unico($dado);
        endif;

        return [];

    }

    public function montar_unico()
    {

        return (object)[
            'saude' => (object)[
                'cnu' => true,
                'seguros' => true,
                'vitoria' => true,
                'unimedflorianopolis' => true,
                'amil' => true,
            ],
        ];
    }

}
