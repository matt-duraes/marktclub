<?php

namespace App\Models\Site\Pagina;

final class HashModel
{
    public array $retorno = [];
    public function __construct(
        private array $hash,
        ?string $replace
    )
    {
        $replace = $this->converterReplace($replace);
        $this->rodarRequisicao($hash, $replace);
    }

    private function rodarRequisicao(array $hash, array $replace)
    {
        foreach($hash as $item) {
            $dado = jsonDecode($item, true, true);
            $hash = base64Decode($dado['hash'], url: true);

            // try {
                $Api = new ApiModel(
                    dado: $hash,
                    tipo: $dado['tipo'],
                    replace: $replace
                );
                $retorno = $Api->retorno;
            // } catch (\Throwable $th) {
            //     $retorno = [];
            // }

            $this->retorno[] = [
                'id' => $dado['id'],
                'dado' => $retorno
            ];
        }
    }

    private function converterReplace(?string $replace)
    {
        if(empty($replace)) {
            return [];
        }
        $replace = base64Decode($replace, url: true);
        if(!is_array($replace)) {
            return [];
        }
        $retorno = [];
        foreach($replace as $ind => $val) {
            $retorno['{{' . strCaixaAlta($ind) . '}}'] = $val;
        }
        return $retorno;
    }
}
