<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use App\Classes\ParceiroCashback\Categoria;
use Tests\Tests;

class ParceiroCashbackTest extends Tests
{
    protected string $idUltimo;
    protected string $scope = 'parceiro_cashback';
    protected string $uri = '/parceiro-cashback';
    public string $automatico = 'crud';

    public function atualizarApenasOStatusTest(): ParceiroCashbackTest
    {
        $this->api('parceiro_cashback:atualizar');
        $this
            ->Curl
            ->body([
                'status' => Status::INATIVO
            ])
            ->put('/parceiro-cashback/' . $this->idUltimo);

        return $this->checkStatus(204);
    }

    protected function pegarBody(): array
    {
        return [
            'titulo'          => nomeCompletoAleatorio(),
            'texto_descricao' => 'Descrição do parceiro',
            'texto_restricao' => 'Restrição do parceiro',
            'texto_outro'     => 'Outro texto do parceiro',
            'categoria'       => valorAleatorio(array_keys((new Categoria())->select())),
            'comissao_minima' => 2.8,
            'comissao_maxima' => 10000000,
            'status'          => Status::ATIVO,
            'empresa'         => ['14afa776394ada4be23be6acf7e3259e'],
            'link_site'       => 'https://www.google.com.br',
            'imagem'          => '123',
        ];
    }
}
