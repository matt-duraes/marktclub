<?php

namespace Painel\Agenda\Models\Trait;

use Helpers\CurlHelper;

trait Cliente
{

    private CurlHelper $Cliente;

    private function setarCliente()
    {
        $this->Cliente = new CurlHelper('https://www.googleapis.com/calendar/v3/calendars');
        $this->Cliente->header([
            'authorization' => 'Bearer ' . $this->token,
            'Content-Type' => 'application/json'
        ]);
    }
}
