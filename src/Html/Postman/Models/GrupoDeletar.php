<?php

namespace System\Html\Postman\Models;

final class GrupoDeletar
{
    private string $path = ROOT . '/postman/';
    public function __construct($post)
    {
        $this->path .= $post['id'];
        $this->verificarSeExiste();
        $this->deletarDiretorioSubDiretorio($this->path);
    }

    public function retorno()
    {
        return [];
    }

    private function verificarSeExiste(): void
    {
        if (!is_dir($this->path)) {
            mensagemErro('Erro!', 'Não foi encontrado o diretório para deletar.');
        }
    }
    private function deletarDiretorioSubDiretorio($path): void
    {
        $lista = array_diff(scandir($path), array('.','..'));

        foreach ($lista as $arquivo) {
            $temp = $path . '/' . $arquivo;
            (is_dir($temp)) ? $this->deletarDiretorioSubDiretorio($temp) : unlink($temp);
        }
        rmdir($path);
    }
}
