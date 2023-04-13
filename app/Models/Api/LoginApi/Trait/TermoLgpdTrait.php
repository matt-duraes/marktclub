<?php

namespace App\Models\Api\LoginApi\Trait;

use Http\Response;

trait TermoLgpdTrait
{
    public function mandarParaTermoLgpd(): Response
    {
        $arquivo = md5(uniqid(time()));
        $this->criarArquivoTemporario($arquivo);

        return mensagemSucesso([
            'link' => LINK . '/termo-lgpd/assinar/' . $arquivo
        ], status: 201);
    }

    private function criarArquivoTemporario($arquivo)
    {
        $dado = jsonEncode([
            'usuario' => removerIndiceVazio($this->request),
            'link' => $this->linkClube,
            'empresa' => $this->idEmpresa
        ]);

        criarArquivo(DIRETORIO_PRIVADO . '/lgpd/' . $arquivo . '.json', $dado);
    }
}
