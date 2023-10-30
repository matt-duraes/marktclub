<?php

namespace Tests\Api;

use App\Classes\Carteirinha\Status;
use Erro\Excecao;
use Tests\Token\Clube;

class CarterinhaTest extends Clube
{
    private string $idCarteirinha;

    public function __construct()
    {
        $this->tabela(TABELA_CARTEIRINHA)->resetar();
        parent::__construct();
    }

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function listarModelosCarteirinhasTest(): CarterinhaTest
    {
        $this->api('carteirinha:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'     => 1,
                'quantidade' => null,
                'ordem'      => null,
                'empresa'    => null,
                'status'     => null
            ])
            ->get('/carteirinha')
            ->array();

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado')
            ->checkIndiceIgual('status', 'sucesso');
    }

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function salvarModeloCarteirinhaTest(): CarterinhaTest
    {
        $this->api('carteirinha:salvar');
        $modelo = $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody())
            ->post('/carteirinha')
            ->array();

        $this->idCarteirinha = $modelo['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado')
            ->checkIndiceIgual('status', 'sucesso');
    }

    /**
     * @return array
     */
    private function getBody(): array
    {
        return [
            'bg_fundo'        => 'b',
            'bg_frente'       => 'b',
            'empresa'         => '369fc307129e405b3f2f00620c7b012d',
            'titulo'          => 'Titulo carteirinha de teste',
            'nome'            => $this->nomeCompleto(),
            'cpf'             => $this->cpf(),
            'matricula'       => $this->numero(),
            'data_nascimento' => $this->dataPassada(),
            'estado'          => $this->estado(),
            'status'          => Status::ATIVO
        ];
    }

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function buscarModeloCarteirinhaTest(): CarterinhaTest
    {
        $this->api('carteirinha:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/carteirinha/' . $this->idCarteirinha);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado')
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function listarCarteirinhaPelaEmpresaTest(): CarterinhaTest
    {
        $this->api('carteirinha:listar');
        $this
            ->Curl
            ->json([
                'pagina'  => 1,
                'empresa' => '369fc307129e405b3f2f00620c7b012d',
                'status'  => Status::ATIVO
            ])
            ->loginPainel()
            ->get('/carteirinha');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado')
            ->checkIndiceIgual('status', 'sucesso');
    }

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function alterarModeloCarteirinhaTest(): CarterinhaTest
    {
        $this->api('carteirinha:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body($this->getBody())
            ->put('/carteirinha/' . $this->idCarteirinha);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function verificarSeModeloCarteirinhaFoiAtualizadoTest(): CarterinhaTest
    {
        $this->api('carteirinha:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/carteirinha/' . $this->idCarteirinha);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkNaoVazio('dado')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.bg_frente', 'b')
            ->checkIndiceIgual('dado.bg_fundo', 'b')
            ->checkIndiceIgual('dado.status', Status::ATIVO);
    }

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function deletarModeloCarteirinhaTest(): CarterinhaTest
    {
        $this->api('carteirinha:deletar');
        $this
            ->Curl
            ->loginPainel()
            ->delete('/carteirinha/' . $this->idCarteirinha);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function verificarSeModeloCarteirinhaFoiDeletadoTest(): CarterinhaTest
    {
        $this->api('carteirinha:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/carteirinha/' . $this->idCarteirinha);

        return $this
            ->checkStatus(404)
            ->checkIndiceExiste('erro')
            ->checkNaoVazio('erro')
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.titulo', 'Página não existe!')
            ->checkIndiceIgual('erro.mensagem', 'Essa página ou recurso não existe ou foi movida para outra URL.');
    }
}
