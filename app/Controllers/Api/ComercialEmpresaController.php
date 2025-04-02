<?php

namespace App\Controllers\Api;

use App\Classes\ComercialEmpresa\Helper;
use App\Classes\ComercialEmpresa\Ordem;
use App\Classes\ComercialEmpresa\ProspeccaoStatus;
use App\Classes\ComercialEmpresa\Status;
use App\Models\Api\ComercialEmpresa\DownloadModel;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use App\Models\Api\ComercialEmpresa\EmpresaModel;
use App\Models\Api\ComercialEmpresa\PerfilModel;
use App\Models\Api\ComercialEmpresa\RankingModel;
use App\Models\Api\DownloadPrivado\ArquivoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use Modules\Botao;
use Modules\Cnpj;
use Modules\Data;
use Modules\Pagina;
use Modules\Quantidade;
use System\Interface\ControllerAtualizarInterface;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerSelectInterface;

class ComercialEmpresaController extends Controller implements
    ControllerSelectInterface,
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    /**
     * Lista todas as Empresas com seus respectivos nome fantasia
     *
     * @param Request $request Pode vir um titulo para emcabeçar o resultado
     *
     * @return Response Um array contendo todas as Empresas "uuid" => "nome fantasia"
     * @throws Excecao
     */
    public function getSelect(Request $request): Response
    {
        $EmpresaModel = new EmpresaModel();
        return mensagemSucesso(
            $EmpresaModel->pegarSelect(
                indice: 'cod',
                valor: 'nome_fantasia',
                where: [
                    ['status', 'in', Helper::STATUS_LIBERADO],
                    ['id_admin_empresa', 'null']
                ],
                titulo: $request->titulo
            )
        );
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getBuscar(string $id): Response
    {
        $EmpresaEntity = new EmpresaEntity();
        $EmpresaEntity->uuid($id);
        return $this->retornoPadrao($EmpresaEntity);
    }

    /**
     * @param EmpresaEntity $empresaEntity
     * @param int           $status
     *
     * @return Response
     * @throws Excecao
     */
    private function retornoPadrao(EmpresaEntity $empresaEntity, int $status = 200): Response
    {
        return mensagemSucesso(pegarPropriedadeDaEntity($empresaEntity, lista: [
            'equipe', 'dono', 'finalidade_principal', 'finalidade_secundaria',
            'imagem', 'titulo', 'nome_fantasia', 'razao_social', 'slug',
            'valor_pago', 'cobrar_aposentado', 'site', 'gerente_contas',
            'responsavel_nome', 'responsavel_email', 'responsavel_telefone',
            'responsavel_cpf', 'responsavel_cargo', 'tipo_pagamento',
            'contrato_valor', 'contrato_valor_minimo', 'contrato_usuario_minimo',
            'valor_pib', 'produto_clube', 'produto_ios', 'produto_android',
            'produto_site', 'produto_webview', 'produto_api', 'cnpj',
            'estado_principal', 'status', 'data_eleicao', 'email_dia',
            'whatsapp_dia', 'rede_social_dia', 'contrato_data', 'contrato_prazo',
            'contrato_renovacao', 'tipo_site', 'comunicacao_email', 'comunicacao_whatsapp',
            'comunicacao_rede_social', 'email_disparo', 'prospeccao_status',
            'observacao_ti', 'observacao_comunicacao', 'observacao_financeiro',
            'restricao_lista', 'contrato_dia_pagamento', 'cadastro_usuario',
            'contrato_dia_fechamento', 'renda_media', 'parceiro_proprio',
            'concorrente_status', 'concorrente_nome', 'origem', 'usuario_possivel',
            'contato_preferencial', 'data_apresentacao', 'formato_reuniao',
            'previsao_retorno', 'motivo_standby', 'motivo_standby',
            'motivo_perdido', 'devolutiva', 'etapa_negociacao', 'indicado',
            'equipe_nome'
        ]), $status, Helper::CRIPTOGRAFAR);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getListar(Request $request): Response
    {
        $EmpresaModel = new EmpresaModel(
            new Pagina($request->pagina),
            new Quantidade($request->quantidade),
            new Ordem($request->ordem),
            new Cnpj($request->cnpj),
            $request->pesquisa,
            $request->titulo,
            $request->empresa,
            $request->subempresa,
            $request->usuario,
            $request->dono,
            new Botao($request->sem_responsavel),
            new ProspeccaoStatus($request->prospeccao_status),
            new Status($request->status),
            new Data($request->data_inicio),
            new Data($request->data_final)
        );
        return mensagemSucesso($EmpresaModel->listarDados(), criptografar: Helper::CRIPTOGRAFAR);
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $EmpresaEntity = new EmpresaEntity();
        $EmpresaEntity->set(lista: $this->pegarDadoRequest($request, 'getPost'));
        $EmpresaEntity->salvar();
        return $this->retornoPadrao($EmpresaEntity, 201);
    }

    /**
     * @param Request $request
     * @param string  $metodo
     *
     * @return array
     */
    private function pegarDadoRequest(Request $request, string $metodo): array
    {
        $dado = $request->dado();
        if (array_key_exists('observacao_ti', $dado)) {
            $dado['observacao_ti'] = $request->$metodo('observacao_ti', html: false);
        }
        if (array_key_exists('observacao_comunicacao', $dado)) {
            $dado['observacao_comunicacao'] = $request->$metodo('observacao_comunicacao', html: false);
        }
        if (array_key_exists('observacao_financeiro', $dado)) {
            $dado['observacao_financeiro'] = $request->$metodo('observacao_financeiro', html: false);
        }
        return $dado;
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
        $EmpresaEntity = new EmpresaEntity();
        $EmpresaEntity->uuid($id);
        $EmpresaEntity->set(lista: $this->pegarDadoRequest($request, 'getPut'));
        $EmpresaEntity->salvar();
        return new Response(status: 204);
    }

    /**
     * @param string $id
     *
     * @return Response
     * @throws Excecao
     */
    public function getSlug(string $id): Response
    {
        $EmpresaEntity = new EmpresaEntity();
        $EmpresaEntity->uuid($id);
        return mensagemSucesso(pegarPropriedadeDaEntity($EmpresaEntity, lista: ['slug']));
    }

    /**
     * @return Response
     * @throws Excecao
     */
    public function getPerfil(): Response
    {
        $PerfilModel = new PerfilModel();
        return mensagemSucesso($PerfilModel->listarDados());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getRanking(Request $request): Response
    {
        $RankingModel = new RankingModel();
        return mensagemSucesso($RankingModel->gerarRanking());
    }

    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postDownload(Request $request): Response
    {
        $DownloadModel = new DownloadModel(
            $request->campo,
            $request->usuario,
            new Ordem($request->ordem),
            new Cnpj($request->cnpj),
            $request->pesquisa,
            $request->titulo,
            $request->empresa,
            $request->subempresa,
            $request->dono,
            new Botao($request->sem_responsavel),
            new ProspeccaoStatus($request->prospeccao_status),
            new Status($request->status),
            new Data($request->data_inicio),
            new Data($request->data_final)
        );
        $ArquivoEntity = new ArquivoEntity($DownloadModel->download(), $request->usuario);
        $ArquivoEntity->salvar();
        return mensagemSucesso([
            'id' => $ArquivoEntity->id
        ], 201);
    }
}
