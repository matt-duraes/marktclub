<?php

namespace App\Controllers\Api;

use Http\Response;
use Controller\Controller;
use App\Classes\DownloadPrivado\Status;
use System\Interface\ControllerBuscarInterface;
use App\Models\Api\DownloadPrivado\ArquivoEntity;

final class DownloadSistemaController extends Controller implements
    ControllerBuscarInterface
{
    public function getBuscar(string $id): Response
    {
        $Arquivo = new ArquivoEntity();
        $Arquivo->id($id);

        $dado = pegarPropriedadeDaEntity(
            $Arquivo,
            lista: ['id', 'dono', 'arquivo', 'link', 'vencido', 'status']
        );

        $Arquivo->status = new Status(2);
        $Arquivo->salvar();

        return mensagemSucesso($dado);
    }
}
