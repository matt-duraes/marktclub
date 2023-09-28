<?php

namespace Tests\Api;

use App\Classes\EnqueteSatisfacao\Atendimento;
use App\Classes\EnqueteSatisfacao\Navegar;
use App\Classes\EnqueteSatisfacao\Procura;
use App\Classes\EnqueteSatisfacao\Suporte;
use Erro\Excecao;
use Tests\Token\Clube;

class EnqueteSatisfacaoTest extends Clube
{
    private string $idEnquete;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->pegarToken();
        parent::__construct();
    }

    /**
     * @return EnqueteSatisfacaoTest
     * @throws Excecao
     */
    public function salvarNovaEnqueteSatisfacaoTest(): EnqueteSatisfacaoTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/enquete-satisfacao')
            ->array();

        $this->idEnquete = $dado['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return array
     */
    private function getBody(): array
    {
        return array_merge([
            'navegar'        => valorAleatorio(array_keys((new Navegar())->select())),
            'procura'        => valorAleatorio(array_keys((new Procura())->select())),
            'suporte'        => valorAleatorio(array_keys((new Suporte())->select())),
            'atendimento'    => valorAleatorio(array_keys((new Atendimento())->select())),
            'sistemas_clube' => ['0800', 'cinema', 'atendimento'],
            'comentario'     => 'Teste de comentário'
        ]);
    }

    /**
     * @return EnqueteSatisfacaoTest
     * @throws Excecao
     */
    public function buscarEnqueteTest(): EnqueteSatisfacaoTest
    {
        $this
            ->Curl
            ->get('/enquete-satisfacao/' . $this->idEnquete);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.id', $this->idEnquete);
    }

    /**
     * @return EnqueteSatisfacaoTest
     * @throws Excecao
     */
    public function listarEnquetesTest(): EnqueteSatisfacaoTest
    {
        $this
            ->Curl
            ->json([
                'pagina'     => 1,
                'quantidade' => '',
                'ordem'      => '',
                'status'     => ''
            ])
            ->get('/enquete-satisfacao');

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista');
    }
}
