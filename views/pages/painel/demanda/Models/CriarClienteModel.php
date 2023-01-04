<?php

namespace Painel\Demanda\Models;

use stdClass;
use Helpers\ApiHelper;

final class CriarClienteModel
{
    private stdClass $Demanda;

    public function __construct(
        private string $empresa,
        private string $dominioTipo,
        private string $dominioLink,
        private bool $loginApi,
        private string $loginLink,
        private bool $app,
        private string $texto
    ) {
        $this->criarDemanda();
        $this->verificarSeSalvouDemanda();
        $this->montarDominioLink();
        $this->configurarDnsCdn();
        $this->criarDocumentacaoParaApi();
        $this->configurarConstrutor();
        $this->criarApp();
    }

    private function criarDemanda()
    {
        $Api = new ApiHelper(token: true);
        $this->Demanda = $Api->body([
            'empresa' => $this->empresa,
            'titulo' => 'Novo clube de vantagens',
            'tipo' => 'novo-cliente'
        ])->post('/demanda-dado')->object();
    }

    private function verificarSeSalvouDemanda()
    {
        $Demanda = $this->Demanda;
        if (!is_object($Demanda) || !object_key_exists('status', $Demanda)) {
            mensagemErro('Erro!', 'Ocorreu um erro ao salvar sua demanda, por favor, tente novamente.');
        } else if ($this->Demanda->status != 'sucesso') {
            mensagemErro($Demanda->erro->titulo, $Demanda->erro->mensagem);
        }
    }

    private function montarDominioLink()
    {
        $dominioTipo = $this->dominioTipo;

        if ($dominioTipo == 'temvantagens') {
            $this->dominioLink = 'https://' . $this->dominioLink . '.temvantagens.com.br';
        } else if ($dominioTipo == 'temmaisvantagens') {
            $this->dominioLink = 'https://' . $this->dominioLink . '.temmaisvantagens.com.br';
        } else if (in_array($dominioTipo, ['dominio', 'subdominio'])) {
            $this->dominioLink = 'https://' . str_replace(['https://', 'http://'], '', $this->dominioLink);
        }
    }

    private function configurarDnsCdn()
    {
        if (!in_array($this->dominioTipo, ['temvantagens', 'temmaisvantagens'])) {
            return;
        }
        $this->salvarTarefa(
            'back-end',
            'Configurar CDN',
            '<p>Criar o domínio <strong>' . $this->dominioLink . '</strong> na CDN</p><p>DNS: <strong>' . DNS_CNAME . '</strong></p>'
        );
    }

    private function criarDocumentacaoParaApi()
    {
        if (!$this->loginApi) {
            return;
        }
        $this->salvarTarefa('banco', 'Criar documentação da API', '<p>Criar documentação para login via API do Cliente</p>');
    }

    private function configurarConstrutor()
    {
        $texto = '<p>Link do Clube: <strong>' . $this->dominioLink . '</strong></p>';
        if ($this->loginApi) {
            $texto .= '<p>O cliente fara o Login via API e o link do login será: <strong>' . $this->loginLink . '</strong></p>';
        }
        $texto .= $this->texto;
        $this->salvarTarefa('criacao', 'Configurar Construtor', $texto);
    }

    private function criarApp()
    {
        if (!$this->app) {
            return;
        }
        $this->salvarTarefa('criacao', 'Criar peças para o APP', '<p>Criar as peças para a criação dos APP no IOS e Android</p>');
        $this->salvarTarefa('app', 'Criar APP para Android', '<p>Criar APP para Andriod</p>');
        $this->salvarTarefa('app', 'Criar APP para IOS', '<p>Criar APP para IOS</p>');
    }

    private function salvarTarefa($tipo, $titulo, $texto)
    {
        $Api = new ApiHelper(token: true);
        $Api->body([
            'demanda' => $this->Demanda->dado->id,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'texto' => $texto
        ])->post('/demanda-tarefa');
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
