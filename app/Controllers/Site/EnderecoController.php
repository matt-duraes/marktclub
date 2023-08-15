<?php

namespace App\Controllers\Site;

use Controller\Controller;
use Helpers\LocalizacaoHelper;

final class EnderecoController extends Controller
{
    public function postEnderecoPorCep(string $cep)
    {
        return mensagemSucesso((new LocalizacaoHelper())->pegarEnderecoPeloCep($cep));
    }
}
