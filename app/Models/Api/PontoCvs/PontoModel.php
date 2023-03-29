<?php

namespace App\Models\Api\PontoCvs;

use ORM\ORM;
use stdClass;
use Modules\Cpf;
use Http\Request;
use App\Classes\PontoCvs\Ordem;
use App\Helpers\PontoCvsHelper;
use App\Classes\PontoCvs\Status;
use System\Trait\Model\PaginaTrait;
use App\Classes\UsuarioCliente\Helper;
use System\Trait\Model\QuantidadeTrait;
use App\Models\Api\UsuarioCliente\ClienteEntity;

final class PontoModel extends ORM
{
    use PaginaTrait;
    use QuantidadeTrait;

    protected string $_tabela = TABELA_PONTO_CVS;
    protected string $buscaCpf = '';

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->validarRequest();

        (new AtualizarStatusModel)->AtualizarStatus();
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'ponto_solicitado', 'mensagem', 'data_solicitacao', 'data_voucher', 'voucher', 'status'])
            ->where($this->pegarWhere(), obrigatorio: false)
            ->order(new Ordem($this->request->ordem))
            ->tabela(TABELA_USUARIO_CLIENTE)->join('id', 'id_usuario_cliente')
            ->where([
                'OR',
                ['empresa', 198],
                [
                    ['empresa', 1],
                    ['tipo', 3]
                ]
            ])
            ->campo(['nome', 'documento', 'email_pessoal'])
            ->pagina($this->pegarPagina(), $this->pegarQuantidade())
            ->read();

        $saldo = [];
        $extrato = [];

        if (!empty($this->buscaCpf)) {
            $PontoCvsHelper = new PontoCvsHelper;
            $saldo = $PontoCvsHelper->buscarPontos($this->buscaCpf);
            $extrato = $PontoCvsHelper->buscarExtrato($this->buscaCpf);
        }

        if (!empty($saldo) && !empty($extrato)) {
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
                'usuario_nome' => $r->nome,
                'usuario_email' => $r->email_pessoal,
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
            ['status', 'in', Helper::STATUS_LIBERADO],
            [
                'OR',
                ['empresa', 198],
                [
                    ['empresa', 1],
                    ['tipo', 3]
                ]
            ]
        ], false);

        if (empty($Usuario->id)) {
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
