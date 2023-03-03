<?php

namespace App\Classes\UsuarioEquipe;

use App\Classes\AdminEmpresa\Helper as AdminEmpresaHelper;

final class Helper
{
    const CRIPTOGRAFAR = [
        'nome', 'perfil', 'cpf', 'email', 'email_trabalho', 'email_pessoal',
        'telefone_trabalho', 'telefone_pessoal', 'genero', 'data_nascimento', 'senha',
        'imagem', 'imagem_facebook', 'imagem_google', 'id_facebook', 'id_google',
        'perfil',
        'empresa' => AdminEmpresaHelper::CRIPTOGRAFAR
    ];
    const PERMISSAO_EMPRESA = 'usuario_equipe_empresa';
}
