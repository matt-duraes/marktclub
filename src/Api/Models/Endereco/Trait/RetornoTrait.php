<?php

namespace ApiModel\Endereco\Trait;

use Modules\Botao;

trait RetornoTrait
{
    private function montarRetorno(array $dado): array
    {
        $retorno = [];
        foreach ($dado as $r) {
            $retorno[] = [
                'id'          => $r->uuid,
                'titulo'      => $r->titulo,
                'completo'    => $this->formataEnderecoCompleto($r),
                'cep'         => $r->cep,
                'logradouro'  => $r->logradouro,
                'numero'      => $r->numero,
                'complemento' => $r->complemento,
                'referencia'  => $r->referencia,
                'bairro'      => $r->bairro,
                'cidade'      => $r->cidade,
                'estado'      => $r->estado,
                'pais'        => $r->pais,
                'latitude'    => $r->latitude,
                'longitude'   => $r->longitude,
                'principal'   => (new Botao($r->principal))->valor()
            ];
        }
        return $retorno;
    }

    private function formataEnderecoCompleto($endereco)
    {
        $completo = $endereco->logradouro;

        if (!empty($endereco->numero)) {
            $completo .= ' ' . $endereco->numero;
        }
        if (!empty($endereco->complemento)) {
            $completo .= ' ' . $endereco->complemento;
        }
        if (!empty($endereco->referencia)) {
            $completo .= ', ' . $endereco->referencia;
        }
        if (!empty($endereco->bairro)) {
            $completo .= ', ' . $endereco->bairro;
        }
        if (!empty($endereco->cidade)) {
            $completo .= ', ' . $endereco->cidade;
        }
        if (!empty($endereco->estado)) {
            $completo .= !empty($endereco->cidade) ? '/' : ' - ';
            $completo .= $endereco->estado;
        }
        if (!empty($endereco->cep)) {
            $completo .= ' - CEP: ' . strCep($endereco->cep);
        }

        return $completo;
    }
}
