<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;
use Helpers\OrmHelper;
use App\Models\Api\Votacao\Resultado\Trait\PropriedadeTrait;

final class RetornoModel extends ORM
{
    use PropriedadeTrait;

    public function __construct(
        string $id
    ) {
        $this->setarDadoVotacao($id);
        $this->setarObjecto();
        $this->montarResultado();
        $this->montarListaUsuario();
    }

    private function setarDadoVotacao(string $id)
    {
        $votacao = (new OrmHelper(TABELA_VOTACAO_DADO))->listar(
            campo: ['id', 'identificar_usuario'],
            where: ['uuid', $id]
        );
        $this->idVotacao = $votacao->id;
        $this->identificarUsuario = $votacao->identificar_usuario == 1;
    }

    private function setarObjecto()
    {
        $this->Pergunta = new PerguntaModel($this->idVotacao);
        $this->Resposta = new RespostaModel($this->Pergunta);
        $this->Resultado = new ResultadoModel($this->Pergunta, $this->Resposta);
        $this->Voto = new VotoModel($this->idVotacao);
        $this->Usuario = new UsuarioModel($this->idVotacao);
    }

    private function montarResultado()
    {
        $voto = $this->Voto->voto;
        $usuario = $this->Usuario->lista;
        $pergunta = $this->Pergunta->lista;
        $resposta = $this->Resposta->lista;

        $votoLista = [];
        foreach ($voto as $r) {
            $dado = [
                'Pergunta' => $pergunta[$r->id_votacao_pergunta],
                'Resposta' => $this->montarResposta(
                    $resposta[$r->id_votacao_resposta],
                    $r->resposta_outro,
                    $r->voto_livre
                ),
                'data'     => $r->data_criacao
            ];
            if ($this->identificarUsuario) {
                $dado['Nome'] = $usuario[$r->id_usuario_cliente]->nome ?? 'Usuário deletado';
                $dado['CPF'] = $usuario[$r->id_usuario_cliente]->cpf ?? '-';
            }
            $votoLista[] = $dado;
            $this->Resultado->voto($r->id_votacao_pergunta, $r->id_votacao_resposta);
        }

        $this->retorno['lista'] = $votoLista;
        $this->retorno['resultado'] = $this->Resultado->resultado;
    }

    private function montarResposta($resposta, $outro, $livre)
    {
        if (!empty($outro)) {
            $resposta .= ' (' . $outro . ')';
        } elseif (!empty($livre)) {
            $resposta .= ' (' . $livre . ')';
        }
        return trim($resposta);
    }

    private function montarListaUsuario()
    {
        $retorno = [];
        foreach ($this->Usuario->lista as $r) {
            $retorno[] = [
                'nome' => $r->nome,
                'cpf'  => $r->cpf
            ];
        }
        $this->retorno['usuario'] = $retorno;
    }
}
