<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Modules\Botao;
use Helpers\ApiHelper;
use App\Classes\DemandaDado\Area;

final class CriarClienteModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;
    private array $listaNotificacao = [];
    private string $usuarioInfra = '8fd85f9f7cc21d6e33399681d6e5fca7';
    private string $usuarioDns = '8fd85f9f7cc21d6e33399681d6e5fca7';
    private string $usuarioBancoDados = '8fd85f9f7cc21d6e33399681d6e5fca7';
    private string $usuarioCriacao = '3df1a38ec0919bd14162beabb73e12b4';
    private string $usuarioApp = '0f3a5572ba1343afca4c0b538354c59c';

    public function __construct(
        private string $empresaNome,
        private string $empresa,
        private string $dominioTipo,
        private string $dominioLink,
        private Botao $loginApi,
        private string $loginLink,
        private Botao $app,
        private string $texto,
        private Botao $cdn
    ) {
        $this->criarDemanda($empresaNome . 'Novo clube de vantagens', $texto, 'novo-cliente', Area::TECNOLOGIA);
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
        $this->atualizarTarefaTipoDemanda();
    }

    private function montarDominioLink()
    {
        $dominioTipo = $this->dominioTipo;

        if ($dominioTipo == 'temvantagens') {
            $this->dominioLink = 'https://' . $this->dominioLink . '.temvantagens.com.br';
        } elseif ($dominioTipo == 'temmaisvantagens') {
            $this->dominioLink = 'https://' . $this->dominioLink . '.temmaisvantagens.com.br';
        } elseif (in_array($dominioTipo, ['dominio', 'subdominio'])) {
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
            $this->usuarioDns,
            tempo: 20
        );
    }

    private function criarAppParaClube()
    {
        $this->salvarTarefa(
            'banco',
            'Criar app para o clube',
            '<p>Criar o APP para o clube no banco de dados</p>',
            $this->usuarioBancoDados,
            tempo: 60
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
            $this->usuarioBancoDados,
            tempo: 60
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
            $this->usuarioBancoDados,
            tempo: 120
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
        $this->salvarTarefa(
            'criacao',
            'Configurar Construtor',
            $texto,
            $this->usuarioCriacao,
            tempo: 60
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
            $this->usuarioInfra,
            tempo: 10
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
            $this->usuarioCriacao,
            tempo: 60
        );
        $this->salvarTarefa(
            'app',
            'Criar APP para Android',
            '<p>Criar APP para Andriod</p>',
            $this->usuarioApp,
            tempo: 60
        );
        $this->salvarTarefa(
            'app',
            'Criar APP para IOS',
            '<p>Criar APP para IOS</p>',
            $this->usuarioApp,
            tempo: 60
        );
    }

    private function notificarUsuario()
    {
        $Api = new ApiHelper(token: true);
        foreach ($this->listaNotificacao as $equipe) {
            $Api
                ->body([
                    'titulo'   => 'Criou uma nova tarefa para você',
                    'mensagem' => 'Foi criado uma nova tarefa para você, acesse a demanda e verifique o pedido.',
                    'link'     => LINK . '/demanda/tecnologia#demanda-' . $this->Demanda->dado->id,
                    'botao'    => 'Acessar painel',
                    'dono'     => sessao('USUARIO.id'),
                    'equipe'   => $equipe
                ])
                ->post('/painel-notificacao');
        }
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
