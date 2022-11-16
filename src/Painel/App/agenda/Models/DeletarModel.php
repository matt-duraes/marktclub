<?php

namespace PainelApp\agenda\Models;

use Helpers\CurlHelper;

final class DeletarModel
{

    use Trait\Cliente;
    use Trait\Id;
    use Trait\Evento;

    private CurlHelper $Cliente;
    private bool $meuEvento;

    /**
     * @param string $token Token do google para fazer login
     */
    public function __construct(
        private string $token
    ) {
        $this->setarCliente();
    }

    /**
     * Deleta um evento pelo ID
     *
     * @param string $id ID do evento que deseja deletar
     */
    public function deletarEvento(string $id)
    {
        $this->setarDadosDoEventoQueSeraEditado($id);

        $parametro = $this->setarParametros();
        $retorno = $this->Cliente->parametro($parametro)->delete('/primary/events/' . $this->pegarId($id));

        return $this->validarRetorno($retorno);
    }

    private function setarParametros()
    {
        $parametro = [];
        if ($this->meuEvento) {
            $parametro['sendUpdates'] = 'all';
        }
        return $parametro;
    }

    private function validarRetorno(CurlHelper $retorno): bool
    {
        if ($retorno->status() != 204) {
            mensagemErro('Erro', 'Ocorreu um erro ao tentar deletar seu evento.');
        }
        return true;
    }
}
