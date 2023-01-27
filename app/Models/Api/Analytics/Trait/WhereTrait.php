<?php


namespace App\Models\Api\Analytics\Trait;

trait WhereTrait
{
    private function pegarWherePadrao($de, $ate)
    {
        $de = dataBanco($de);
        $ate = dataBanco($ate);

        $this->validarData($de, $ate);

        return [
            ['data_acesso', '>=', $de],
            ['data_acesso', '<=', $ate . ' 23:59:59'],
            ['id_admin_empresa', $this->idEmpresa]
        ];
    }

    private function validarData($de, $ate, int $diaMaximo = 366)
    {
        $diasDiferenca = dataDiferencaDia($de, $ate);
        if (empty($de)) {
            mensagemErro('Data obrigatória!', 'A data de começo da busca é obrigatória.');
        } else if (!validarDate($de)) {
            mensagemErro('Data inválida!', 'A data de começo da busca não está em um formato válido.');
        } else if (empty($ate)) {
            mensagemErro('Data obrigatória!', 'A data final da busca é obrigatória.');
        } else if (!validarDate($ate)) {
            mensagemErro('Data inválida!', 'A data final da busca não está em um formato válido.');
        } else if ($diasDiferenca > $diaMaximo) {
            mensagemErro('Datas inválidas!', 'Você deve fazer uma busca com no máximo ' . $diaMaximo . ' dia(s) de diferênça.');
        } else if ($ate < $de) {
            mensagemErro('Datas inválidas!', 'A data fianl da busca deve ser maior ou igual a data de começo.');
        }
    }
}
