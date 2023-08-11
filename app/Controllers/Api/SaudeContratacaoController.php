<?php

namespace App\Controllers\Api;

use App\Models\Api\Saude\Contratacao\ContratacaoEntity;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use Controller\Controller;
use Erro\Excecao;
use Http\Request;
use Http\Response;
use System\Interface\ControllerSalvarInterface;

class SaudeContratacaoController extends Controller implements
    ControllerSalvarInterface
{
    /**
     * @param Request $request
     *
     * @return Response
     * @throws Excecao
     */
    public function postSalvar(Request $request): Response
    {
        $SimulacaoEntity = new SimulacaoEntity();
        $SimulacaoEntity->uuid(
            $request->getPost('id_simulacao'),
            mensagem: 'A contratação não foi possível devido a falta do codígo da simulação',
            titulo: 'Contratação inválidada'
        );

        $ContratacaoEntity = new ContratacaoEntity($SimulacaoEntity);
        $ContratacaoEntity->set(lista: $request->dado());
        $ContratacaoEntity->salvar();
        return mensagemSucesso(
            pegarPropriedadeDaEntity($ContratacaoEntity, lista: [
                'id_simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
                'data_nascimento', 'estado_civil', 'naturalidade', 'sexo', 'peso', 'altura',
                'filiacao', 'cpf_responsavel', 'rg_responsavel', 'nome_responsavel',
                'email', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
                'ramal', 'endereco', 'cep', 'estado', 'cidade', 'bairro', 'numero',
                'complemento', 'status'
            ]),
            201
        );
    }
}
