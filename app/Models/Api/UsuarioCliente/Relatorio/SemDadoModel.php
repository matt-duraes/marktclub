<?php

namespace App\Models\Api\UsuarioCliente\Relatorio;

use ORM\ORM;

final class SemDadoModel extends ORM
{
    protected string $_tabela = TABELA_USUARIO_CLIENTE;

    private int $idEmpresa;
    public function __construct()
    {
        $this->idEmpresa = defined('TOKEN') ? TOKEN['empresa']->get('id') : 1;
        parent::__construct();
    }
    public function pegarRelatorio()
    {

        $where = [
            ['empresa', $this->idEmpresa],
            ['status', 'in', [1, 2]],
            ['tipo', 'in', [1, 3]]
        ];
        if ($this->idEmpresa == 153) {
            $where[] = ['data_ativacao', 'notnull'];
        }

        $dado = $this->campo([
            'telefone_fixo', 'telefone_celular', 'email_pessoal', 'email_trabalho', 'uf', 'cidade', 'aniversario'
        ])->where($where)->read();


        $telefoneFixo = 0;
        $telefoneCelular = 0;
        $emailPessoal = 0;
        $emailTrabalho = 0;
        $enderecoEstado = 0;
        $enderecoCidade = 0;
        $dataNascimento = 0;

        $total = count($dado);

        foreach ($dado as $r) {
            if (empty($r->telefone_fixo)) {
                $telefoneFixo++;
            }
            if (empty($r->telefone_celular)) {
                $telefoneCelular++;
            }
            if (empty($r->email_pessoal)) {
                $emailPessoal++;
            }
            if (empty($r->email_trabalho)) {
                $emailTrabalho++;
            }
            if (empty($r->uf)) {
                $enderecoEstado++;
            }
            if (empty($r->cidade)) {
                $enderecoCidade++;
            }
            if (empty($r->aniversario)) {
                $dataNascimento++;
            }
        }

        $relatorio = [];
        if ($telefoneFixo > 0) {
            $relatorio[] = [
                'campo' => 'Telefone Fixo',
                'total' => $telefoneFixo,
                'porcentagem' => number_format(($telefoneFixo * 100) / $total, 2, '.')
            ];
        }
        if ($telefoneCelular > 0) {
            $relatorio[] = [
                'campo' => 'Telefone Celular',
                'total' => $telefoneCelular,
                'porcentagem' => number_format(($telefoneCelular * 100) / $total, 2, '.')
            ];
        }
        if ($emailPessoal > 0) {
            $relatorio[] = [
                'campo' => 'Email pessoal',
                'total' => $emailPessoal,
                'porcentagem' => number_format(($emailPessoal * 100) / $total, 2, '.')
            ];
        }
        if ($emailTrabalho > 0) {
            $relatorio[] = [
                'campo' => 'E-mail de trabalho',
                'total' => $emailTrabalho,
                'porcentagem' => number_format(($emailTrabalho * 100) / $total, 2, '.')
            ];
        }
        if ($enderecoEstado > 0) {
            $relatorio[] = [
                'campo' => 'Estado do endereço',
                'total' => $enderecoEstado,
                'porcentagem' => number_format(($enderecoEstado * 100) / $total, 2, '.')
            ];
        }
        if ($enderecoCidade > 0) {
            $relatorio[] = [
                'campo' => 'Cidade do endereço',
                'total' => $enderecoCidade,
                'porcentagem' => number_format(($enderecoCidade * 100) / $total, 2, '.')
            ];
        }
        if ($dataNascimento > 0) {
            $relatorio[] = [
                'campo' => 'Data de nascimento',
                'total' => $dataNascimento,
                'porcentagem' => number_format(($dataNascimento * 100) / $total, 2, '.')
            ];
        }

        return [
            'total' => $total,
            'lista' => $relatorio
        ];
    }
}
