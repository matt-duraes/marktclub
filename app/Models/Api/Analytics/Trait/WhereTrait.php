<?php


namespace App\Models\Api\Analytics\Trait;

trait WhereTrait
{
    private function pegarWherePadrao()
    {
        $de = $this->de->date();
        $ate = $this->ate->date();

        $this->validarData($de, $ate);

        return [
            ['data_acesso', 'between', [$de, $ate]],
            ['id_admin_empresa', TOKEN['empresa']->get('id')]
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
