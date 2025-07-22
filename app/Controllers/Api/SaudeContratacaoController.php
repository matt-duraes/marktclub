<?php

namespace App\Controllers\Api;

use Http\Request;
use Http\Response;
use Modules\Pagina;
use Modules\Quantidade;
use Controller\Controller;
use App\Classes\Saude\Ordem;
use App\Classes\Saude\Status;
use System\Interface\ControllerListarInterface;
use System\Interface\ControllerSalvarInterface;
use App\Models\Api\Saude\Simulacao\ContratarModel;
use App\Models\Api\Saude\Contratacao\ContratacaoModel;
use App\Models\Api\Saude\Contratacao\ContratacaoEntity;

class SaudeContratacaoController extends Controller implements
    ControllerSalvarInterface,
    ControllerListarInterface
{
    public function getListar(Request $request): Response
    {
        $Contratacao = new ContratacaoModel(
            pagina: new Pagina($request->pagina),
            quantidade: new Quantidade($request->quantidade),
            ordem: new Ordem($request->ordem),
            pesquisa: $request->pesquisa,
            status: new Status($request->status)
        );
        return mensagemSucesso($Contratacao->listarDados(), 200);
    }

    public function getBuscar(Request $request, string $id): Response
    {
        $Contratacao = new ContratacaoEntity();
        $Contratacao->uuid($id);
        return $this->retornoPadrao($Contratacao);
    }

    public function postSalvar(Request $request): Response
    {
        $ContratacaoEntity = new ContratacaoEntity(
            Simulacao: new ContratarModel(uuid: $request->getPost('id_saude_simulacao'))
        );
        $ContratacaoEntity->set(lista: $request->dado());
        $ContratacaoEntity->salvar();

        return $this->retornoPadrao($ContratacaoEntity, 201);
    }

    private function retornoPadrao(ContratacaoEntity $ContratacaoEntity, int $status = 200): Response
    {
        $Contratacao = pegarPropriedadeDaEntity($ContratacaoEntity, lista: [
            'usuario', 'empresa', 'simulacao', 'documento_cpf', 'documento_rg', 'orgao_expedidor', 'nome',
            'data_nascimento', 'estado_civil', 'naturalidade', 'genero', 'peso', 'altura',
            'nome_mae', 'responsavel_cpf', 'responsavel_rg', 'responsavel_nome', 'responsavel_orgao_expedidor',
            'email_pessoal', 'telefone_celular', 'telefone_residencial', 'telefone_comercial',
            'telefone_comercial_ramal', 'endereco_logradouro', 'endereco_cep', 'endereco_estado',
            'endereco_cidade', 'endereco_bairro', 'endereco_numero', 'endereco_complemento', 'status'
        ]);
        return mensagemSucesso($Contratacao, $status);
    }

    public function putAtualizar(Request $request, string $id): Response
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao tentar atualizar o item', status: 500);
        // $ContratacaoEntity = new ContratacaoEntity();
        // $ContratacaoEntity->uuid($id);
        // $ContratacaoEntity->set(lista: $request->dado());
        // $ContratacaoEntity->salvar();
        return new Response(status: 204);
    }
}
