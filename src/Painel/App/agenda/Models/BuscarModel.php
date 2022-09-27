<?php

namespace Painel\Agenda\Models;

use DateTime;
use Helpers\CurlHelper;

final class BuscarModel
{
    use trait\Id;
    use trait\Cliente;
    use trait\Retorno;

    private CurlHelper $Cliente;

    /**
     * @param string $token Token do google para fazer login
     */
    public function __construct(
        private string $token
    ) {
        $this->setarCliente();
    }

    /**
     * Busca a lista de eventos da data x a y
     *
     * @param string $de    Data de inicio da busca
     * @param string $ate   Data fim para a busca
     * @return array Array com a lista de eventos
     */
    public function buscarListaEvento(string $de, string $ate): array
    {
        $this->validarDatas($de, $ate);

        $de = $this->converterDataParaRFC3339($de);
        $ate = $this->converterDataParaRFC3339($ate);

        $retorno = $this->Cliente->parametro([
            'calendarId' => 'primary',
            'timeMin' => $de,
            'timeMax' => $ate,
            'showDeleted' => 'false',
            'singleEvents' => 'true',
            'orderBy' =>  'startTime',
        ])->get('/primary/events')->object();

        $this->validarRetorno($retorno, 'items');
        return $this->pegarRetorno($retorno->items);
    }

    /**
     * Busca um evento pelo ID informado ou pega pelo request
     */
    public function pegarEventoPeloId(string $id)
    {
        $id = $this->pegarId($id);
        $retorno = $this->Cliente->get('/primary/events/' . $id)->object();

        $this->validarRetorno($retorno, 'id');
        return $this->pegarRetorno([$retorno])[0];
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS PRIVADOS
    |--------------------------------------------------------------------------
    */
    private function validarDatas(string $de, string $ate): void
    {
        if (!validarDate($de) || !validarDate($ate)) {
            mensagemErro('Erro!', 'A data da busca está incorreta.');
        }
        return;
    }

    private function converterDataParaRFC3339(string $data): string
    {
        $data = new DateTime($data);
        return $data->format(DateTime::RFC3339);
    }

    private function validarRetorno($retorno, string $campo = 'items'): void
    {
        if (existeErro($retorno, $campo)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao buscar os eventos do calendario.');
        }
        return;
    }
}
