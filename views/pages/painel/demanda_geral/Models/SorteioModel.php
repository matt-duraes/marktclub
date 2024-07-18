<?php

namespace Painel\DemandaGeral\Models;

use stdClass;
use Http\Request;
use Helpers\ApiHelper;
use App\Classes\DemandaDado\Area;
use App\Classes\DemandaDado\Tipo;

final class SorteioModel
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
        $this->criarDemanda($request->empresaNome . $request->titulo, '', Tipo::SORTEIO, Area::CRIACAO);
        $this->verificarSeSalvouDemanda();
        $this->salvarSorteio();
        $this->notificarUsuario();
    }

    private function salvarSorteio()
    {
        $request = $this->request;
        $motivacao = $request->sorteio_motivacao == 'outro'
            ? $request->sorteio_motivacao_outro : $request->sorteio_motivacao;
        $entrega = $request->sorteio_premio_entrega == 'outro'
            ? $request->sorteio_premio_entrega_outro : $request->sorteio_premio_entrega;
        $texto = $this->request->getPost('texto', html: false);
        $this->salvarTarefa(
            tipo: Tipo::CRIACAO,
            titulo: 'Criar peça para sorteio',
            texto: <<<HTML
                <p><strong>DATAS DO SORTEIO</strong></p>
                <p>Data de início do sorteio: <strong>$request->sorteio_inicio</strong></p>
                <p>Data final do sorteio: <strong>$request->sorteio_final</strong></p>
                <p>Data do sorteio: <strong>$request->sorteio_data</strong></p>
                <p><strong>COMO O SORTEIO IRÁ FUNCIONAR</strong></p>
                <p>Como participar do sorteio: <strong>$request->sorteio_como_participar</strong></p>
                <p>Motivação do sorteio: <strong>$motivacao</strong></p>
                <p><strong>DADOS DO PRÉMIO</strong></p>
                <p>Qual será o prémio: <strong>$request->sorteio_premio</strong></p>
                <p>Quem irá comprar o prémio: <strong>$request->sorteio_premio_compra</strong></p>
                <p>Onde será entregue: <strong>$entrega</strong></p>
                <hr>
                <p><strong>Outros dados:</strong></p>
                $texto
            HTML,
            tempo: 120
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
