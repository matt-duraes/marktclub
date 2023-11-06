<?php

namespace Tests\Api;

use Tests\Tests;

final class UsuarioGrupoTest extends Tests
{
    private string $id;
    private string $uri = '/usuario-grupo';

    public function __construct()
    {
        parent::__construct();
    }

    public function listarGruposTest()
    {
        $this->api('usuario_grupo:listar');
        $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get($this->uri);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

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

    public function salvarNovoGrupoTest()
    {
        $titulo = nomeCompletoAleatorio();
        $indice = str_replace(' ', '-', strtolower($titulo));

        $this->api('usuario_grupo:salvar');
        $dado = $this
            ->Curl
            ->body([
                'titulo' => $titulo,
                'indice' => $indice,
                'status' => 'ativo'
            ])
            ->post($this->uri)
            ->array();

        $this->id = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.titulo', $titulo)
            ->checkIndiceIgual('dado.indice', $indice);
    }

    public function buscarNovoGrupoTest()
    {
        $this->api('usuario_grupo:buscar');
        $this
            ->Curl
            ->get($this->uri . '/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->id);
    }

    public function atualizarGrupoTest()
    {
        $titulo = nomeCompletoAleatorio();
        $indice = str_replace(' ', '-', strtolower($titulo));

        $this->api('usuario_grupo:atualizar');
        $this
            ->Curl
            ->body([
                'titulo' => $titulo,
                'indice' => $indice,
                'status' => 'ativo'
            ])
            ->put($this->uri . '/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function atualizarStatusTest()
    {
        $this->api('usuario_grupo:atualizar');
        $this
            ->Curl
            ->body([
                'status' => 'inativo'
            ])
            ->put($this->uri . '/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function verificarSeStatusMudouTest()
    {
        $this->api('usuario_grupo:buscar');
        $this
            ->Curl
            ->get($this->uri . '/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.status', 'inativo');
    }
}
