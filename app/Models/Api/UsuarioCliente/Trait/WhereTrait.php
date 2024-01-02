<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Origem;
use App\Classes\UsuarioCliente\Status;
use App\Classes\UsuarioCliente\TipoUsuario;
use App\Classes\UsuarioCliente\TrabalhoCargo;
use App\Classes\UsuarioCliente\TrabalhoEmpresa;
use Helpers\ListaHelper;
use Helpers\OrmHelper;

trait WhereTrait
{
    protected function pegarWhere(): array
    {
        $request = $this->request;
        $where = [];
        $listaEstado = (new ListaHelper())->uf()->r();

        $whereEmpresa = $this->pegarWhereEmpresa();
        if ($whereEmpresa) {
            $where[] = $whereEmpresa;
        }

        $whereTipo = $this->pegarWhereTipo();
        if ($whereTipo) {
            $where[] = $whereTipo;
        }

        $whereFederacao = $this->pegarWhereFederacao($listaEstado);
        if ($whereFederacao) {
            $where[] = $whereFederacao;
        }

        if (!empty($this->idSubempresa)) {
            $where[] = ['id_admin_subempresa', $this->idSubempresa];
        }

        // Colocando para aparecer só quem tem data de ativação na FENAE
        if ($this->idEmpresa == 153) {
            $where[] = ['data_ativacao', 'notnull'];
        }

        $pesquisa = $request->pesquisa;
        if (!empty($pesquisa)) {
            $wherePesquisa = [
                'OR',
                ['nome', 'like', '%' . $pesquisa . '%'],
                ['email_pessoal', 'like', $pesquisa . '%'],
                ['email_trabalho', 'like', $pesquisa . '%']
            ];
            $documento = soNumero($pesquisa);
            if (!empty($documento) && validarCpf($documento)) {
                $wherePesquisa[] = ['documento', 'like', $documento . '%'];
            }
            $where[] = [$wherePesquisa];
        }

        // estado
        $enderecoEstado = $request->endereco_estado;
        if (in_array($enderecoEstado, $listaEstado)) {
            $where[] = ['uf', $enderecoEstado];
        }

        // nome
        $nome = $request->nome;
        if (!empty($nome)) {
            $where[] = ['nome', 'like', '%' . $nome . '%'];
        }

        // email
        $email = $request->email;
        if (!empty($nome)) {
            $where[] = [
                'OR',
                ['email_pessoal', 'like', $email . '%'],
                ['email_trabalho', 'like', $email . '%'],
            ];
        }

        // cpf
        $cpf = soNumero($request->cpf);
        if (!empty($cpf)) {
            $where[] = ['documento', 'like', $cpf . '%'];
        }

        // matricula
        $matricula = soNumero($request->matricula);
        if (!empty($matricula)) {
            $where[] = ['matricula', 'like', $matricula . '%'];
        }

        // siape
        $siape = soNumero($request->siape);
        if (!empty($siape)) {
            $where[] = ['siape', 'like', $siape . '%'];
        }

        // data upload
        $dataUpload = $request->data_upload;
        if (validarDate($dataUpload)) {
            $where[] = ['data_upload_tabela', dataBanco($dataUpload)];
        }

        // data criacao de
        $dataCriacaoDe = $request->data_criacao_de;
        if (validarDate($dataCriacaoDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataCriacaoDe)];
        }
        // data criacao ate
        $dataCriacaoAte = $request->data_criacao_ate;
        if (validarDate($dataCriacaoAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataCriacaoAte) . ' 23:59:59'];
        }

        // Lead
        if ($request->lead == 'sim') {
            $where[] = ['usuario_lead', 1];
        }

        // Origem
        $origem = new Origem($request->origem);
        if ($origem->valido()) {
            $where[] = ['lead_origem', $origem->numero()];
        }

        // status
        $status = new Status($request->status);
        if ($status->valido() && $status->indice() != 'deletado') {
            $where[] = ['status', $status->numero()];
        } else {
            $where[] = ['status', 'in', Helper::STATUS_LIBERADO];
        }

        // Trabalho Empresa
        $TrabalhoEmpresa = new TrabalhoEmpresa($request->trabalho_empresa);
        if (!$TrabalhoEmpresa->vazio() && $TrabalhoEmpresa->valido()) {
            $where[] = ['trabalho_orgao', $TrabalhoEmpresa->numero()];
        }
        // Trabalho Cargo
        $TrabalhoCargo = new TrabalhoCargo($request->trabalho_cargo);
        if (!$TrabalhoCargo->vazio() && $TrabalhoCargo->valido()) {
            $where[] = ['trabalho_cargo', $TrabalhoCargo->numero()];
        }
        return $where;
    }

    private function pegarWhereEmpresa()
    {
        if (!$this->verificarSePodeMudarEmpresa()) {
            return $this->ormWherePadrao;
        }

        if (!empty($this->request->empresa)) {
            return ['empresa', (new OrmHelper(TABELA_COMERCIAL_EMPRESA))->pegarIdPeloUuid($this->request->empresa)];
        }
    }

    private function pegarWhereTipo()
    {
        if ($this->request->tipo == 'funcionario') {
            return ['tipo', (new TipoUsuario(TipoUsuario::TITULAR))->numero()];
        }

        $tipo = new TipoUsuario($this->request->tipo);
        if (!$tipo->vazio() && $tipo->valido()) {
            // Funcionario é do tipo titular com federação = FU
            return ['tipo', $tipo->numero()];
        }
    }

    private function pegarWhereFederacao(array $listaEstado)
    {
        $federacao = $this->request->federacao;
        $tipo = $this->request->tipo;

        if ($tipo == 'funcionario') {
            return ['federacao', 'FU'];
        }

        if ($tipo === 'titular' && empty($federacao)) {
            return [
                'OR',
                ['federacao', '!=', 'FU'],
                ['federacao', 'isnull']
            ];
        }

        if (in_array($federacao, $listaEstado)) {
            return ['federacao', $federacao];
        }
    }
}
