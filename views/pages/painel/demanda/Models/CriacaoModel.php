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
    private array $listaNotificacao = ['3df1a38ec0919bd14162beabb73e12b4', 'b9f14339-ae96-4524-8697-737133b3360e'];
    private string $empresa;

    public function __construct(
        private Request $request
    ) {
        $this->empresa = $request->empresa;
        $this->criarDemanda($request->empresaNome . $request->titulo, $request->texto, Tipo::CRIACAO, Area::CRIACAO, $request->data_entrega);
        $this->verificarSeSalvouDemanda();
        $this->criarDemandaSite();
        $this->criarDemandaRedeSocial();
        $this->criarDemandaImpresso();
        $this->criarDemandaKit();
        $this->criarDemandaVideo();
        $this->criarDemandaOutro();
        $this->notificarUsuario();
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
                <hr>
                ' . $this->request->getPost('site_texto', html: false) . '
            '
        );
    }

    private function criarDemandaRedeSocial()
    {
        if ($this->request->criacao_social != 'sim') {
            return;
        }
        $texto = '';
        if ($this->request->digital_stories == 'sim') {
            $texto .= '<li>Criar peça para stories</li>';
        }
        $feed = '';
        if ($this->request->feed_whatsapp == 'sim') {
            $feed .= '<li>Criar peça para o feed do WhatsApp</li>';
        }
        if ($this->request->feed_instagram == 'sim') {
            $feed .= '<li>Criar peça para o feed do Instagram</li>';
        }
        if ($this->request->feed_facebook == 'sim') {
            $feed .= '<li>Criar peça para o feed do Facebook</li>';
        }
        if ($this->request->feed_linkedin == 'sim') {
            $feed .= '<li>Criar peça para o feed do LinkedIn</li>';
        }
        if ($this->request->feed_twitter == 'sim') {
            $feed .= '<li>Criar peça para o feed do Twitter</li>';
        }
        if ($this->request->feed_youtube == 'sim') {
            $feed .= '<li>Criar peça para o feed do YouTube</li>';
        }
        if ($this->request->feed_tiktop == 'sim') {
            $feed .= '<li>Criar peça para o feed do TikTop</li>';
        }
        if (!empty($feed)) {
            $texto .= '<li>Criar peça para os Feeds:<ul>' . $feed . '</ul></li>';
        }
        if ($this->request->digital_banner == 'sim') {
            $texto .= '<li>Criar um banner para as redes sociais</li>';
        }
        $this->criarTarefaPadrao(
            'Criar peças para rede social',
            '<ol>' . $texto . '</ol><hr>' . $this->request->getPost('digital_texto', html: false)
        );
    }

    private function criarDemandaImpresso()
    {
        if ($this->request->criacao_impresso != 'sim') {
            return;
        }

        $texto = '';
        if ($this->request->impresso_voucher == 'sim') {
            $texto .= '<li>Criar peça impressa para um voucher</li>';
        }
        if ($this->request->impresso_folder == 'sim') {
            $texto .= '<li>Criar peça impressa para um folder</li>';
        }
        if ($this->request->impresso_banner == 'sim') {
            $texto .= '<li>Criar peça impresa para um banner</li>';
        }
        if ($this->request->impresso_revista == 'sim') {
            $texto .= '<li>Criar peça impresa para uma revista</li>';
        }
        if ($this->request->impresso_outro == 'sim') {
            $texto .= '<li>Criar peça impressa em outro</li>';
        }
        $this->criarTarefaPadrao(
            'Criar peças para impressão',
            '<ol>' . $texto . '</ol><hr>' . $this->request->getPost('impresso_texto', html: false)
        );
    }

    private function criarDemandaKit()
    {
        if ($this->request->criacao_kit != 'sim') {
            return;
        }

        $texto = '';
        if ($this->request->kit_email == 'sim') {
            $texto .= '<li>Criar e-mail do Kit de boas-vindas</li>';
        }
        if ($this->request->kit_stories == 'sim') {
            $texto .= '<li>Criar stories do Kit de boas-vindas</li>';
        }
        if ($this->request->kit_video == 'sim') {
            $texto .= '<li>Criar vídeo do Kit de boas-vindas</li>';
        }
        if ($this->request->kit_feed == 'sim') {
            $texto .= '<li>Criar peças para feed do Kit de boas-vindas</li>';
        }
        if ($this->request->kit_como_acessar == 'sim') {
            $texto .= '<li>Criar ajuda de como acessar do Kit de boas-vindas</li>';
        }
        if ($this->request->kit_baixar_app == 'sim') {
            $texto .= '<li>Criar ajuda de como baixar os APPs do Kit de boas-vindas</li>';
        }
        if ($this->request->kit_previa == 'sim') {
            $texto .= '<li>Criar prévia do Kit de boas-vindas</li>';
        }
        $this->criarTarefaPadrao(
            'Criar peças do kit de bem-vindos',
            '<ol>' . $texto . '</ol><hr>' . $this->request->getPost('kit_texto', html: false)
        );
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
            titulo: 'Criar peça para vídeo',
            texto: '
                <p>Criar vídeo com o seguinte formato:</p>
                <p>' . $formato . '</p>
                <hr>
                ' . $this->request->getPost('video_texto', html: false) . '
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
            texto: $this->request->getPost('outro_texto', html: false)
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
                    'titulo'   => 'Criou uma nova tarefa para você',
                    'mensagem' => 'Foi criado uma nova tarefa para você, acesse a demanda e verifique o pedido.',
                    'link'     => LINK . '/demanda/criacao#demanda-' . $this->Demanda->dado->id,
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
