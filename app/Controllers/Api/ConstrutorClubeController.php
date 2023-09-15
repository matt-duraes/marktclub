<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Models\Api\ConstrutorClube\ClubeModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use App\Models\Api\ConstrutorClube\LinkClubeModel;
use System\Interface\ControllerAtualizarInterface;
use App\Models\Api\ConstrutorClube\ConstrutorModel;
use App\Models\Api\ConstrutorClube\ConstrutorEntity;

final class ConstrutorClubeController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getClube(string $url)
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_clube', (new LinkClubeModel($url))->url],
            ['status', 1]
        ]);
        $Clube = new ClubeModel($Construtor);
        return mensagemSucesso($Clube->construtor);
    }

    public function getListar(Request $request): Response
    {
        $Construtor = new ConstrutorModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            pesquisa: $request->pesquisa,
            status: new Status($request->status),
        );

        return mensagemSucesso($Construtor->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->uuid($id);

        return $this->retornoPadrao($Construtor);
    }

    public function postSalvar(Request $request): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->set(lista: $request->dado());
        $Construtor->salvar();

        return $this->retornoPadrao($Construtor, 201);
    }

    private function retornoPadrao(ConstrutorEntity $Construtor, int $status = 200)
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Construtor,
                lista: [
                    'empresa', 'titulo', 'logo_principal', 'logo_secundaria', 'favicon', 'header_tag',
                    'cor_principal', 'cor_secundaria', 'link_clube', 'link_login', 'link_cadastro',
                    'link_salavip', 'link_app_ios', 'link_app_android', 'contato_telefone', 'contato_whatsapp',
                    'contato_email', 'contato_horario', 'contato_endereco', 'menu_faq', 'menu_como_funciona',
                    'menu_sair', 'menu_acesso_rapido', 'menu_loja', 'menu_mapa', 'menu_cinema', 'menu_tema',
                    'menu_turismo', 'menu_historico', 'menu_farmacia', 'menu_automovel', 'link_odontologico',
                    'menu_saude_vitoria', 'menu_saude_amil', 'menu_saude_seguro', 'menu_saude_cnu',
                    'menu_saude_florianopolis', 'menu_cashback', 'menu_indicar_usuario', 'menu_indicar_loja',
                    'menu_odontologico', 'menu_premium', 'menu_dependente', 'menu_carteira', 'menu_cupom',
                    'menu_salavip', 'menu_credito_sicoob', 'menu_primeiro_acesso', 'chat_status', 'header_descricao',
                    'menu_meu_parceiro', 'administrado_status', 'api_status', 'tipo_ativacao', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->uuid($id);
        $Construtor->set(lista: $request->dado());
        $Construtor->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->uuid($id);
        $Construtor->destruir();

        return new Response(status: 204);
    }
}
