<?php

namespace Tests\Api;

use Tests\Tests;

final class AnalyticsUsuarioTest extends Tests
{
    private string $de;
    private string $ate;
    private string $idUsuario = '5595203c-f7b1-4211-9981-bf09eb236b35';

    public function __construct()
    {
        parent::__construct();
        $this->de = dataRemover(hoje(), 7, 'dias');
        $this->ate = hoje();
        $this->api('relatorio_analytics:listar');
    }

    public function listarAnalyticsSemPaginacaoTest()
    {
        $this->fazerRequisicao($this->de, $this->ate);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function naoPodeListaComDataInicialComMaisDeSeteDiasTest()
    {
        $this->fazerRequisicao(dataRemover(hoje(), 10, 'dias'), $this->ate);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Você deve fazer uma busca com no máximo 7 dias de diferênça.');
    }
    public function naoPodeListaComDataFinalComMaisDeSeteDiasTest()
    {
        $this->fazerRequisicao($this->de, dataAdicionar(hoje(), 10, 'dias'));

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Você deve fazer uma busca com no máximo 7 dias de diferênça.');
    }
    public function naoPodeListarComDataFinalMenorQueDataInicialTest()
    {
        $this->fazerRequisicao(hoje(), dataRemover(hoje(), 1, 'dia'));

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data final da busca deve ser maior ou igual a data de começo.');
    }
    public function naoPodeListarComDataInicialESemDataFinalTest()
    {
        $this->fazerRequisicao(de: $this->de);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data final da busca é obrigatória.');
    }
    public function naoPodeListarComPaginacaoComDataInicialESemDataFinalTest()
    {
        $this->fazerRequisicao(de: $this->de, pagina: 1);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data final da busca é obrigatória.');
    }
    public function naoPodeListarComDataFinalESemDataInicialTest()
    {
        $this->fazerRequisicao(ate: $this->ate);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de começo da busca é obrigatória.');
    }
    public function naoPodeListarComPaginacaoComDataFinalESemDataInicialTest()
    {
        $this->fazerRequisicao(ate: $this->ate, pagina: 1);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de começo da busca é obrigatória.');
    }
    public function naoPodeListarSemPaginacaoESemDataTest()
    {
        $this->fazerRequisicao();

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de começo da busca é obrigatória.');
    }
    public function podeListarSemDataMasComPaginacaoTest()
    {
        $this->fazerRequisicao(pagina: 1);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }

    public function podeListarComPaginacaoEUsuarioTest()
    {
        $this->fazerRequisicao(pagina: 1, usuario: $this->idUsuario);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function podeListarSemPaginacaoSemUsuarioEComDatasTest()
    {
        $this->fazerRequisicao(de: $this->de, ate: $this->ate, usuario: $this->idUsuario);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function naoPodeListarComUsuarioInvalidoTest()
    {
        $this->fazerRequisicao(pagina: 1, usuario: uuid());

        return $this
            ->checkStatus(404)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Usuario buscado não foi encontrado.');
    }
    public function podeListarComPaginacaoEQuantidadeTest()
    {
        $this->fazerRequisicao(pagina: 1, quantidade: 5);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso');
    }
    public function naoPodeListaComPaginacaoErradaTest()
    {
        $this->fazerRequisicao(pagina: 'teste_erro');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo pagina está inválido.');
    }
    public function naoPodeListaComQuantidadeErradaTest()
    {
        $this->fazerRequisicao(pagina: 1, quantidade: 'teste_erro');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo quantidade está inválido.');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODO PRIVADO
    |--------------------------------------------------------------------------
    */
    private function fazerRequisicao(
        ?string $de = null,
        ?string $ate = null,
        null|int|string $pagina = null,
        null|int|string $quantidade = null,
        ?string $usuario = null
    ) {
        $where = [];
        if (!empty($de)) {
            $where['de'] = $de;
        }
        if (!empty($ate)) {
            $where['ate'] = $ate;
        }
        if (!empty($pagina)) {
            $where['pagina'] = $pagina;
        }
        if (!empty($quantidade)) {
            $where['quantidade'] = $quantidade;
        }
        if (!empty($usuario)) {
            $where['usuario'] = $usuario;
        }
        return $this
            ->Curl
            ->json($where)
            ->get('/relatorio/analytics');
    }
}
