<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Models\Api\SiteConfig\ConfigModel;
use App\Models\Api\SiteConfig\ConfigEntity;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerAtualizarInterface;

final class SiteConfigController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    public function getListar(Request $request): Response
    {
        $Config = new ConfigModel();
        $Config->set(lista: $request->dado());

        return mensagemSucesso($Config->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Config = new ConfigEntity();
        $Config->idSlug($id, 'link_site');

        return $this->retornoPadrao($Config);
    }

    public function postSalvar(Request $request): Response
    {
        $Config = new ConfigEntity();
        $Config->set(lista: $request->dado());
        $Config->salvar();

        return $this->retornoPadrao($Config, 201);
    }

    private function retornoPadrao(ConfigEntity $Config, int $status = 200)
    {
        return mensagemSucesso(
            dado: pegarPropriedadeDaEntity(
                Entity: $Config,
                lista: [
                    'titulo', 'titulo_painel', 'descricao', 'contato_telefone', 'contato_celular',
                    'contato_whatsapp', 'contato_email', 'contato_endereco', 'mapa_imagem', 'mapa_link',
                    'cor_principal', 'rede_youtube', 'rede_facebook', 'rede_instagram', 'rede_twitter_x',
                    'logo_principal', 'favicon', 'link_site', 'template_header', 'template_footer', 'contato_chat',
                    'cor_texto', 'cor_header', 'cor_footer', 'rede_header', 'rede_footer', 'rss', 'login_texto',
                    'login_link', 'clube_link', 'home_banner', 'home_noticia_principal', 'home_noticia_lista',
                    'home_parceiro', 'home_video', 'home_galeria', 'noticia_imagem', 'imagem_social',
                    'diretoria_tipo', 'status'
                ]
            ),
            status: $status
        );
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        $Config = new ConfigEntity();
        $Config->uuid($id);
        $Config->set(lista: $request->dado());
        $Config->salvar();

        return new Response(status: 204);
    }

    public function deleteDeletar(string $id): Response
    {
        $Config = new ConfigEntity();
        $Config->uuid($id);
        $Config->destruir();

        return new Response(status: 204);
    }
}
