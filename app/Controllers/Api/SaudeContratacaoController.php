<?php

namespace App\Controllers\Api;

use Erro\Excecao;
use Http\Request;
use Http\Response;
use Controller\Controller;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\Saude\Simulacao\SimulacaoEntity;
use App\Models\Api\Saude\Contratacao\ContratacaoEntity;

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
                'data_nascimento', 'estado_civil', 'naturalidade', 'genero', 'peso', 'altura',
                'nome_mae', 'responsavel_cpf', 'responsavel_rg', 'responsavel_nome', 'responsavel_orgao_expedidor',
                'email', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
                'telefone_comercial_ramal', 'endereco_logradouro', 'endereco_cep', 'endereco_estado',
                'endereco_cidade', 'endereco_bairro', 'endereco_numero', 'endereco_complemento', 'status'
            ]),
            201
        );
    }
}
