<?php

namespace App\Models\Site\Endereco;

trait RetornoTrait
{
    public function montarRetorno(array $dado)
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'        => $r->id,
                'titulo'    => $r->titulo,
                'endereco'  => $r->completo,
                'latitude'  => $r->latitude,
                'longitude' => $r->longitude
            ];
        }
        return $retorno;
    }
}
