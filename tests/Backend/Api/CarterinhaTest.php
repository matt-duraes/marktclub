<?php

namespace Tests\Api;

use App\Classes\Carteirinha\Status;
use Erro\Excecao;
use Tests\Token\Clube;

class CarterinhaTest extends Clube
{
    private string $idCarteirinha;

    /**
     * @return CarterinhaTest
     * @throws Excecao
     */
    public function buscarCarteirinhaTest(): CarterinhaTest
    {
        $this->api('carteirinha:clube');
        $this
            ->Curl
            ->loginPainel()
            ->get('/carteirinha-clube');

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
            ->body([
                'bg_frente' => 'a',
                'bg_fundo'  => 'a'
            ])
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
            ->body([
                'bg_frente' => 'b',
                'bg_fundo'  => 'b',
                'status'    => Status::ATIVO
            ])
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
            ->checkIndiceIgual('erro.titulo', 'Não encontrado!')
            ->checkIndiceIgual('erro.mensagem', 'Modelo de carteirinha não encontrado ou inexistente');
    }
}
