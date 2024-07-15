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
        $link = $this->dominioLink;
        $dns = DNS_CNAME;
        $texto = "<p>Criar o domínio <strong>{$link}</strong> na CDN</p><p>DNS: <strong>{$dns}</strong></p>";

        if (
            $this->cdn->valor() == 'nao' ||
            !in_array($this->dominioTipo, ['dominio', 'temvantagens', 'temmaisvantagens'])
        ) {
            return;
        } elseif (in_array($this->dominioTipo, ['temvantagens', 'temmaisvantagens'])) {
            $texto = "<p>Criar o subdomínio <strong>{$link}</strong> na CDN</p>";
        }

        $this->salvarTarefa('infra', 'Configurar CDN', $texto, dificuldade: 1);
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
            dificuldade: 2
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
            dificuldade: 1
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
            dificuldade: 1
        );
    }

    private function criarApp()
    {
        if ($this->app->valor() == 'nao') {
            return;
        }
        $this->salvarTarefa(
            'app',
            'Criar APP para Android',
            '<p>Criar APP para Andriod</p>',
            dificuldade: 2
        );
        $this->salvarTarefa(
            'app',
            'Criar APP para IOS',
            '<p>Criar APP para IOS</p>',
            dificuldade: 2
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
