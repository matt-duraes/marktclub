<?php

namespace Painel\Demanda\Models;

use stdClass;
use Helpers\ApiHelper;

final class CriarBugModel
{
    use DemandaTrait;
    use TarefaTrait;

    private stdClass $Demanda;

    public function __construct(
        private string $titulo,
        private string $empresa,
        private string $texto,
        private string $critico,
        private string $local
    ) {
        if (empty($empresa)) {
            $this->empresa = '14afa776394ada4be23be6acf7e3259e';
        }

        $this->criarDemanda($titulo, 'bug-' . $local, 'ti');
        $this->verificarSeSalvouDemanda();
        $this->salvarTarefa('nao-definido', $titulo, $texto);
        $this->notificarUsuario();
    }

    private function notificarUsuario()
    {
        if ('sim' != $this->critico) {
            return;
        }
        (new ApiHelper(token: true))
            ->body([
                'titulo' => 'Criou uma uma demanda de bug crítico',
                'mensagem' => 'Foi criado uma nova demanda de bug crítico, acesse o painel e verifique o pedido para verificar a urgência.',
                'link' => LINK . '/demanda#demanda-' . $this->Demanda->dado->id,
                'botao' => 'Acessar painel',
                'dono' => sessao('USUARIO.id'),
                'equipe' => '8fd85f9f7cc21d6e33399681d6e5fca7'
            ])
            ->post('/painel-notificacao');
    }

    public function id()
    {
        return $this->Demanda->dado->id;
    }
}
