<?php

namespace Tests\Api;

use Tests\Tests;

final class UsuarioPagamentoTest extends Tests
{
    private string $id;
    private string $idUsuario = '0ab2712a-625f-4588-85e4-33aa68288915';
    private string $uri = '/usuario-pagamento';

    public function listarPagamentosTest()
    {
        $this->api('usuario_pagamento:listar');
        $this
            ->Curl
            ->json(['pagina' => 1])
            ->get($this->uri);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function salvarPagamentoTest()
    {
        $this->api('usuario_pagamento:salvar');
        $dado = $this
            ->Curl
            ->loginPainel()
            ->body([
                'data'    => hoje(),
                'valor'   => numeroAleatorio(1, 10000),
                'usuario' => $this->idUsuario
            ])
            ->post($this->uri)
            ->array();

        $this->id = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function buscarPagamentoTest()
    {
        $this->api('usuario_pagamento:buscar');
        $this
            ->Curl
            ->get($this->uri . '/' . $this->id);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function atualizarStatusPagamentoTest()
    {
        $this->api('usuario_pagamento:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => 'pago'
            ])
            ->put($this->uri . '/' . $this->id);

        return $this
            ->checkStatus(204);
    }

    public function validarSeStatusMudouTest()
    {
        $this->api('usuario_pagamento:buscar');
        $this
            ->Curl
            ->get($this->uri . '/' . $this->id)
            ->array();

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceNaoExiste('dado.id');
    }
}
