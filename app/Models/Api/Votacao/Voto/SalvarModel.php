<?php

namespace App\Models\Api\Votacao\Voto;

use App\Classes\Geral\Publicado;
use App\Classes\Votacao\Pergunta\Tipo as TipoPergunta;
use App\Models\Api\Votacao\Usuario\VotouModel;
use Erro\Excecao;
use Helpers\OrmHelper;
use Modules\DataHora;
use ORM\ORM;

final class SalvarModel extends ORM
{
    protected string $ormTabela = TABELA_VOTACAO_VOTO;
    private int $idVotacao;
    private int $idUsuario;
    private array $votacaoDado = [];
    private array $usuarioDado = [];

    /**
     * @param string $votacao
     * @param string $usuario
     * @param array  $resposta
     *
     * @throws Excecao
     */
    public function __construct(
        private readonly string $votacao,
        private readonly string $usuario,
        private readonly array $resposta
    ) {
        parent::__construct();
        $this->buscarVotacao();
        $this->validarVotacaoExiste();
        $this->verificarVotacaoPublicada();
        $this->buscarUsuario();
        $this->validarDados();
        $this->salvarVoto();
    }

    private function buscarVotacao(): void
    {
        $this->votacaoDado = (new OrmHelper(TABELA_VOTACAO_DADO))->pegarPrimeiroRegistro(
            ['uuid', $this->votacao],
            ['id', 'voto_unico', 'identificar_usuario', 'data_inicio', 'data_final', 'status']
        );
    }

    /**
     * @throws Excecao
     */
    private function validarVotacaoExiste(): void
    {
        if (empty($this->votacaoDado) || !array_key_exists('id', $this->votacaoDado)) {
            mensagemErro('Erro!', 'Não foi possível encontrar a votação.');
        }
    }

    /**
     * @throws Excecao
     */
    private function verificarVotacaoPublicada(): void
    {
        $dado = $this->votacaoDado;
        $publicado = new Publicado(
            new DataHora($dado['data_inicio']),
            new DataHora($dado['data_final']),
            $dado['status'] == 1
        );
        if ($publicado->indice() != 'sim') {
            mensagemErro('Erro!', 'A votação não está mais ativa.');
        }
        $this->idVotacao = $this->votacaoDado['id'];
    }

    private function buscarUsuario(): void
    {
        $usuario = (new OrmHelper(TABELA_USUARIO_CLIENTE))->pegarPrimeiroRegistro(
            ['uuid', $this->usuario],
            ['id', 'nome', 'cpf']
        );
        $this->idUsuario = $usuario['id'];
        $this->usuarioDado = $usuario;
    }

    /**
     * @throws Excecao
     */
    private function validarDados(): void
    {
        $usuario = $this->usuarioDado;
        if (empty($usuario) || !array_key_exists('id', $usuario)) {
            mensagemErro('Erro!', 'Não foi possível achar o usuário do voto.');
        } elseif (
            !array_key_exists('nome', $usuario)
            || !array_key_exists('cpf', $usuario)
            || !validarCpf($usuario['cpf'])
        ) {
            mensagemErro('Erro!', 'Seu nome e/ou CPF estão inválidos, atualize seus dados para votar.');
        } elseif (empty($this->votacao)) {
            mensagemErro('Erro!', 'Não foi possível achar a votação.');
        } elseif (!is_array($this->resposta) || !$this->resposta) {
            mensagemErro('Erro!', 'As respostas não podem ser lidas.');
        }
    }

    /**
     * @throws Excecao
     */
    private function salvarVoto(): void
    {
        $ormPergunta = new OrmHelper(TABELA_VOTACAO_PERGUNTA);
        $ormResposta = new OrmHelper(TABELA_VOTACAO_RESPOSTA);
        foreach ($this->resposta as $pergunta => $resposta) {
            $idPergunta = $ormPergunta->pegarIdPeloUuid($pergunta);
            $this->validarPerguntaObrigatoria($ormPergunta, $idPergunta, $resposta);
            foreach ($resposta as $uuid) {
                $idResposta = $ormResposta->pegarIdPeloUuid($uuid);
                $this->validarRespostaBloqueada($ormResposta, $idResposta);
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
        (new VotouModel($this->usuarioDado, $this->idVotacao));
    }

    /**
     * @param OrmHelper  $ormHelper
     * @param int|string $idPergunta
     * @param array|null $idResposta
     *
     * @throws Excecao
     */
    private function validarPerguntaObrigatoria(
        OrmHelper $ormHelper,
        int|string $idPergunta,
        array $idResposta = null
    ): void {
        $pergunta = $ormHelper->pegarUltimoRegistro(
            ['id', $idPergunta],
            ['titulo', 'tipo', 'pode_nulo'],
            'object'
        );
        if (empty($pergunta)) {
            mensagemErro(
                'Pergunta não encontrada/inexistente!!',
                'Não encontramos a pergunta. Por favor tente novamente.'
            );
        }

        $tipoPergunta = new TipoPergunta($pergunta->tipo);
        if (
            $tipoPergunta->indice() === TipoPergunta::UMA_ESCOLHA
            && (empty($pergunta->pode_nulo))
            && empty($idResposta)
        ) {
            mensagemErro(
                'Resposta obrigatória!!',
                "Você deve escolher uma alternativa para `$pergunta->titulo`"
            );
        } elseif (
            $tipoPergunta->indice() === TipoPergunta::MULTIPLA_ESCOLHA
            && (empty($pergunta->pode_nulo))
            && empty($idResposta)
        ) {
            mensagemErro(
                'Resposta obrigatória!!',
                "Você deve escolher pelo menos uma alternativa para `$pergunta->titulo`"
            );
        }
    }

    /**
     * @param OrmHelper  $ormHelper
     * @param int|string $idResposta
     *
     * @throws Excecao
     */
    private function validarRespostaBloqueada(
        OrmHelper $ormHelper,
        int|string $idResposta
    ): void {
        $resposta = $ormHelper->pegarUltimoRegistro(
            ['id', $idResposta],
            ['titulo', 'escrever_voto', 'voto_nulo'],
            'object'
        );
        if (empty($resposta)) {
            mensagemErro(
                'Resposta não encontrada/inexistente!!',
                'Não encontramos a resposta. Por favor tente novamente.'
            );
        }

        if (!empty($resposta->voto_nulo) && $resposta->voto_nulo == '1') {
            mensagemErro(
                'Resposta invalida!!',
                'Resposta bloqueada. Por favor tente novamente.'
            );
        }
    }
}
