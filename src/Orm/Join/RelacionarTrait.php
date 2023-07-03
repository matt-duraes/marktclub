<?php

namespace ORM\Join;

trait RelacionarTrait
{
    /**
     * Cria uma relação em uma Entity
     *
     * @param string $tabela         Tabela que deseja fazer a relação
     * @param string $campoAtual     Campo da tabela para fazer a relação
     * @param string $campoOriginal  Campo da tabela original para fazer a relação
     * @param string $campo          Campos da $tabela que deseja buscar
     * @param string $tabelaOriginal Caso não queira usar a tabela da Entity para fazer a relação
     * @param string $alias          Caso queira usar uma alias para os campos
     * @param string $condicao       Condição para a relação
     * @param string $tipo           Qual tipo de JOIN será usado podendo ser: INNER, LEFT, RIGHT, CROSS ou FULL
     */
    public function relacionarTabela(
        string $tabela,
        string $campoAtual,
        string $campoOriginal = '',
        array $campo = [],
        string $tabelaOriginal = '',
        string $alias = '',
        string $condicao = '=',
        string $tipo = 'INNER',
        array $where = []
    ) {
        if (!in_array($tipo, ['INNER', 'LEFT', 'RIGHT', 'CROSS', 'FULL'])) {
            mensagemErro(titulo: 'Campo incorreto!', mensagem: 'O tipo de JOIN (' . $tipo . ') não é um valor padrão.');
        }

        $this->ormRelacionado[] = [
            'tabela'          => $tabela,
            'campo_atual'     => $campoAtual,
            'campo_original'  => $campoOriginal,
            'tabela_original' => !empty($tabelaOriginal) ? $tabelaOriginal : $this->ormTabela,
            'condicao'        => $condicao,
            'campo'           => $campo,
            'alias'           => $alias,
            'tipo'            => $tipo,
            'where'           => $where
        ];
    }
}
