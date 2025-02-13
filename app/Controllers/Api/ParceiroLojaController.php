<?php

namespace App\Controllers\Api;

use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\Ordem;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Models\Api\ParceiroLoja\Auditoria\OptionModel;
use App\Models\Api\ParceiroLoja\Auditoria\SalvarModel;
use App\Models\Api\ParceiroLoja\DestaqueModel;
use App\Models\Api\ParceiroLoja\DownloadModel;
use App\Models\Api\ParceiroLoja\LojaEntity;
use App\Models\Api\ParceiroLoja\LojaModel;
use App\Models\Api\ParceiroLoja\RelacionadoModel;
use App\Models\Api\ParceiroLoja\SelectModel;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerDeletarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerSelectInterface;

final class ParceiroLojaController extends Controller implements
    ControllerListarInterface,
    ControllerBuscarInterface,
    ControllerSelectInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface,
    ControllerDeletarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSelect(Request $request): Response
    {
        $Parceiro = new SelectModel($request->titulo, new TipoLoja($request->tipo));
        return mensagemSucesso($Parceiro->listarDados());
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $Parceiro = new LojaEntity();
        $Parceiro->idSlug($id);
        return $this->retornoPadrao($Parceiro);
    }

    /**
     * @param LojaEntity $Parceiro
     * @param int        $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(LojaEntity $Parceiro, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($Parceiro, lista: [
            'nome_fantasia', 'razao_social', 'tipo_juridico', 'documento_cpf', 'documento_cnpj',
            'titulo_interno', 'tipo_loja', 'equipe', 'responsavel_nome', 'responsavel_cpf',
            'responsavel_email', 'responsavel_telefone', 'responsavel_cargo', 'imagem_logo',
            'imagem_capa_desktop', 'imagem_capa_mobile', 'titulo', 'tipo_estabelecimento', 'origem_lead',
            'url', 'delivery', 'convenio_direto', 'data_contrato_inicio', 'data_contrato_vencimento',
            'precisa_aditivo', 'data_auditoria', 'email_contato', 'tipo_procedimento', 'limite_voucher',
            'prazo_voucher', 'prazo_voucher_fixo', 'contato_whatsapp', 'link_site', 'link_alias',
            'link_bloqueado', 'texto_descricao', 'texto_desconto', 'texto_procedimento', 'texto_voucher',
            'texto_restricao', 'texto_outro', 'categoria_principal', 'categoria_lista', 'subcategoria_tag',
            'comissao_minima', 'subcategoria_lista', 'comissao_maxima', 'empresa', 'destaque',
            'confirmar_status', 'confirmar_titulo', 'confirmar_texto', 'endereco_estado', 'pontuacao',
            'desconto', 'arquivo_clube', 'arquivo_painel', 'cupom_desconto', 'existe_endereco',
            'existe_email', 'existe_telefone', 'cancelar_motivo', 'status', 'prazo_declaracao'
        ]), $status);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $Parceiro = new LojaModel();
        $Parceiro->set(lista: $request->dado());
        return mensagemSucesso($Parceiro->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $Parceiro = new LojaEntity();
        $Parceiro->set(lista: $request->dado());
        $Parceiro->salvar();
        return $this->retornoPadrao($Parceiro, 201);
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
        $Loja = new LojaEntity();
        $Loja->uuid($id);
        $Loja->set(lista: $request->dado());
        $Loja->salvar();
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
        $Loja = new LojaEntity();
        $Loja->uuid($id);
        $Loja->destruir();
        return new Response(status: 204);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getDestaque(Request $request): Response
    {
        $Parceiro = new DestaqueModel(
            new Quantidade($request->quantidade),
            new Categoria($request->categoria),
            $request->subcategoria,
            new Ordem($request->ordem)
        );
        return mensagemSucesso($Parceiro->listarDados());
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getRelacionado(string $id): Response
    {
        $Parceiro = new RelacionadoModel(id: $id);
        return mensagemSucesso($Parceiro->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postAuditoria(Request $request): Response
    {
        $Auditoria = new SalvarModel(
            new OptionModel(
                $request->parceiro,
                $request->auditoria,
                $request->mensagem
            )
        );
        return mensagemSucesso([
            'id'       => $Auditoria->Historico->id,
            'mensagem' => $Auditoria->Historico->mensagem,
            'status'   => $Auditoria->Parceiro->status->indice()
        ], 201);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDownload(Request $request): Response
    {
        $Download = new DownloadModel($request);
        return mensagemSucesso([
            'id' => $Download->id
        ], 201);
    }
}
