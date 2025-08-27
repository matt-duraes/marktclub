<?php

namespace App\Models\Api\Saude\Contratacao\Proasa;

use Helpers\CurlHelper;

abstract class ApiAbstract extends CurlHelper {
    private string $token;
    public bool $existe = false;
    public string $id = '';

    public function __construct()
    {
        parent::__construct(url: $this->pegarLinkApi());
        $this->token = env('PROASA_RD_TOKEN', '');
    }

    private function pegarLinkApi(): string
    {
        $link = env('PROASA_RD_LINK', '');
        if(empty($link)) {
            return '';
        }
        return preg_replace('/\/+$/', '', $link );
    }

    protected function headerAccept(): array
    {
        return [
            'accept' => 'application/json'
        ];
    }
    protected function headerAcceptJson()
    {
        return [
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ];
    }

    protected function uri(string $uri)
    {
        return '/' . preg_replace('/^\/+/', '', $uri) . '?token=' . $this->token;
    }

    protected function validarBuscaExiste(array $busca, string $campo): void
    {
        if(!validarIndiceExiste($busca, ['total', $campo]) || $busca['total'] === 0) {
            return;
        }
        $this->existe = true;
        $this->id = $busca[$campo][0]['id'];
    }

    protected function validarDadoSalvo(array $salvar) {
        if(!validarIndiceExiste($salvar, 'id') && !empty($salvar['id'])) {
            mensagemErro('Erro!', 'Ocorreu um erro ao tentar salvar sua solicitação, por favor, tente novamente.');
        }
        $this->id = $salvar['id'];
    }
}
