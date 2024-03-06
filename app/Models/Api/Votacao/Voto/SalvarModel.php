<?php

namespace App\Models\Api\Votacao\Voto;

use ORM\ORM;
use Modules\DataHora;
use Helpers\OrmHelper;
use App\Classes\Geral\Publicado;
use App\Models\Api\Votacao\Usuario\VotouModel;

final class SalvarModel extends ORM
{
    protected string $ormTabela = TABELA_VOTACAO_VOTO;
    private int $idVotacao;
    private int $idUsuario;
    private array $votacaoDado;

    public function __construct(
        private string $votacao,
        private string $usuario,
        private array $resposta
    ) {
        parent::__construct();
        $this->buscarVotacao();
        $this->verificarVotacaoPublicada();
        $this->buscarUsuario();
        $this->validarDados();
        $this->salvarVoto();
    }

    private function buscarVotacao()
    {
        $this->votacaoDado = (new OrmHelper(TABELA_VOTACAO_DADO))->pegarPrimeiroRegistro(
            where: ['uuid', $this->votacao],
            campo: ['id', 'voto_unico', 'identificar_usuario', 'data_inicio', 'data_final', 'status']
        );
    }

    private function verificarVotacaoPublicada()
    {
        if (empty($this->votacaoDado)) {
            mensagemErro('Erro!', 'Não foi possível encontrar a votação.');
        }
        $dado = $this->votacaoDado;

        $publicado = new Publicado(
            inicio: new DataHora($dado['data_inicio']),
            final: new DataHora($dado['data_final']),
            ativo: $dado['status'] == 1
        );
        if ($publicado->indice() != 'sim') {
            mensagemErro('Erro!', 'A votação não está mais ativa.');
        }
        $this->idVotacao = $this->votacaoDado['id'];
    }

    private function buscarUsuario()
    {
        $this->idUsuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarIdPeloUuid($this->usuario);
    }

    private function validarDados()
    {
        if (empty($this->usuario)) {
            mensagemErro('Erro!', 'Não foi possível achar o usuário do voto.');
        } elseif (empty($this->votacao)) {
            mensagemErro('Erro!', 'Não foi possível achar a votação.');
        } elseif (!is_array($this->resposta) || !$this->resposta) {
            mensagemErro('Erro!', 'As respostas não podem ser lidas.');
        }
    }

    private function salvarVoto()
    {
        $ormPergunta = new OrmHelper(TABELA_VOTACAO_PERGUNTA);
        $ormResposta = new OrmHelper(TABELA_VOTACAO_RESPOSTA);
        foreach ($this->resposta as $pergunta => $resposta) {
            $idPergunta = $ormPergunta->pegarIdPeloUuid($pergunta);
            foreach ($resposta as $uuid) {
                $idResposta = $ormResposta->pegarIdPeloUuid($uuid);
                $dado = [
                    'id_votacao_dado'     => $this->idVotacao,
                    'id_votacao_pergunta' => $idPergunta,
                    'id_votacao_resposta' => $idResposta,
                    'status'              => 1
                ];
                if ($this->votacaoDado['identificar_usuario']) {
                    $dado['id_usuario_cliente'] = $this->idUsuario;
                }
                $this
                    ->dado($dado)
                    ->insert();
            }
        }
        (new VotouModel($this->idUsuario, $this->idVotacao));
    }
}
