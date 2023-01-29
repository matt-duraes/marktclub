<?php

namespace Painel\Demanda\Models;

use stdClass;
use Modules\Botao;
use Helpers\ApiHelper;

final class CriarClienteModel
{
    private stdClass $Demanda;
    private array $listaNotificacao = [];

    private string $usuarioInfra = '8fd85f9f7cc21d6e33399681d6e5fca7';
    private string $usuarioDns = '8fd85f9f7cc21d6e33399681d6e5fca7';
    private string $usuarioBancoDados = '8fd85f9f7cc21d6e33399681d6e5fca7';
    private string $usuarioCriacao = '3df1a38ec0919bd14162beabb73e12b4';
    private string $usuarioApp = '0f3a5572ba1343afca4c0b538354c59c';

    public function __construct(
        private string $empresa,
        private string $dominioTipo,
        private string $dominioLink,
        private Botao $loginApi,
        private string $loginLink,
        private Botao $app,
        private string $texto,
        private Botao $cdn
    ) {
        $this->criarDemanda();
        $this->verificarSeSalvouDemanda();
        $this->montarDominioLink();
        $this->configurarDnsCdn();
        $this->criarDocumentacaoParaApi();
        $this->configurarConstrutor();
        $this->criarAppParaApp();
        $this->criarAppParaClube();
        $this->rodarScriptSubirConvenio();
        $this->criarApp();
        $this->notificarUsuario();
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
        if ($this->cdn->valor() == 'nao' || $this->dominioTipo != 'dominio') {
            return;
        }
        $this->salvarTarefa(
            'back-end',
            'Configurar CDN',
            '<p>Criar o domínio <strong>
            ' . $this->dominioLink . '
            </strong> na CDN</p><p>DNS: <strong>' . DNS_CNAME . '</strong></p>',
            $this->usuarioDns
        );
    }

    private function criarAppParaClube()
    {
        $this->salvarTarefa(
            'banco',
            'Criar app para o clube',
            '<p>Criar o APP para o clube no banco de dados</p>',
            $this->usuarioBancoDados
        );
    }

    private function criarAppParaApp()
    {
        if ($this->app->valor() == 'nao') {
            return;
        }
        $this->salvarTarefa(
            'banco',
            'Criar app para o aplicativo',
            '<p>Criar o APP para o aplicativo no banco de dados</p>',
            $this->usuarioBancoDados
        );
    }

    private function criarDocumentacaoParaApi()
    {
        if ($this->loginApi->valor() == 'nao') {
            return;
        }
        $this->salvarTarefa(
            'banco',
            'Criar documentação da API',
            '<p>Criar documentação para login via API do Cliente</p>',
            $this->usuarioBancoDados
        );
    }

    private function configurarConstrutor()
    {
        $texto = '<p>Link do Clube: <strong>' . $this->dominioLink . '</strong></p>';
        if ($this->loginApi->valor() == 'sim') {
            $texto .= '
                <p>O cliente fara o Login via API e o link do login será: <strong>
                https://' . $this->loginLink . '
                </strong></p>
            ';
        }
        $texto .= $this->texto;
        $this->salvarTarefa(
            'criacao',
            'Configurar Construtor',
            $texto,
            $this->usuarioCriacao
        );
    }
    private function rodarScriptSubirConvenio()
    {
        $this->salvarTarefa(
            'infra',
            'Rodar script para copiar parceiros',
            '
                <p>Rodar script para copiar convênios, chashback, promoções e etc para o novo Clube</p>
                <p>Excutar via URL <strong>http://novoclube.mkc</strong> que deve ser apontada para
                <strong>' . env('DNS_IP_API', '') . '</strong>
                </p>
            ',
            $this->usuarioInfra
        );
    }

    private function criarApp()
    {
        if ($this->app->valor() == 'nao') {
            return;
        }
        $this->salvarTarefa(
            'criacao',
            'Criar peças para o APP',
            '<p>Criar as peças para a criação dos APP no IOS e Android</p>',
            $this->usuarioCriacao
        );
        $this->salvarTarefa(
            'app',
            'Criar APP para Android',
            '<p>Criar APP para Andriod</p>',
            $this->usuarioApp
        );
        $this->salvarTarefa(
            'app',
            'Criar APP para IOS',
            '<p>Criar APP para IOS</p>',
            $this->usuarioApp
        );
    }

    private function salvarTarefa(string $tipo, string $titulo, string $texto, string $equipe)
    {
        $Api = new ApiHelper(token: true);
        $dado = [
            'demanda' => $this->Demanda->dado->id,
            'tipo' => $tipo,
            'titulo' => $titulo,
            'texto' => $texto
        ];
        if (!empty($equipe)) {
            $dado['equipe'] = $equipe;
        }
        $Api->body($dado)->post('/demanda-tarefa');
        if (!empty($equipe) && !in_array($equipe, $this->listaNotificacao)) {
            $this->listaNotificacao[] = $equipe;
        }
    }

    private function notificarUsuario()
    {
        $Api = new ApiHelper(token: true);
        foreach ($this->listaNotificacao as $equipe) {
            $Api
                ->body([
                    'titulo' => 'Criou uma nova tarefa para você',
                    'mensagem' => 'Foi criado uma nova tarefa para você, acesse a demanda e verifique o pedido.',
                    'link' => LINK . '/demanda#demanda-' . $this->Demanda->dado->id,
                    'botao' => 'Acessar painel',
                    'dono' => sessao('USUARIO.id'),
                    'equipe' => $equipe
                ])
                ->post('/painel-notificacao');
        }
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
