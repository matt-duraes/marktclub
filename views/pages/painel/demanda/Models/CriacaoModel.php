<?php

namespace Painel\Demanda\Models;

use stdClass;
use Http\Request;
use Helpers\ApiHelper;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Tipo;

final class CriacaoModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;
    private array $listaNotificacao = ['3df1a38ec0919bd14162beabb73e12b4'];
    private string $empresa;

    public function __construct(
        private Request $request
    ) {
        $this->empresa = $request->empresa;
        $this->criarDemanda($request->titulo, Tipo::CRIACAO, Area::CRIACAO);
        $this->verificarSeSalvouDemanda();
        $this->criarHistorico();
        $this->criarDemandaSite();
        $this->criarDemandaRedeSocial();
        $this->criarDemandaImpresso();
        $this->criarDemandaKit();
        $this->criarDemandaVideo();
        $this->criarDemandaOutro();
        $this->notificarUsuario();
    }

    private function criarHistorico()
    {
        (new ApiHelper(token: true))
            ->body([
                'app' => ["demanda_dado"],
                'relacionado' => [$this->id()],
                'acao' => 'mensagem',
                'mensagem' => $this->request->criacao_texto,
            ])
            ->post('/painel-historico');
    }

    private function criarDemandaSite()
    {
        if ($this->request->criacao_site != 'sim') {
            return;
        }
        $this->criarTarefaPadrao(
            titulo: 'Criar peça para site',
            texto: '
                <p>Criar peça para site no tamanho <strong>'
                    . $this->request->site_largura . '</strong>x<strong>'
                    . $this->request->site_altura . '</strong></p>
            '
        );
    }
    private function criarDemandaRedeSocial()
    {
        if ($this->request->criacao_social != 'sim') {
            return;
        }

        if ($this->request->digital_stories == 'sim') {
            $this->criarTarefaPadrao('Criar peça para stories');
        }
        if ($this->request->feed_whatsapp == 'sim') {
            $this->criarTarefaPadrao('Criar peça para o feed do WhatsApp');
        }
        if ($this->request->feed_instagram == 'sim') {
            $this->criarTarefaPadrao('Criar peça para o feed do Instagram');
        }
        if ($this->request->feed_facebook == 'sim') {
            $this->criarTarefaPadrao('Criar peça para o feed do Facebook');
        }
        if ($this->request->feed_linkedin == 'sim') {
            $this->criarTarefaPadrao('Criar peça para o feed do LinkedIn');
        }
        if ($this->request->feed_twitter == 'sim') {
            $this->criarTarefaPadrao('Criar peça para o feed do Twitter');
        }
        if ($this->request->feed_youtube == 'sim') {
            $this->criarTarefaPadrao('Criar peça para o feed do YouTube');
        }
        if ($this->request->feed_tiktop == 'sim') {
            $this->criarTarefaPadrao(
                'Criar peça para o feed do TikTop',
                '<p>Criar peça para o feed do TikTopER ಠ_ಠ</p>'
            );
        }
        if ($this->request->digital_banner == 'sim') {
            $this->criarTarefaPadrao('Criar um banner para as redes sociais');
        }
    }
    private function criarDemandaImpresso()
    {
        if ($this->request->criacao_impresso != 'sim') {
            return;
        }

        if ($this->request->impresso_voucher == 'sim') {
            $this->criarTarefaPadrao('Criar peça impressa para um voucher');
        }
        if ($this->request->impresso_folder == 'sim') {
            $this->criarTarefaPadrao('Criar peça impressa para um folder');
        }
        if ($this->request->impresso_banner == 'sim') {
            $this->criarTarefaPadrao('Criar peça impresa para um banner');
        }
        if ($this->request->impresso_revista == 'sim') {
            $this->criarTarefaPadrao('Criar peça impresa para uma revista');
        }
        if ($this->request->impresso_outro == 'sim') {
            $this->criarTarefaPadrao(
                'Criar peça impressa em outro',
                strConverterTextareaEmParagrafo($this->request->impresso_outro_texto)
            );
        }
    }
    private function criarDemandaKit()
    {
        if ($this->request->criacao_kit != 'sim') {
            return;
        }

        if ($this->request->kit_email == 'sim') {
            $this->criarTarefaPadrao('Criar e-mail do Kit de boas-vindas');
        }
        if ($this->request->kit_stories == 'sim') {
            $this->criarTarefaPadrao('Criar stories do Kit de boas-vindas');
        }
        if ($this->request->kit_video == 'sim') {
            $this->criarTarefaPadrao('Criar vídeo do Kit de boas-vindas');
        }
        if ($this->request->kit_feed == 'sim') {
            $this->criarTarefaPadrao('Criar peças para feed do Kit de boas-vindas');
        }
        if ($this->request->kit_como_acessar == 'sim') {
            $this->criarTarefaPadrao('Criar ajuda de como acessar do Kit de boas-vindas');
        }
        if ($this->request->kit_baixar_app == 'sim') {
            $this->criarTarefaPadrao('Criar ajuda de como baixar os APPs do Kit de boas-vindas');
        }
        if ($this->request->kit_previa == 'sim') {
            $this->criarTarefaPadrao('Criar prévia do Kit de boas-vindas');
        }
    }
    private function criarDemandaVideo()
    {
        if ($this->request->criacao_video != 'sim') {
            return;
        }

        $formato = '';
        if ($this->request->video_formato == 'horizontal') {
            $formato = '<strong>16:9 Horizontal</strong>';
        } elseif ($this->request->video_formato == 'vertical') {
            $formato = '<strong>9:16 Vertical</strong>';
        } elseif ($this->request->video_formato == 'outro') {
            $formato = '<strong>' . $this->request->video_largura . '</strong>x<strong>'
                . $this->request->video_altura . '</strong>';
        }
        $this->criarTarefaPadrao(
            titulo: 'Criar vídeo',
            texto: '
                <p>Criar vídeo com o seguinte formato:</p>
                <p>' . $formato . '</p>
            '
        );
    }
    private function criarDemandaOutro()
    {
        if ($this->request->criacao_outro != 'sim') {
            return;
        }

        $this->criarTarefaPadrao(
            titulo: 'Criar outro tipo de criação',
            texto: strConverterTextareaEmParagrafo($this->request->outro_texto)
        );
    }

    private function criarTarefaPadrao(string $titulo, ?string $texto = null, ?int $tempo = null)
    {
        $this->salvarTarefa(
            tipo: 'criacao',
            titulo: $titulo,
            texto: !empty($texto) ? $texto : '<p>' . $titulo . '</p>',
            tempo: !empty($tempo) ? $tempo : 60
        );
    }

    private function notificarUsuario()
    {
        $Api = new ApiHelper(token: true);
        foreach ($this->listaNotificacao as $equipe) {
            $Api
                ->body([
                    'titulo' => 'Criou uma nova tarefa para você',
                    'mensagem' => 'Foi criado uma nova tarefa para você, acesse a demanda e verifique o pedido.',
                    'link' => LINK . '/demanda/criacao#demanda-' . $this->Demanda->dado->id,
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
