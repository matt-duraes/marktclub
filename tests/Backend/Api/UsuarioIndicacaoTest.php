<?php

namespace Tests\Api;

use App\Classes\UsuarioIndicacao\Helper;
use App\Classes\UsuarioIndicacao\Status;
use Erro\Excecao;
use Tests\Token\Clube;

class UsuarioIndicacaoTest extends Clube
{
    private ?string $idUsuarioIndicacao = '5595203c-f7b1-4211-9981-bf09eb236b35';
    private ?string $idIndicacao = null;
    private int $pagina = 1;
    private ?int $quantidade = null;
    private ?string $ordem = null;
    private ?string $pesquisa = null;
    private ?string $nome = null;
    private ?string $email = null;
    private ?string $status = null;

    /**
     * @return UsuarioIndicacaoTest
     * @throws Excecao
     */
    public function listarIndicacoesTest(): UsuarioIndicacaoTest
    {
        $this->api('usuario_indicacao:listar');
        $this
            ->Curl
            ->loginPainel()
            ->json([
                'pagina'     => $this->pagina,
                'quantidade' => $this->quantidade,
                'ordem'      => $this->ordem,
                'pesquisa'   => $this->pesquisa,
                'nome'       => $this->nome,
                'email'      => $this->email,
                'status'     => $this->status
            ])
            ->get('/usuario-indicacao');

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.lista')
            ->checkIndiceIgual('dado.pagina.atual', $this->pagina);
    }

    /**
     * @return UsuarioIndicacaoTest
     * @throws Excecao
     */
    public function salvarIndicacaoTest(): UsuarioIndicacaoTest
    {
        $this->api('usuario_indicacao:salvar');

        $body = $this->cryptEncode([
            'usuario'  => $this->idUsuarioIndicacao,
            'nome'     => $this->nomeCompleto(),
            'email'    => $this->email(),
            'telefone' => $this->telefone()
        ], Helper::CRIPTOGRAFAR);

        $indicacao = $this
            ->Curl
            ->loginPainel()
            ->body($body)
            ->post('/usuario-indicacao')
            ->array();

        $this->idIndicacao = $indicacao['dado']['id'] ?? 'sem-id';

        return $this
            ->checkStatus(201)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id')
            ->checkIndiceIgual('dado.quem_indicou.id', $this->idUsuarioIndicacao);
    }

    /**
     * @return UsuarioIndicacaoTest
     * @throws Excecao
     */
    public function buscarIndicacaoTest(): UsuarioIndicacaoTest
    {
        $this->api('usuario_indicacao:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-indicacao/' . $this->idIndicacao);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idIndicacao);
    }

    /**
     * @return UsuarioIndicacaoTest
     * @throws Excecao
     */
    public function atualizarIndicacaoTest(): UsuarioIndicacaoTest
    {
        $this->api('usuario_indicacao:atualizar');
        $this
            ->Curl
            ->loginPainel()
            ->body([
                'status' => Status::BLOQUEADO
            ])
            ->put('/usuario-indicacao/' . $this->idIndicacao);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return UsuarioIndicacaoTest
     * @throws Excecao
     */
    public function verificaSeStatusIndicacaoFoiAlteradaTest(): UsuarioIndicacaoTest
    {
        $this->api('usuario_indicacao:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-indicacao/' . $this->idIndicacao);

        return $this
            ->checkStatus(200)
            ->checkIndiceExiste('dado')
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceIgual('dado.id', $this->idIndicacao)
            ->checkIndiceIgual('dado.status', Status::BLOQUEADO);
    }

    /**
     * @return UsuarioIndicacaoTest
     * @throws Excecao
     */
    public function deletarIndicacaoTest(): UsuarioIndicacaoTest
    {
        $this->api('usuario_indicacao:deletar');
        $this
            ->Curl
            ->loginPainel()
            ->delete('/usuario-indicacao/' . $this->idIndicacao);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return UsuarioIndicacaoTest
     * @throws Excecao
     */
    public function verificaIndicacaoFoiDeletadaTest(): UsuarioIndicacaoTest
    {
        $this->api('usuario_indicacao:buscar');
        $this
            ->Curl
            ->loginPainel()
            ->get('/usuario-indicacao/' . $this->idIndicacao);

        return $this
            ->checkStatus(404)
            ->checkIndiceExiste('erro')
            ->checkNaoVazio('erro')
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.titulo', 'Não encontrado!')
            ->checkIndiceIgual('erro.mensagem', 'Indicação não encontrada ou inexistente');
    }
}
