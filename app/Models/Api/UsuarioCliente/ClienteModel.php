<?php

namespace App\Models\Api\UsuarioCliente;

use stdClass;
use Http\Request;
use Modules\Data;
use App\Models\Api\GeralModel;
use App\Classes\UsuarioCliente\Ordem;
use App\Classes\UsuarioCliente\Status;
use App\Models\Api\UsuarioCliente\Trait\BuscarUsuarioTrait;

final class ClienteModel extends GeralModel
{
    protected string $_tabela = TABELA_USUARIO_NOVO;

    use BuscarUsuarioTrait;

    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
        $this->validarCampoDoRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this->buscarUsuario([
            'cod', 'nome', 'documento', 'email_trabalho', 'email_pessoal', 'status', 'data_criacao', 'usuario_lead'
        ], true);

        $dado->lista = $this->montarRetornoLista($dado->lista);
        return $dado;
    }

    private function montarRetornoLista($dado)
    {
        if (!$dado) {
            return [];
        }

        $lista = [];
        foreach ($dado as $r) {
            $email = null;
            if (!empty($r->email_pessoal)) {
                $email = $r->email_pessoal;
            } else if (!empty($r->email_trabalho)) {
                $email = $r->email_trabalho;
            }
            $lista[] = [
                'id' => $r->cod,
                'nome' => $r->nome,
                'cpf' => $r->documento,
                'email' => $email,
                'data_criacao' => $r->data_criacao,
                'status' => (new Status($r->status))->indice(),
            ];
        }
        return $lista;
    }

    private function validarCampoDoRequest()
    {
        if (is_null($this->request)) {
            return;
        }

        $ordem = new Ordem($this->request->ordem);
        $status = new Status($this->request->status);
        $dataUpload = new Data($this->request->data_upload);
        $dataCriacaoDe = new Data($this->request->data_criacao_de);
        $dataCriacaoAte = new Data($this->request->data_criacao_ate);
        $status = new Status($this->request->status);

        if (!empty($this->request->pagina) && !preg_match('/^[1-9]{1}[0-9]*$/', $this->request->pagina)) {
            mensagemErro('Campo inválido!', 'A página deve ser um número inteiro.');
        } else if (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é um valor válido.');
        } else if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é um valor válido.');
        } else if (!$dataUpload->vazio() && !validarDate($dataUpload)) {
            mensagemErro('Campo inválido!', 'A data de upload informado não é um valor válido.');
        } else if (!$dataCriacaoDe->vazio() && !validarDate($dataCriacaoDe)) {
            mensagemErro('Campo inválido!', 'A data de criação do começo informado não é um valor válido.');
        } else if (!$dataCriacaoAte->vazio() && !validarDate($dataCriacaoAte)) {
            mensagemErro('Campo inválido!', 'A data de criação final informado não é um valor válido.');
        } else if (!$status->vazio() && (!$status->valido() || $status->indice() == 'deletado')) {
            mensagemErro('Campo inválido!', 'O Status não é um valor válido.');
        }
    }
}
