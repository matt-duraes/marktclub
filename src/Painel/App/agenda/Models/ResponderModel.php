<?php

namespace PainelApp\agenda\Models;

use Http\Request;

final class ResponderModel
{
    use Trait\Cliente;
    use Trait\Id;
    use Trait\Retorno;

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

        $teste = json_decode('{
            "attendees": [
                {
                    "email": "andrerodrigues@andrerodrigues.com",
                    "responseStatus": "declined"
                },
                {
                    "email": "andrebaixista@gmail.com",
                    "responseStatus": "declined"
                }
            ]
        }', true);
        $retorno = $this->Cliente
            ->json($teste)
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
        $Buscar = new BuscarModel($this->token);
        $evento = $Buscar->pegarEventoPeloId($this->request->id);

        $convidado = [];
        foreach ($evento['convidado'] as $item) {
            $dado = [
                'email' => $item['email'],
            ];
            if (!$item['eu']) {
                $dado['responseStatus'] = $this->pegarValorDaPresenca($this->request->confirmar);
            } else if (in_array($item['status'], ['sim', 'nao', 'talvez'])) {
                $dado['responseStatus'] = $this->pegarValorDaPresenca($item['status']);
            }
            if ($item['nome'] != $item['email']) {
                $dado['displayName'] = $item['nome'];
            }
            $convidado[] = $dado;
        }
        return $convidado;
    }
    private function pegarValorDaPresenca($valor)
    {
        return [
            'sim' => 'accepted',
            'talvez' => 'tentative',
            'nao' => 'declined'
        ][$valor];
    }

    private function validarRetorno($retorno): void
    {
        if (existeErro($retorno, 'id')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao responder evento, por favor, tente novamente.');
        }
        return;
    }
}
