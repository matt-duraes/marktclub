<?php

namespace App\Models\Site\Samsung;

use App\Helpers\ClubeApiHelper;

final class BuscarModel extends ClubeApiHelper
{
    public function __construct()
    {
        parent::__construct('usuario_cliente:buscar');
    }

    public function buscar()
    {
        $dado = $this
            ->get('/usuario-cliente/' . $this->idUsuario)
            ->object()->dado;

        $emailPessoal = !empty($dado->email_pessoal ?? '') ? $this->Crypt->decode($dado->email_pessoal) : '';
        $emailTrabalho = !empty($dado->email_trabalho ?? '') ? $this->Crypt->decode($dado->email_trabalho) : '';

        $email = [];
        if (!empty($emailPessoal)) {
            $email[] = $emailPessoal;
        }
        if (!empty($emailTrabalho)) {
            $email[] = $emailTrabalho;
        }
        return (object)[
            'email'    => $email,
            'pessoal'  => $emailPessoal,
            'trabalho' => $emailTrabalho,
            'link'     => $this->pegarLinkSamsung()
        ];
    }

    private function pegarLinkSamsung()
    {
        return [
            '556d7a18533d1e4d013dd9a8c5b86c58' => 'https://parcerias.samsung.com.br/markt-club?utm_source=markt-club_uber',
            '8986a24345f36de15e2a7f4a51f1c9e7' => 'https://parcerias.samsung.com.br/markt-club?utm_source=markt-club_digio',
        ][CLUBE_ID] ?? 'https://parcerias.samsung.com.br/markt-club';
    }
}
