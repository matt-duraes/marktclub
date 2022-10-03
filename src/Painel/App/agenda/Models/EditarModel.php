<?php

namespace PainelApp\agenda\Models;

use Http\Request;
use Helpers\CurlHelper;

final class EditarModel
{
    use trait\Salvar;
    use trait\Cliente;
    use trait\Retorno;
    use trait\Id;
    use trait\Evento;

    private CurlHelper $Cliente;
    private bool $temVideo;

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
     * Envia uma alteração para o evento existente
     *
     * @return array
     */
    public function editarEvento(): array
    {
        $this->setarDadosDoEventoQueSeraEditado($this->request->id);
        $this->validarDadosEnviadosPeloUsuario();

        $dado = $this->montarDadosParaSalvar();
        $parametro = $this->pegarParametros($dado);

        $retorno = $this->Cliente
            ->json($dado)
            ->parametro($parametro)
            ->put('/primary/events/' . $this->pegarId($this->request->id))
            ->object();

        $this->validarRetorno($retorno);

        return $this->pegarRetorno([$retorno]);
    }

    private function pegarParametros(array $dado)
    {
        $parametro = [];
        if ($this->request->notificar) {
            $parametro['sendUpdates'] = 'all';
        }
        if (array_key_exists('conferenceData', $dado)) {
            $parametro['conferenceDataVersion'] = '1';
        }
        return $parametro;
    }
    private function validarRetorno($retorno): void
    {
        if (existeErro($retorno, 'id')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao editar seu evento, por favor, tente novamente.');
        }
        return;
    }
}
