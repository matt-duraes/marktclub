<?php

namespace App\Models\Api\ChatbotPerguntas;

use App\Classes\Geral\Status;
use Helpers\OrmHelper;
use ORM\Entity;

class PerguntasEntity extends Entity
{
    protected string $ormTabela = TABELA_CHATBOT_PERGUNTAS;
    public array $ormSalvar = ['categoria', 'pergunta', 'resposta', 'status'];
    public array $ormBuscar = ['categoria', 'pergunta', 'resposta', 'status'];
    public int|string $categoria;
    public string $pergunta;
    public string $resposta;
    public Status $status;

    public function regraSalvar()
    {
        $this->categoria = $this->pegarCategoria(
            campo: 'id',
            where: [
                ['categoria', $this->categoria],
                ['status', (new Status(Status::ATIVO))->numero()]
            ]
        );
    }

    public function regraPosBuscar()
    {
        $this->categoria = $this->pegarCategoria(
            campo: 'categoria',
            where: [
                ['id', $this->categoria],
                ['status', (new Status(Status::ATIVO))->numero()]
            ]
        );
    }

    private function pegarCategoria(string $campo, array $where): int|string
    {
        $dado = (new OrmHelper(TABELA_CHATBOT_CATEGORIA))
            ->pegarPrimeiroRegistro(
                where: $where,
                campo: ['id', 'categoria']
            );
        $this->validarCategoria($dado);
        return $dado[$campo];
    }

    private function validarCategoria($dado)
    {
        if(count($dado) == 0) {
            mensagemErro(titulo: "Erro ao salvar", mensagem: "O valor da categoria não é válido ou está inativo");
        }
    }
}
