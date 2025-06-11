<?php

namespace App\Models\Site\Pagina;

final class HashModel
{
    public array $retorno = [];
    public function __construct(
        private array $hash
    )
    {
        foreach($hash as $item) {
            $dado = jsonDecode($item, true, true);
            $hash = base64Decode($dado['hash'], url: true);
            try {
                $Api = new ApiModel(
                    dado: $hash,
                    tipo: $dado['tipo']
                );
                $retorno = $Api->retorno;
            } catch (\Throwable $th) {
                $retorno = [];
            }

            $this->retorno[] = [
                'id' => $dado['id'],
                'dado' => $retorno
            ];
        }
    }
}
