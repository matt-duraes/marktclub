<?php

namespace Tests\Api;

use Tests\Tests;

final class UsuarioGrupoTest extends Tests
{
    protected string $idUltimo;
    protected string $uri = '/usuario-grupo';
    protected string $scope = 'usuario_grupo';
    public string $automatico = 'lbsa';

    public function listarGrupoPorStatusAtivoTest()
    {
        $this->api('usuario_grupo:listar');
        $this
            ->Curl
            ->json([
                'pagina' => 1,
                'status' => 'ativo'
            ])
            ->get($this->uri);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.lista.0.status', 'ativo');
    }

    public function listarGrupoPorStatusInativoTest()
    {
        $this->api('usuario_grupo:listar');
        $this
            ->Curl
            ->json([
                'pagina' => 1,
                'status' => 'inativo'
            ])
            ->get($this->uri);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.lista.0.status', 'inativo');
    }

    public function atualizarStatusTest()
    {
        $this->api('usuario_grupo:atualizar');
        $this
            ->Curl
            ->body([
                'status' => 'inativo'
            ])
            ->put($this->uri . '/' . $this->idUltimo);

        return $this
            ->checkStatus(204);
    }

    protected function pegarBody()
    {
        return [
            'titulo' => nomeCompletoAleatorio(),
            'indice' => str_replace(' ', '-', strtolower(nomeCompletoAleatorio())),
            'status' => 'ativo'
        ];
    }
}
