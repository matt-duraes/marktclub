<?php

namespace App\Controllers\Api\Saude;

use Http\Request;
use Http\Response;
use Controller\Controller;
use Modules\EnderecoEstado;
use App\Models\Api\Saude\Convenio\BuscarModel;
use App\Models\Api\Saude\Convenio\CidadeModel;
use App\Models\Api\Saude\Convenio\EstadoModel;
use App\Models\Api\Saude\Convenio\ListarModel;
use App\Models\Api\Saude\Convenio\TabelaModel;

final class ConvenioController extends Controller
{
    public function getListar(Request $request): Response
    {
        $Convenio = new ListarModel(
            EnderecoEstado: new EnderecoEstado($request->endereco_estado),
            enderecoCidade: $request->endereco_cidade
        );
        return mensagemSucesso($Convenio->retorno);
    }

    public function getBuscar(string $id): Response
    {
        $Buscar = new BuscarModel(id: $id);
        return mensagemSucesso($Buscar->retorno);
    }

    public function getEstado(): Response
    {
        $Estado = new EstadoModel();
        return mensagemSucesso($Estado->retorno);
    }

    public function getCidade(Request $request): Response
    {
        $Cidade = new CidadeModel(
            EnderecoEstado: new EnderecoEstado($request->endereco_estado),
        );
        return mensagemSucesso($Cidade->retorno);
    }

    public function getTabela(Request $request): Response
    {
        $Tabela = new TabelaModel(
            convenio: $request->convenio,
            tabela: $request->tabela
        );
        return mensagemSucesso($Tabela->retorno);
    }
}
