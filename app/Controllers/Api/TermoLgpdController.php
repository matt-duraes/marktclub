<?php

namespace App\Controllers\Api;

use Http\Request;
use Controller\Controller;
use App\Models\Api\AdminConstrutor\ConstrutorEntity;
use App\Models\Api\LoginApi\LoginModel as LoginApiModel;

final class TermoLgpdController extends Controller
{
    public function assinar(string $hash)
    {
        $path = DIRETORIO_PRIVADO . '/lgpd/' . $hash . '.json';
        $dado = pegarArquivo($path);
        deletarArquivo($path);
        if (
            !is_array($dado) ||
            !array_key_exists('usuario', $dado) ||
            !array_key_exists('empresa', $dado) ||
            !array_key_exists('link', $dado)
        ) {
            return $this->lgpdErro();
        }

        try {
            $Construtor = new ConstrutorEntity();
            $Construtor->buscar([
                ['empresa', $dado['empresa']],
                ['status', 1]
            ]);
        } catch (\Throwable) {
            return $this->lgpdErro();
        }

        return view('lgpd.aceitar', [
            'link' => $dado['link'],
            'hash' => base64Encode($dado, true),
            'logo' => $Construtor->link_logo,
            'cor' => $Construtor->cor
        ]);
    }

    public function lgpdErro()
    {
        return view('lgpd.erro');
    }

    public function postSalvar(Request $request)
    {
        $dado = base64Decode($request->hash);
        if ($request->termo != 'sim') {
            mensagemErro('Campo obrigatório!', 'Você deve aceitar os termos para continuar.');
        } elseif (
            !is_array($dado) ||
            !array_key_exists('empresa', $dado) ||
            !array_key_exists('usuario', $dado) ||
            !array_key_exists('nome', $dado['usuario']) ||
            !array_key_exists('cpf', $dado['usuario'])
        ) {
            mensagemErro('Erro!', 'Não foi possível validar seus dados, volte e refaça seu login para continuar.');
        }

        $dado['usuario']['termo_lgpd'] = 'sim';
        $Login = new LoginApiModel($dado['usuario'], $dado['empresa']);
        return $Login->link();
    }
}
