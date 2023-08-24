<?php

namespace Tests\Api;

use App\Classes\Solicitacao\Status;
use App\Classes\UsuarioCliente\GrauParentesco;
use App\Classes\UsuarioCliente\TipoUsuario;
use Modules\EstadoCivil;
use Tests\Tests;

class SolicitacaoChequeBonusTest extends Tests
{
    private string $idSolicitacao;

    private function getBody(array $array = []): array
    {
        $tipo_usuario = valorAleatorio(array_keys((new TipoUsuario())->select()));

        return array_merge([
            'automovel'                  => 'dc68285f-65e2-4db9-b37c-d216cf4ddd97',
            'data_termo'                 => $this->hoje(),
            'tipo_usuario'               => $tipo_usuario === TipoUsuario::DEPENDENTE ? TipoUsuario::TITULAR : $tipo_usuario,
            'nome'                       => nomeCompletoAleatorio(),
            'email_pessoal'              => emailAleatorio(),
            'telefone_celular'           => telefoneAleatorio(),
            'estado_civil'               => valorAleatorio(array_keys((new EstadoCivil())->select())),
            'rg'                         => rgAleatorio(),
            'data_nascimento'            => $this->dataPassada(),
            'endereco_cep'               => cepAleatorio(),
            'endereco_logradouro'        => logradouroAleatorio(),
            'endereco_numero'            => numeroAleatorio(),
            'endereco_complemento'       => complementoAleatorio(),
            'endereco_bairro'            => bairroAleatorio(),
            'endereco_cidade'            => cidadeAleatorio(),
            'endereco_estado'            => estadoAleatorio(),
            'dependente_nome'            => '',
            'dependente_email_pessoal'   => '',
            'dependente_rg'              => '',
            'dependente_documento'       => '',
            'dependente_grau_parentesco' => '',
            'dependente_data_nascimento' => '',
        ], $array);
    }

    private function getArrayDependete(): array
    {
        return [
            'tipo_usuario'               => TipoUsuario::DEPENDENTE,
            'dependente_nome'            => nomeCompletoAleatorio(),
            'dependente_email_pessoal'   => emailAleatorio(),
            'dependente_rg'              => rgAleatorio(),
            'dependente_documento'       => cpfAleatorio(),
            'dependente_grau_parentesco' => valorAleatorio(array_keys((new GrauParentesco())->select())),
            'dependente_data_nascimento' => $this->dataPassada()
        ];
    }

    public function listarTodosTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina' => 1
            ])
            ->get('/solicitacao-cheque-bonus');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado.lista');
    }

    public function salvarNovaSolicitacaoTipoUsuarioNaoDependenteTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody())
            ->post('/solicitacao-cheque-bonus')
            ->array()['dado'] ?? '';

        $this->idSolicitacao = $dado['id'] ?? '';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function salvarNovaSolicitacaoTipoUsuarioDependenteTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody($this->getArrayDependete()))
            ->post('/solicitacao-cheque-bonus')
            ->array()['dado'] ?? '';

        $this->idSolicitacao = $dado['id'] ?? '';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }

    public function naoPodeSalvarComDataNascimentoFuturaTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:salvar');

        $depentente = $this->getArrayDependete();
        $depentente['dependente_data_nascimento'] = $this->dataFutura();

        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody($depentente))
            ->post('/solicitacao-cheque-bonus');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de nascimento do dependente está inválida.');
    }

    public function naoPodeSalvarComDataTermoDiferenteDeHojeTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody([
                'data_termo' => $this->dataFutura()
            ]))
            ->post('/solicitacao-cheque-bonus');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'A data do termo está inválida.');
    }

    public function naoPodeSalvarComDataNascimentoDependenteFuturaTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:salvar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody([
                'tipo_usuario'               => TipoUsuario::DEPENDENTE,
                'dependente_data_nascimento' => $this->dataFutura()
            ]))
            ->post('/solicitacao-cheque-bonus');

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de nascimento do dependente está inválida.');
    }

    public function atualizarStatusValidoTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => valorAleatorio(array_keys((new Status())->select()))
            ])
            ->put('/solicitacao-cheque-bonus/' . $this->idSolicitacao);

        return $this
            ->checkStatus(204);
    }

    public function naoPodeAtualizarComStatusInvalidoTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => 'STATUS INVALIDO'
            ])
            ->put('/solicitacao-cheque-bonus/' . $this->idSolicitacao);

        return $this
            ->checkStatus(400)
            ->checkIndiceExiste('erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    public function buscarPorIdTest(): SolicitacaoChequeBonusTest
    {
        $this->api('solicitacao_cheque_bonus:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/solicitacao-cheque-bonus/' . $this->idSolicitacao);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceExiste('dado.id');
    }
}
