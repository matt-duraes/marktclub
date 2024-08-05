<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Modules\Data;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Geral\Status;
use App\Classes\ConstrutorClube\Ordem;
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
    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->uuid($id);
        return $this->retornoPadrao($Construtor);
    }

    /**
     * @param ConstrutorEntity $Construtor
     * @param int              $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(ConstrutorEntity $Construtor, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Construtor,
                lista: [
                    'empresa', 'titulo', 'logo_principal', 'logo_secundaria', 'favicon', 'logo_footer', 'header_tag',
                    'cor_principal', 'cor_secundaria', 'link_clube', 'link_login', 'link_cadastro',
                    'link_salavip', 'link_app_ios', 'link_app_android', 'contato_telefone', 'contato_whatsapp',
                    'contato_email', 'contato_horario', 'contato_endereco', 'menu_faq', 'menu_como_funciona',
                    'menu_sair', 'menu_acesso_rapido', 'menu_loja', 'menu_mapa', 'menu_cinema', 'menu_tema',
                    'menu_turismo', 'menu_ponto_mais_acao', 'menu_historico', 'menu_farmacia', 'menu_automovel',
                    'menu_saude_vitoria', 'menu_saude_amil', 'menu_saude_seguro', 'menu_saude_cnu',
                    'menu_saude_florianopolis', 'menu_cashback', 'menu_indicar_usuario', 'menu_indicar_loja',
                    'menu_odontologico', 'menu_premium', 'menu_dependente', 'menu_funcionario', 'menu_carteira',
                    'menu_cupom', 'menu_salavip', 'menu_credito_sicoob', 'menu_primeiro_acesso', 'chat_status',
                    'header_descricao', 'menu_corrida', 'menu_show_internacional', 'menu_show_nacional', 'menu_samsung',
                    'menu_meu_parceiro', 'administrado_status', 'api_status', 'tipo_ativacao', 'status',
                    'campos_primeiro_acesso', 'grupo_label', 'grupo_placeholder', 'tela_login', 'link_odontologico',
                    'link_funcionario', 'texto_login_usuario', 'texto_login_dependente', 'texto_login_funcionario'
                ]
            ),
            $status
        );
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Construtor = new ConstrutorModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->empresa,
            $request->pesquisa,
            $request->titulo_clube,
            new Data($request->data_inicio),
            new Data($request->data_final),
            new Status($request->status)
        );
        return mensagemSucesso($Construtor->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->set(lista: $request->dado());
        $Construtor->salvar();
        return $this->retornoPadrao($Construtor, 201);
    }

    /**
     * @param Request $request
     * @param string  $id
     *
     * @return Response
     * @throws Excecao
     */
    public function putAtualizar(Request $request, string $id): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->uuid($id);
        $Construtor->set(lista: $request->dado());
        $Construtor->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function deleteDeletar(string $id): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->uuid($id);
        $Construtor->destruir();
        return new Response(status: 204);
    }

    /**
     * @param string $url
     *
     * @return Response
     * @throws Excecao
     */
    public function getClube(string $url): Response
    {
        $Construtor = new ConstrutorEntity();
        $Construtor->buscar([
            ['link_clube', (new LinkClubeModel($url))->url],
            ['status', 1]
        ]);
        $Clube = new ClubeModel($Construtor);
        return mensagemSucesso($Clube->construtor);
    }
}
