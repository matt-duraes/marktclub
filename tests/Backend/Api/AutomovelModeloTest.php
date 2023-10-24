<?php

namespace Tests\Api;

use App\Classes\Geral\Status;
use Erro\Excecao;
use Tests\Token\Clube;

class AutomovelModeloTest extends Clube
{
    private array $idsParceiros;
    private array $statusValidos;
    private string $idModelo;

    /**
     * @throws Excecao
     */
    public function __construct()
    {
        $this->pegarToken();
        $this->getIdParceiros();
        $this->getStatusValidos();
        parent::__construct();
    }

    /**
     * @throws Excecao
     */
    private function getIdParceiros(): void
    {
        $dado = $this
            ->Curl
            ->json([
                'pagina' => 1
            ])
            ->get('/parceiro-loja')
            ->array()['dado']['lista'] ?? [];

        foreach ($dado as $parceiro) {
            if (!empty($parceiro['id'])) {
                $this->idsParceiros[] = $parceiro['id'];
            }
        }
    }

    private function getStatusValidos(): void
    {
        $this->statusValidos = array_keys((new Status())->select());
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function salvarModeloTest(): AutomovelModeloTest
    {
        $dado = $this
            ->Curl
            ->body($this->getBody())
            ->post('/automovel-modelo')
            ->array();

        $this->idModelo = $dado['dado']['id'] ?? 'sem-id';

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
        return [
            'titulo'      => nomeCompletoAleatorio(),
            'parceiro'    => !empty($this->idsParceiros) ? valorAleatorio($this->idsParceiros) : '',
            'imagem'      => 'asdsdsd',
            'data_inicio' => $this->dataPassada(),
            'data_final'  => $this->dataFutura(),
            'status'      => valorAleatorio($this->statusValidos)
        ];
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeSalvarComUmParceiroInvalidoTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['parceiro'] = 'PARCEIRO ERRADO';

        $this
            ->Curl
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'Parceiro não encontrado.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeSalvarComUmStatusInvalidoTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['status'] = 'STATUS INVALIDO';

        $this
            ->Curl
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeSalvarComUmParceiroVazioTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['parceiro'] = '';

        $this
            ->Curl
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo parceiro é obrigatório.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeSalvarComUmNomeVazioTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['titulo'] = '';

        $this
            ->Curl
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'O campo Título não pode ser vazio.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeSalvarDataInicioMaiorQueFinalTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['data_final'] = $this->dataPassada();
        $body['data_inicio'] = $this->dataFutura();

        $this
            ->Curl
            ->body($body)
            ->post('/automovel-modelo');

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('status', 'erro')
            ->checkIndiceIgual('erro.mensagem', 'A data de inicio não pode ser maior que a data final.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function buscarModeloTest(): AutomovelModeloTest
    {
        $this
            ->Curl
            ->get('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(200)
            ->checkIndiceIgual('status', 'sucesso')
            ->checkIndiceExiste('dado.id');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function atualizarTudoTest(): AutomovelModeloTest
    {
        $this
            ->Curl
            ->body($this->getBody())
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function atualizarSemStatusTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        unset($body['status']);

        $this
            ->Curl
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function atualizarApenasOStatusTest(): AutomovelModeloTest
    {
        $this
            ->Curl
            ->body(['status' => 'inativo'])
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeAtualizarComStatusInvalidoTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['status'] = 'STATUS INVALIDO';

        $this
            ->Curl
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não é um valor válido.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeAtualizarComParceiroInvalidoTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['parceiro'] = 'PARCEIRO INVALIDO';

        $this
            ->Curl
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'Parceiro não encontrado.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeAtualizarComStatusVazioTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['status'] = '';

        $this
            ->Curl
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo Status não pode ser vazio.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function naoPodeAtualizarComParceiroVazioTest(): AutomovelModeloTest
    {
        $body = $this->getBody();
        $body['parceiro'] = '';

        $this
            ->Curl
            ->body($body)
            ->put('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(400)
            ->checkIndiceIgual('erro.mensagem', 'O campo parceiro é obrigatório.');
    }

    /**
     * @return AutomovelModeloTest
     * @throws Excecao
     */
    public function deletarModeloTest(): AutomovelModeloTest
    {
        $this
            ->Curl
            ->delete('/automovel-modelo/' . $this->idModelo);

        return $this
            ->checkStatus(204);
    }
}
