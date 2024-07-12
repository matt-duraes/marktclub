<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Helpers\ApiHelper;
use App\Classes\DemandaDado\Area;

final class CriarBugModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private string $empresaNome,
        private string $titulo,
        private string $empresa,
        private string $critico,
        private string $local,
        string $texto
    ) {
        if (empty($empresa)) {
            $this->empresa = '14afa776394ada4be23be6acf7e3259e';
        }

        $this->criarDemanda($empresaNome . $titulo, $texto, 'bug-' . $local, Area::TECNOLOGIA);
        $this->verificarSeSalvouDemanda();
        $this->notificarUsuario();
    }

    private function notificarUsuario()
    {
        if ('sim' != $this->critico) {
            return;
        }
        (new ApiHelper(token: true))
            ->body([
                'titulo'   => 'Criou uma uma demanda de bug crítico',
                'mensagem' => 'Foi criado uma nova demanda de bug crítico, acesse o painel e verifique o pedido para verificar a urgência.',
                'link'     => LINK . '/demanda/tecnologia#demanda-' . $this->Demanda->dado->id,
                'botao'    => 'Acessar painel',
                'dono'     => sessao('USUARIO.id'),
                'equipe'   => '8fd85f9f7cc21d6e33399681d6e5fca7'
            ])
            ->post('/painel-notificacao');
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
