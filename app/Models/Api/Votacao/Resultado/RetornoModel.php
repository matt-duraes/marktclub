<?php

namespace App\Models\Api\Votacao\Resultado;

use ORM\ORM;
use App\Models\Api\Votacao\Resultado\Trait\PropriedadeTrait;

final class RetornoModel extends ORM
{
    use PropriedadeTrait;

    public function __construct(
        private string $id
    ) {
        $this->setarObjecto();
        $this->montarResultado();
        $this->montarListaUsuario();
    }

    private function setarObjecto()
    {
        $this->Votacao = new VotacaoModel($this->id);
        $this->Pergunta = new PerguntaModel($this->Votacao->id);
        $this->Resposta = new RespostaModel($this->Pergunta);
        $this->Resultado = new ResultadoModel($this->Pergunta, $this->Resposta);
        $this->Voto = new VotoModel($this->Votacao->id);
        $this->Usuario = new UsuarioModel($this->Votacao->id);
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
            if ($this->Votacao->identificarUsuario) {
                $dado['Nome'] = $usuario[$r->id_usuario_cliente]->nome ?? 'Usuário deletado';
                $dado['CPF'] = $usuario[$r->id_usuario_cliente]->cpf ?? '-';
            }
            $votoLista[] = $dado;
            $this->Resultado->adicionarVoto($r->id_votacao_pergunta, $r->id_votacao_resposta);
        }

        $this->retorno['lista'] = $votoLista;
        $this->retorno['resultado'] = $this->Resultado->pegarResultado();
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
        $this->retorno['usuario'] = $this->Usuario->todos;
    }
}
