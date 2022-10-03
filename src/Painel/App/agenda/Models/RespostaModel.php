<?php

namespace PainelApp\agenda\Models;

use Http\Request;

final class RespostaModel
{
    use Trait\Id;
    use Trait\Retorno;
    use Trait\Cliente;

    /**
     * @param string $token Token do google para fazer login
     */
    public function __construct(
        private Request $request,
        private string $token
    ) {
        $this->setarCliente();
    }

    /**
     * Confirmar ou negar presenca no evento
     *
     * @return array
     */
    public function confirmarPresenca(): array
    {
        $this->validarValorPresenca();
        $convidado = $this->criarListaDeConvidado();

        $retorno = $this->Cliente
            ->json($convidado)
            ->patch('/primary/events/' . $this->pegarId($this->request->id))
            ->object();

        $this->validarRetorno($retorno);
        return $this->pegarRetorno([$retorno]);
    }
    private function validarValorPresenca(): void
    {
        if (!in_array($this->request->confirmar, ['sim', 'nao', 'talvez'])) {
            mensagemErro('Valor inválido!', 'Você deve enviar um valor válido para confirmar sua presença.');
        }
        return;
    }
    private function criarListaDeConvidado()
    {
        $evento = $this->Cliente->get('/primary/events/' . $this->pegarId($this->request->id))->array();
        if (!array_key_exists('attendees', $evento)) {
            mensagemErro('Erro!', 'Você não está como convidado nesse evento.');
        }
        $convidado = $evento['attendees'];
        foreach ($evento['attendees'] as $ind => $item) {
            if (array_key_exists('self', $item) && $item['self'] == 1) {
                $convidado[$ind]['responseStatus'] = $this->pegarValorDaPresenca();
                break;
            }
        }

        return [
            'attendees' => $convidado
        ];
    }
    private function pegarValorDaPresenca()
    {
        return [
            'sim' => 'accepted',
            'talvez' => 'tentative',
            'nao' => 'declined'
        ][$this->request->confirmar];
    }

    private function validarRetorno($retorno): void
    {
        if (existeErro($retorno, 'id')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao responder evento, por favor, tente novamente.');
        }
        return;
    }
}
