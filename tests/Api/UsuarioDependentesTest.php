<?php

namespace Tests\Api;

use Tests\Tests;

class UsuarioDependentesTest extends Tests
{
    private string $idUsuario = '5595203c-f7b1-4211-9981-bf09eb236b35';
    private array $idDependentes = [];

    private function getBody(): array
    {
        return [
            'usuario' => $this->idUsuario,
            'nome'    => $this->cryptEncode(nomeCompletoAleatorio()),
            'cpf'     => $this->cryptEncode(cpfAleatorio()),
            'email'   => $this->cryptEncode(emailAleatorio()),
        ];
    }

    public function buscarDependentesDoUsuarioTest(): UsuarioDependentesTest
    {
        $this->api('usuario_dependente:listar');
        $this
            ->Curl
            ->json([
                'usuario' => $this->idUsuario
            ])
            ->get('/usuario-dependente');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function salvarNovoDependenteTest(): UsuarioDependentesTest
    {
        $this->api('usuario_dependente:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody())
            ->post('/usuario-dependente')
            ->array();

        $this->idDependentes[] = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function naoPodeAdicionarDependenteFaltandoInformacaoTest(): UsuarioDependentesTest
    {
        $this->api('usuario_dependente:salvar');

        $body = $this->getBody();
        unset($body['cpf']);

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-dependente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', "Erro no parâmetro enviado. Falta o parametro: 'cpf'.");
    }

    public function dependenteNaoPodeAdicionarDependeteTest(): UsuarioDependentesTest
    {
        $this->api('usuario_dependente:salvar');

        $body = $this->getBody();
        $body['usuario'] = $this->idDependentes[0];

        $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-dependente');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Um dependente não pode adicionar outros dependentes.');
    }

    public function buscarPeloIdDoDependenteTest(): UsuarioDependentesTest
    {
        $this->api('usuario_dependente:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'usuario' => $this->idDependentes[0]
            ])
            ->get('/usuario-dependente');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceNaoExiste('dado.0.id');
    }

    public function naoPodeAdicionar6DependentesTest(): UsuarioDependentesTest
    {
        for ($i = 0; $i < 6; $i++) {
            $this->api('usuario_dependente:salvar');
            $body = $this->getBody();

            $dado = $this
                ->Curl
                ->loginPainel()
                ->body($body)
                ->post('/usuario-dependente')
                ->array();

            if (array_key_exists('erro', $dado)) {
                $this
                    ->checkStatus(400)
                    ->checkIndiceIgual('status', 'erro')
                    ->checkIndiceIgual('erro.mensagem', 'Cada usuário só pode ter 5 dependentes.');
                break;
            }
            $this->idDependentes[] = $dado['dado']['id'] ?? 'sem-id';
        }
        return $this;
    }

    public function apagarDependenteAdicionadosTest(): UsuarioDependentesTest
    {
        foreach ($this->idDependentes as $id) {
            $this->api('usuario_dependente:deletar');
            $this
                ->Curl
                ->loginPainel()
                ->delete('/usuario-dependente/' . $id);
            $this->checkStatus(204);
        }

        return $this;
    }
}
