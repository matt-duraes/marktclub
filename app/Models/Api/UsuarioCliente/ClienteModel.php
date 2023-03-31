<?php

namespace App\Models\Api\UsuarioCliente;

use ORM\ORM;
use stdClass;
use Http\Request;
use Modules\Data;
use App\Classes\UsuarioCliente\Ordem;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use App\Models\Api\UsuarioCliente\Trait\BuscarUsuarioTrait;

final class ClienteModel extends ORM
{
    protected string $ormTabela = TABELA_USUARIO_CLIENTE;

    use ValidarEmpresaTrait;
    use BuscarUsuarioTrait;

    public function __construct(
        protected ?Request $request = null
    ) {
        parent::__construct();
        $this->validarEmpresa('empresa');
        $this->validarCampoDoRequest();
    }

    public function listarDados(): stdClass
    {
        $dado = $this->buscarUsuario([
            'cod', 'nome', 'documento', 'email_trabalho', 'email_pessoal',
            'data_criacao', 'usuario_lead', 'tipo', 'titular', 'status'
        ], true);

        $dado->lista = $this->montarRetornoLista($dado->lista);
        return $dado;
    }

    private function montarRetornoLista($dado)
    {
        if (!$dado) {
            return [];
        }

        $TipoUsuario = new TipoUsuario();
        $lista = [];
        foreach ($dado as $r) {
            $email = null;
            if (!empty($r->email_pessoal)) {
                $email = $r->email_pessoal;
            } elseif (!empty($r->email_trabalho)) {
                $email = $r->email_trabalho;
            }
            $tipo = $TipoUsuario->nome($r->tipo);
            $uuid = $r->cod;
            if ($r->tipo == 2) {
                $uuid = $this->campo(['cod'])->where(['id', $r->titular])->read(indice: 0, campo: 'cod');
            }

            $lista[] = [
                'id' => $uuid,
                'empresa' => [
                    'id' => $r->empresa_cod,
                    'nome_fantasia' => $r->empresa_nome_fantasia,
                ],
                'nome' => $r->nome,
                'cpf' => $r->tipo == 2 ? '' : $r->documento,
                'email' => $email,
                'tipo' => $tipo,
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
        $TrabalhoEmpresa = new TrabalhoEmpresa($this->request->trabalho_empresa);
        $TrabalhoCargo = new TrabalhoCargo($this->request->trabalho_cargo);

        if (!empty($this->request->pagina) && !preg_match('/^[1-9]{1}[0-9]*$/', $this->request->pagina)) {
            mensagemErro('Campo inválido!', 'A página deve ser um número inteiro.');
        } elseif (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Campo inválido!', 'A ordem informada não é um valor válido.');
        } elseif (!$status->vazio() && !$status->valido()) {
            mensagemErro('Campo inválido!', 'O Status informado não é um valor válido.');
        } elseif (!$dataUpload->vazio() && !validarDate($dataUpload)) {
            mensagemErro('Campo inválido!', 'A data de upload informado não é um valor válido.');
        } elseif (!$dataCriacaoDe->vazio() && !validarDate($dataCriacaoDe)) {
            mensagemErro('Campo inválido!', 'A data de criação do começo informado não é um valor válido.');
        } elseif (!$dataCriacaoAte->vazio() && !validarDate($dataCriacaoAte)) {
            mensagemErro('Campo inválido!', 'A data de criação final informado não é um valor válido.');
        } elseif (!$status->vazio() && (!$status->valido() || $status->indice() == 'deletado')) {
            mensagemErro('Campo inválido!', 'O Status não é um valor válido.');
        } elseif (!$TrabalhoEmpresa->vazio() && !$TrabalhoEmpresa->valido()) {
            mensagemErro('Campo inválido!', 'O local de trabalho não é um valor válido.');
        } elseif (!$TrabalhoCargo->vazio() && !$TrabalhoCargo->valido()) {
            mensagemErro('Campo inválido!', 'O cargo não é um valor válido.');
        }
    }
}
