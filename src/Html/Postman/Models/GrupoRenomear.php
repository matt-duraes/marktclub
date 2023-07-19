<?php

namespace System\Html\Postman\Models;

final class GrupoRenomear
{
    private string $path = ROOT . '/postman/';
    private string $nomeAtual;
    private string $nomeNovo;

    public function __construct($post)
    {
        $this->nomeAtual = $this->setarNome($post['id']);
        $this->nomeNovo = $this->setarNome($post['nome']);

        $novo = $post['novo'] == 'sim';
        $this->verificarSeExiste($novo);
        if ($novo) {
            $this->criarDiretorio();
            return;
        }
        $this->renomearDiretorio();
    }

    public function retorno()
    {
        return [];
    }

    private function setarNome($nome)
    {
        if (empty($nome)) {
            return '';
        }
        return preg_replace(['/[^A-Za-z\ \-\_0-9à-úÀÚ]/', '/\ {1,}/'], ['', ' '], trim($nome));
    }

    private function verificarSeExiste(bool $novo): void
    {
        $existe = is_dir($this->path . $this->nomeAtual);
        if ($existe && $novo) {
            mensagemErro('Erro!', 'Já existe um diretório com o mesmo nome.');
        } elseif (!$existe && !$novo) {
            mensagemErro('Erro!', 'Não foi encontrado o diretório para renomear.');
        }
    }

    private function criarDiretorio()
    {
        if (!mkdir($this->path . $this->nomeNovo, 0777)) {
            mensagemErro('Erro!', 'Erro ao criar novo diretório.');
        }
    }

    private function renomearDiretorio()
    {
        if (!rename($this->path . $this->nomeAtual, $this->path . $this->nomeNovo)) {
            mensagemErro('Erro!', 'Não foi possível renomear o diretório atual.');
        }
    }
}
