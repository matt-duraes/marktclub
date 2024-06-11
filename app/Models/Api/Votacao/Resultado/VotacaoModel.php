<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;
use stdClass;
use Helpers\OrmHelper;

final class VotacaoModel extends ORM
{
    private stdClass $votacao;
    public int $id;
    public bool $identificarUsuario;

    public function __construct(
        string $id
    ) {
        $this->pegarVotacao($id);
        $this->verificarSePodePegarResultado();
    }

    private function pegarVotacao(string $id)
    {
        $votacao = (new OrmHelper(TABELA_VOTACAO_DADO))->pegarPrimeiroRegistro(
            campo: ['id', 'tipo', 'identificar_usuario', 'data_final', 'status'],
            where: ['uuid', $id],
            retorno: OrmHelper::RETORNO_OBJECT
        );
        $this->id = $votacao->id;
        $this->identificarUsuario = $votacao->identificar_usuario == 1;
        $this->votacao = $votacao;
    }

    private function verificarSePodePegarResultado()
    {
        $tipo = $this->votacao->tipo == 1 ? 'enquete' : 'votação';
        if ($this->votacao->status == 3) {
            mensagemErro('Erro!', 'Essa ' . $tipo . ' foi cancelada.');
        } elseif ($this->votacao->data_final > agora()) {
            mensagemErro('Erro!', 'Você só pode pegar o resultado depois do horário final da ' . $tipo . '.');
        }
    }
}
