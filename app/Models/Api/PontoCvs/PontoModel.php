<?php

namespace App\Models\Api\PontoCvs;

use stdClass;
use Http\Request;
use App\Models\Api\GeralModel;
use App\Classes\PontoCvs\Ordem;
use App\Classes\PontoCvs\Status;
use App\Classes\UsuarioCliente\Helper;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class PontoModel extends GeralModel
{
    protected string $_tabela = TABELA_PONTO_CVS;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }
    public function listarDados(): stdClass
    {
        $dado = $this->campo(['uuid', 'ponto_solicitado', 'data_solicitacao', 'data_voucher', 'voucher', 'status'])->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order(new Ordem($this->request->ordem))
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        $retorno = [];
        $Status = new Status();
        foreach ($dado as $r) {
            $retorno[] = [
                'id' => $r->uuid,
                'ponto_solicitado' => strNull($r->ponto_solicitado),
                'voucher' => strNull($r->voucher),
                'data_solicitacao' => $r->data_solicitacao,
                'data_voucher' => $r->data_voucher,
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    protected function pegarWhere(): array
    {
        $where = [];

        $usuario = $this->buscarIdUsuarioPeloCod($this->request->usuario);
        if ($usuario) {
            $where[] = ['id_usuario_cliente', $usuario];
        }

        $status = new Status($this->request->status);
        if (!empty($status) && $status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        return $where;
    }

    private function buscarIdUsuarioPeloCod()
    {
        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['cod', $this->request->usuario],
            ['empresa', $this->idEmpresa],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);

        if(!empty($Usuario->id))
        {
            return $Usuario->get('id');
        }
        return false;
    }

    private function validarRequest()
    {
        $pagina = $this->request->pagina;
        $quantidade = $this->request->quantidade;
        $ordem = new Ordem($this->request->ordem);
        $usuario = $this->request->usuario;
        $status = new Status($this->request->status);

        if (!validarPagina($pagina)) {
            mensagemErro('Dado inválido!', 'O campo página não é um valor válido.');
        } else if (!empty($quantidade) && !validarPagina($quantidade)) {
            mensagemErro('Dado inválido!', 'O campo quantidade não é um valor válido.');
        } else if (!empty($quantidade) && $quantidade > 50) {
            mensagemErro('Dado inválido!', 'O campo quantidade deve ser menor ou igual a 50.');
        } else if (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Dado inválido!', 'O campo ordem não é um valor válido.');
        } else if (!empty($usuario) && !validarUuid($usuario, false)) {
            mensagemErro('Dado inválido!', 'O campo usuario não é um valor válido.');
        } else if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Dado inválido!', 'O campo status não é um valor válido.');
        }
    }
}
