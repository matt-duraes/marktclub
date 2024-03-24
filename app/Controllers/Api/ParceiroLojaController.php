<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\ParceiroLoja\Tipo;
use App\Classes\ParceiroLoja\Categoria;
use App\Models\Api\ParceiroLoja\LojaModel;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\ParceiroLoja\SelectModel;
use App\Models\Api\ParceiroLoja\DestaqueModel;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSelectInterface;
use App\Models\Api\ParceiroLoja\RelacionadoModel;

final class ParceiroLojaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSelectInterface
{
    public function getSelect(Request $request): Response
    {
        $Parceiro = new SelectModel(
            titulo: $request->titulo,
            tipo: new Tipo($request->tipo)
        );
        return mensagemSucesso($Parceiro->listarDados());
    }

    public function getBuscar(string $id): Response
    {
        $Parceiro = new LojaEntity();
        $Parceiro->idSlug($id);
        return mensagemSucesso(
            pegarPropriedadeDaEntity(
                $Parceiro,
                lista: [
                    'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj',
                    'titulo_interno', 'tipo_loja', 'equipe', 'responsavel_nome', 'responsavel_cpf', 'responsavel_email',
                    'imagem_logo', 'imagem_capa_desktop', 'imagem_capa_desktop', 'titulo', 'tipo_estabelecimento', 'origem_lead',
                    'url', 'delivery', 'convenio_direto', 'data_contrato_inicio', 'data_contrato_vencimento', 'precisa_aditivo',
                    'email_contato', 'tipo_procedimento', 'limite_voucher', 'prazo_voucher', 'prazo_voucher_fixo',
                    'contato_whatsapp', 'link_site', 'link_alias', 'link_bloqueado', 'texto_descricao', 'texto_desconto',
                    'texto_procedimento', 'texto_voucher', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
                    'subcategoria_lista', 'empresa', 'destaque', 'endereco_estado', 'pontuacao',
                ]
            )
        );
    }

    public function getListar(Request $request): Response
    {
        $Parceiro = new LojaModel();
        $Parceiro->set(lista: $request->dado());
        return mensagemSucesso($Parceiro->listarDados());
    }

    public function getDestaque(Request $request)
    {
        $Parceiro = new DestaqueModel(
            categoria: new Categoria($request->categoria),
            subcategoria: $request->subcategoria,
            quantidade: new Quantidade($request->quantidade)
        );
        return mensagemSucesso($Parceiro->listarDados());
    }

    public function getRelacionado(string $id)
    {
        $Parceiro = new RelacionadoModel(id: $id);
        return mensagemSucesso($Parceiro->listarDados());
    }
}
