<?php

namespace App\Models\Api\Trait;

use App\Models\Api\Painel\LogDownloadEntity;

trait DownloadModelTrait
{
    private function salvarLogDownload(array $dado, string $app)
    {
        $Log = new LogDownloadEntity(
            app: $app,
            request: $this->request->dado(),
            quantidade: count($dado),
            usuario: $this->usuario
        );
        try {
            $Log->salvar();
        } catch (\Throwable) {
            $this->erroDownloadPadrao();
        }
    }

    /**
     * Mensagem de erro padrão
     *
     * @return
     */
    private function erroDownloadPadrao(): void
    {
        mensagemErro('Erro!', 'Ocorreu um erro ao fazer o download, por favor, tente novamente.');
    }

    /**
     * Valida se os campos foram passados e são validos
     *
     * @param array $campo  Campos que o usuário enviou
     * @param array $aceito Campos aceitos
     */
    private function validarCampoAceito($campo, $aceito): void
    {
        if (!$campo) {
            mensagemErro('Erro!', 'Você deve enviar pelo menos um campo.');
        }

        foreach ($campo as $item) {
            if (!in_array($item, $aceito)) {
                mensagemErro(
                    'Erro!',
                    'Um ou mais campos não tem permissão para serem buscados.',
                    status: 403,
                    localhost: 'O campo ' . $item . ' não está na lista de campos permitidos'
                );
            }
        }
    }
}
