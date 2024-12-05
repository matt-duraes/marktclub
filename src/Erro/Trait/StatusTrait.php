<?php

namespace Erro\Trait;

trait StatusTrait
{
    private function buscarStatusProjeto($status)
    {
        $this->verificarSeJaExistePagina();

        $diretorio = defined('ROUTE_DIRETORIO') ? mb_strtolower(ROUTE_DIRETORIO, 'UTF-8') : 'site';
        $path = ROOT . '/files/build/views/status_' . $diretorio . '_' . $status . '.php';

        if (!file_exists($path)) {
            return;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, LINK_PADRAO . '/fw-erro-status');
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, [
            'FW_ERRO_STATUS' => 'nao',
            'diretorio'      => $diretorio,
            'status'         => $status
        ]);
        $retorno = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        // $erro = curl_error($ch);

        curl_close($ch);
        if ($status !== 201 || empty($retorno)) {
            return;
        }
        echo $retorno;
        exit();
    }

    private function verificarSeJaExistePagina()
    {
        if (defined('FW_LOG_ERRO_EXISTE')) {
            exit();
        }
        define('FW_LOG_ERRO_EXISTE', true);
    }
}
