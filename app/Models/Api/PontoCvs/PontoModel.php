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
use Modules\Cpf;

final class PontoModel extends GeralModel
{
    protected string $_tabela = TABELA_PONTO_CVS;
    protected string $buscaCpf = '';

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();
    }
    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'ponto_solicitado', 'mensagem', 'data_solicitacao', 'data_voucher', 'voucher', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order(new Ordem($this->request->ordem))
            ->tabela(TABELA_USUARIO_NOVO)->join('id', 'id_usuario_cliente')
            ->where(['empresa', 198])
            ->campo(['nome', 'documento'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        if(!empty($this->buscaCpf)){
            $PontoCvsHelper = new PontoCvsHelper;
            $saldo = $PontoCvsHelper->buscarPontos($this->buscaCpf);
            $extrato = $PontoCvsHelper->buscarExtrato($this->buscaCpf);

            $dado->saldo = $saldo;
            $dado->extrato = $extrato;
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
                'mensagem' => strNull($r->mensagem),
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

        $usuario = !empty($this->request->cpf) ? $this->buscarIdUsuarioPeloCpf() : '';
        if (!empty($usuario)) {
            $where[] = ['id_usuario_cliente', $usuario];
        }

        $status = new Status($this->request->status);
        if (!empty($status) && $status->valido()) {
            $where[] = ['status', $status->numero()];
        }

        return $where;
    }

    private function buscarIdUsuarioPeloCpf()
    {
        $Usuario = new ClienteEntity(validarToken: false);
    
        $Usuario->buscar([
            ['documento', soNumero($this->request->cpf)],
            ['empresa', 198],
            ['status', 'in', Helper::STATUS_LIBERADO]
        ], false);

        if(empty($Usuario->id))
        {
            return $this->request->cpf;
        }

        $this->buscaCpf = $Usuario->getCpf();

        return $Usuario->get('id');
    }

    private function validarRequest()
    {
        $pagina = $this->request->pagina;
        $quantidade = $this->request->quantidade;
        $cpf = new Cpf($this->request->cpf);
        $ordem = new Ordem($this->request->ordem);
        $status = new Status($this->request->status);

        if (!validarPagina($pagina)) {
            mensagemErro('Dado inválido!', 'O campo página não é um valor válido.');
        } else if (!empty($quantidade) && !validarPagina($quantidade)) {
            mensagemErro('Dado inválido!', 'O campo quantidade não é um valor válido.');
        } else if (!empty($quantidade) && $quantidade > 50) {
            mensagemErro('Dado inválido!', 'O campo quantidade deve ser menor ou igual a 50.');
        } else if (!$cpf->vazio() && !$cpf->valido()) {
            mensagemErro('Dado inválido!', 'O campo cpf não é um valor válido.');
        } else if (!$ordem->vazio() && !$ordem->valido()) {
            mensagemErro('Dado inválido!', 'O campo ordem não é um valor válido.');
        } else if (!$status->vazio() && !$status->valido()) {
            mensagemErro('Dado inválido!', 'O campo status não é um valor válido.');
        }
    }
}
