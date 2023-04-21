<?php

namespace PainelApp\agenda\Models\Trait;

use PainelApp\agenda\Models\BuscarModel;

trait Evento
{
    private bool $meuEvento;
    private bool $temVideo;

    /**
     * Seta os dados do evento que será editado ou deletado
     *
     * @param string $id ID do evento
     */
    private function setarDadosDoEventoQueSeraEditado(string $id)
    {
        $Buscar = new BuscarModel($this->token);
        $evento = $Buscar->pegarEventoPeloId($id);

        $this->meuEvento = $evento['dono']['eu'];
        $this->temVideo = !empty($evento['video']['link']);
    }
}
