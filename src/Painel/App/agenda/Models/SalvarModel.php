<?php

namespace PainelApp\agenda\Models;

use Http\Request;
use Helpers\CurlHelper;

final class SalvarModel
{
    use Trait\Cliente;
    use Trait\Salvar;
    use Trait\Retorno;

    private CurlHelper $Cliente;
    private bool $meuEvento = true;
    private bool $temVideo = false;

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
     * Envia um novo evento para salvar
     *
     * @param Request   $request    Request que o usuário envio
     * @param string    $token      Token do Google
     * @return array
     */
    public function salvarEvento()
    {
        $this->validarDadosEnviadosPeloUsuario();
        $dado = $this->montarDadosParaSalvar();

        $retorno = $this->Cliente
            ->json($dado)
            ->parametro([
                'sendUpdates' => 'all',
                'conferenceDataVersion' => '1'
            ])
            ->post('/primary/events')
            ->object();

        $this->validarRetorno($retorno);

        return $this->pegarRetorno([$retorno]);
    }

    private function validarRetorno($retorno): void
    {
        if (existeErro($retorno, 'id')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar seu evento, por favor, tente novamente.');
        }
        return;
    }
}
