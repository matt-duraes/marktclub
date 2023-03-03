<?php

namespace PainelApp\download\Controllers;

use Http\Request;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Controller\Controller;

final class DownloadController extends Controller
{
    public function buscar(string $id)
    {
        return view(
            arquivo: 'painel.download.index',
            var: [
                'download' => $id
            ]
        );
    }

    public function download(string $id)
    {
        if (!sessaoExiste('DOWNLOAD_PRIVADO_' . $id)) {
            mensagemStatus(404);
        }

        $link = sessao('DOWNLOAD_PRIVADO_' . $id);
        sessaoDeletar('DOWNLOAD_PRIVADO_' . $id);
        return new Response(url: $link);
    }

    public function postValidar(Request $request, string $id)
    {
        $request->vazio('senha', mensagem: 'Digite sua senha para continuar.');

        $chave = (new ApiHelper(token: true))->get('/admin/chave-publica')->object()->dado->chave;
        $Crypt = new CryptHelper(chavePublica: $chave);
        (new ApiHelper(token: true))
            ->validar('Erro ao validar a senha, por favor, tente novamente.')
            ->body([
                'senha' => $Crypt->encode($request->senha)
            ])
            ->post('/usuario-equipe/validar-senha');

        $mensagemErro = 'O arquivo procurado não foi encontrado, já foi baixado ou está vencido.';

        $arquivo = (new ApiHelper(token: true))
            ->validar($mensagemErro)
            ->get('/download-privado/' . $id)
            ->object()->dado;

        if ($arquivo->dono->id != sessao('USUARIO.id') || $arquivo->status != 'novo' || $arquivo->vencido == 'sim') {
            mensagemErro('Erro!', $mensagemErro);
        }

        $hash = uuid();
        sessao('DOWNLOAD_PRIVADO_' . $hash, $arquivo->link);

        return mensagemSucesso([
            'id' => $hash
        ]);
    }
}
