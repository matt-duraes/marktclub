<?php

namespace Tests\Api;

use Tests\Tests;
use App\Classes\Carteirinha\Status;

class CarterinhaTest extends Tests
{
    protected string $scope = 'carteirinha';
    protected string $uri = '/carteirinha';
    public string $automatico = 'lbsad';
    public string $mensagemErroDeletar = 'Modelo de carteirinha não encontrado ou inexistente';
    protected bool $automaticoPainel = true;

    public function __construct()
    {
        $this->tabela(TABELA_CARTEIRINHA)->resetar();
        parent::__construct();
    }

    public function listarCarteirinhaPelaEmpresaTest(): CarterinhaTest
    {
        $this->api('carteirinha:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'  => 1,
                'empresa' => '369fc307129e405b3f2f00620c7b012d',
                'status'  => Status::ATIVO
            ])
            ->get('/carteirinha');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado')
            ->checkIndiceIgual('status', 'sucesso');
    }

    protected function pegarBody(): array
    {
        return [
            'nome'            => valorAleatorio(['sim', 'nao']),
            'bg_frente'       => 'b',
            'empresa'         => '369fc307129e405b3f2f00620c7b012d',
            'titulo'          => 'Titulo carteirinha de teste',
            'cpf'             => cpfAleatorio(),
            'matricula'       => numeroAleatorio(),
            'data_nascimento' => dataPassadaAleatorio(),
            'estado'          => estadoAleatorio(),
            'status'          => Status::ATIVO
        ];
    }
}
