<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use App\Classes\ComercialEmpresa\Helper;
use System\Interface\ControllerBuscarInterface;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use System\Interface\ControllerSelectInterface;
use App\Models\Api\ComercialEmpresa\PerfilModel;
use App\Models\Api\ComercialEmpresa\EmpresaModel;
use App\Models\Api\ComercialEmpresa\RankingModel;
use App\Models\Api\ComercialEmpresa\EmpresaEntity;
use System\Interface\ControllerAtualizarInterface;

class ComercialEmpresaController extends Controller implements
    ControllerSelectInterface,
    ControllerBuscarInterface,
    ControllerListarInterface,
    ControllerSalvarInterface,
    ControllerAtualizarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function getSelect(Request $request): Response
    {
        $EmpresaModel = new EmpresaModel();
        return mensagemSucesso(
            $EmpresaModel->pegarSelect(
                'cod',
                'nome_fantasia',
                [
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
            'imagem', 'titulo', 'nome_fantasia', 'razao_social', 'slug', 'valor_pago',
            'cobrar_aposentado', 'site', 'gerente_contas', 'responsavel_nome',
            'responsavel_email', 'responsavel_telefone', 'responsavel_cpf',
            'responsavel_cargo', 'tipo_pagamento', 'contrato_valor', 'contrato_valor_minimo',
            'contrato_usuario_minimo', 'valor_pib', 'produto_clube', 'produto_ios',
            'produto_android', 'produto_site', 'produto_webview', 'produto_api',
            'cnpj', 'estado_principal', 'status', 'data_eleicao', 'email_dia',
            'whatsapp_dia', 'rede_social_dia', 'contrato_data', 'contrato_prazo',
            'contrato_renovacao', 'tipo_site', 'comunicacao_email', 'comunicacao_whatsapp',
            'comunicacao_rede_social', 'email_disparo', 'prospeccao_status', 'observacao_ti',
            'observacao_comunicacao', 'observacao_financeiro', 'restricao_lista',
            'contrato_dia_pagamento', 'cadastro_usuario', 'contrato_dia_fechamento',
            'renda_media', 'parceiro_proprio', 'concorrente_status', 'concorrente_nome',
            'origem', 'usuario_possivel', 'contato_preferencial', 'data_apresentacao',
            'formato_reuniao', 'previsao_retorno', 'motivo_standby', 'motivo_standby',
            'motivo_perdido', 'devolutiva', 'etapa_negociacao', 'indicado'
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
        $EmpresaModel = new EmpresaModel($request);
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
}
