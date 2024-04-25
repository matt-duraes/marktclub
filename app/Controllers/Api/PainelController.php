<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Models\Api\Painel\MenuModel;
use App\Classes\PainelConfiguracoes\Ordem;
use App\Models\Api\Painel\ConfiguracaoModel;
use App\Models\Api\Painel\ConfiguracaoEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class PainelController extends Controller implements
    ControllerBuscarInterface,
    ControllerListarInterface,
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
        $Configuracoes = new ConfiguracaoEntity();
        $Configuracoes->uuid($id);
        return $this->retornoPadrao($Configuracoes);
    }

    /**
     * @param ConfiguracaoEntity $configuracaoEntity
     * @param int                $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(ConfiguracaoEntity $configuracaoEntity, int $status = 200): Response
    {
        return mensagemSucesso(
            pegarPropriedadeDaEntity($configuracaoEntity, lista: [
                'empresa', 'titulo', 'permissao', 'configuracao', 'campo_obrigatorio',
                'campo_permitido', 'upload_grupo'
            ]),
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
        $ConfiguracaoModel = new ConfiguracaoModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            $request->empresa
        );
        return mensagemSucesso($ConfiguracaoModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Configuracoes = new ConfiguracaoEntity();
        $Configuracoes->set(lista: $request->dado());
        $Configuracoes->salvar();
        return $this->retornoPadrao($Configuracoes, 201);
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
        $Configuracoes = new ConfiguracaoEntity();
        $Configuracoes->uuid($id);
        $Configuracoes->set(lista: $request->dado());
        $Configuracoes->salvar();
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
        $Configuracoes = new ConfiguracaoEntity();
        $Configuracoes->uuid($id);
        $Configuracoes->destruir();
        return new Response(status: 204);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getPermissao(): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        $configuracoes = $Configuracao->pegarConfiguracoes();
        return mensagemSucesso($configuracoes->permissao);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getConfiguracao(): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        $configuracoes = $Configuracao->pegarConfiguracoes();
        return mensagemSucesso($configuracoes->configuracao);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getUploadGrupo(): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        $configuracoes = $Configuracao->pegarConfiguracoes();
        return mensagemSucesso($configuracoes->upload_grupo);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getMenu(): Response
    {
        $Menu = new MenuModel();
        return mensagemSucesso($Menu->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getCampoObrigatorio(Request $request): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        $configuracoes = $Configuracao->pegarConfiguracoes();
        $campo = $configuracoes->campo_obrigatorio;

        if (!empty($request->app)) {
            $campo = is_array($campo) && array_key_exists($request->app, $campo) ? $campo[$request->app] : [];
        }
        return mensagemSucesso($campo);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getCampoPermitido(Request $request): Response
    {
        $Configuracao = new ConfiguracaoEntity();
        $configuracoes = $Configuracao->pegarConfiguracoes();
        $campo = $configuracoes->campo_permitido;

        if (!empty($request->app)) {
            $campo = is_array($campo) && array_key_exists($request->app, $campo) ? $campo[$request->app] : [];
        }
        return mensagemSucesso($campo);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getChavePublica(): Response
    {
        return mensagemSucesso([
            'chave' => TOKEN['app']->chave_publica
        ]);
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getChavePrivada(): Response
    {
        return mensagemSucesso([
            'chave' => TOKEN['app']->chave_privada
        ]);
    }
}
