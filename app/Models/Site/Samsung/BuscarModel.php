<?php

namespace App\Models\Site\Samsung;

use App\Helpers\ClubeApiHelper;

final class BuscarModel extends ClubeApiHelper
{
    public function buscar()
    {
        $dado = $this
            ->validar(login: true)
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
            'link'     => ''
        ];
    }
}
