<?php

namespace ORM\Trait;

use ORM\ORM;
use Erro\Excecao;

trait TabelaTrait
{
    /**
     * @param string $tabela Um namespace de um ORM ou o nome da tabela
     */
    protected function tabela(string $tabela)
    {
        $tabela = $this->ormPegarTabelaDaClasse($tabela);
        $this->verificarSeTabelaExiste($tabela);
        $this->ormTabelaAtual = $tabela;
        return $this;
    }

    private function ormPegarTabelaDaClasse(string $tabela): string
    {
        if (class_exists($tabela)) {
            $classe = new $tabela();
            return $classe instanceof ORM && !empty($classe->ormTabela) && is_string($classe->ormTabela)
                ? $classe->ormTabela : $tabela;
        }
        return $tabela;
    }

    private function verificarSeTabelaExiste(string $tabela)
    {
        if (empty($this->ormDB->query("SHOW TABLES LIKE '$tabela'")->rowCount())) {
            throw new Excecao(
                titulo: 'Tabela não encontrada!',
                mensagem: 'A tabela ' . $tabela . 'não foi encontrada na base de dados.'
            );
        }
    }
}
