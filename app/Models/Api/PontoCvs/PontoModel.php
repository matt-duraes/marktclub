<?php

namespace App\Models\Api\PontoCvs;

use stdClass;
use Http\Request;
use App\Models\Api\GeralModel;
use App\Classes\PontoCvs\Ordem;
use App\Classes\PontoCvs\Status;
use App\Classes\UsuarioCliente\Helper;
use App\Helpers\PontoCvsHelper;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class PontoModel extends GeralModel
{
    protected string $_tabela = TABELA_PONTO_CVS;
    protected bool $buscaCpf = false;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'ponto_solicitado', 'data_solicitacao', 'data_voucher', 'voucher', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->order(new Ordem($this->request->ordem))
            ->tabela('usuario_novo')->join('documento', 'id_usuario_cliente')->campo(['nome', 'documento'])
            ->read();

        if($this->buscaCpf && !empty($dado->lista)){
            $PontoCvsHelper = new PontoCvsHelper;
            $saldo = $PontoCvsHelper->buscarPontos($dado->lista[0]->documento);
            
            $dado->saldo = $saldo;
        }

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
                'usuario' => $r->nome,
                'ponto' => strNull($r->ponto_solicitado),
                'voucher' => strNull($r->voucher),
                'data_solicitacao' => dataHoraBr($r->data_solicitacao),
                'data_voucher' => dataHoraBr($r->data_voucher),
                'status' => $Status->indice($r->status)
            ];
        }
        return $retorno;
    }

    protected function pegarWhere(): array
    {
        $where = [];

        $usuario = $this->buscarIdUsuarioPeloCpf();
        if (!empty($usuario)) {
            $where[] = ['id_usuario_cliente', $usuario];
            $this->buscaCpf = true;
        }

        $status = new Status($this->request->status);
        if (!empty($status) && $status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        return $where;
    }

    private function buscarIdUsuarioPeloCpf()
    {
        $Usuario = new ClienteEntity();
        $Usuario->buscar([
            ['documento', soNumero($this->request->cpf)],
            ['empresa', $this->idEmpresa],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);
        
        if(!empty($Usuario->id))
        {
            return $Usuario->get('id');
        }
        return soNumero($this->request->cpf);
    }

    private function validarRequest()
    {
        $pagina = $this->request->pagina;
        $quantidade = $this->request->quantidade;
        $ordem = new Ordem($this->request->ordem);
        $status = new Status($this->request->status);

        if (!validarPagina($pagina)) {
            mensagemErro('Dado inválido!', 'O campo página não é um valor válido.');
        } else if (!empty($quantidade) && !validarPagina($quantidade)) {
            mensagemErro('Dado inválido!', 'O campo quantidade não é um valor válido.');
        } else if (!empty($quantidade) && $quantidade > 50) {
            mensagemErro('Dado inválido!', 'O campo quantidade deve ser menor ou igual a 50.');
        } else if (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Dado inválido!', 'O campo ordem não é um valor válido.');
        } else if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Dado inválido!', 'O campo status não é um valor válido.');
        }
    }
}
