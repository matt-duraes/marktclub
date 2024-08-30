<?php

namespace App\Models\Api\Trait;

use Erro\Excecao;

trait ValidarRequestListar
{
    private array $propriedades = [
        'pagina'     => 'Página',
        'quantidade' => 'Quantidade',
        'ordem'      => 'Ordem',
        'publicado'  => 'Publicado',
        'dataInicio' => 'Data de início',
        'dataFinal'  => 'Data Final',
        'status'     => 'Status'
    ];

    /**
     * @return void
     * @throws Excecao
     */
    protected function validarRequestListar(): void
    {
        foreach ($this->propriedades as $propriedade => $texto) {
            if (!property_exists($this, $propriedade)) {
                continue;
            }
            if (!$this->$propriedade->vazio() && !$this->$propriedade->valido()) {
                mensagemErro(
                    "Campo $texto inválido!",
                    "O campo $texto informado não é válido."
                );
            }
        }
    }
}