<?php

namespace App\Models\Api\UsuarioCliente\Trait;

use App\Classes\UsuarioCliente\Helper;
use App\Classes\UsuarioCliente\Status;

trait WhereTrait
{
    private function pegarWhere(): array
    {
        $request = $this->request;

        $where = [
            [
                'OR',
                ['empresa', $this->idEmpresa],
                ['tipo', 'in', [1, 3]],
            ]
        ];

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
            if (!empty($documento)) {
                $wherePesquisa[] = ['documento', 'like', $documento . '%'];
            }
            $where[] = [$wherePesquisa];
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

        // status
        $status = new Status($request->status);
        if ($status->valido() && $status->indice() != 'deletado') {
            $where[] = ['status', $status->numero()];
        } else {
            $where[] = ['status', 'in', Helper::STATUS_LIBERADO];
        }
        return $where;
    }
}
