<?php

namespace Helpers;

final class TextoHelper
{

    /**
     * @param Mixed $valor Valor a ser convertido
     */
    public function __construct(
        private $valor = ''
    ) {
    }

    public function __toString()
    {
        $valor = (string) $this->valor;
        $this->valor = '';
        if (!empty($valor)) {
            return $valor;
        }
        return '';
    }

    /**
     * @param Mixed $valor $valor Valor a ser convertido
     */
    public function valor($valor)
    {
        $this->valor = $valor;
        return $this;
    }

    public function r()
    {
        $valor = $this->valor;
        $this->valor = '';
        return $valor;
    }

    private function validar()
    {
        if (empty($this->valor)) {
            return false;
        }
        return true;
    }

    public function estado()
    {
        if (!$this->validar()) {
            return $this;
        }
        $ListaHelper = new ListaHelper;
        $tamanho = strlen($this->valor);
        if ($tamanho == 2) {
            $estado = $ListaHelper->uf()->r();
            $this->valor = $this->caixa('A', $this->valor)->r();
        } else {
            $estado = $ListaHelper->uf()->r();
            if (is_array($estado) && $estado) {
                $estado = array_flip($estado);
            }
            $this->valor = $this->caixa('Aa Aa', $this->valor)->r();
        }
        $this->valor = isset($estado[$this->valor]) ? $estado[$this->valor] : '';
        return $this;
    }

    /**
     * @param String $slug Caracter que será substituido por espaço
     */
    public function slug($slug = '-')
    {
        if (!$this->validar()) {
            return $this;
        }

        $valor = mb_strtolower(trim($this->valor), 'UTF-8');
        $valor = preg_replace('/[áàãâä]/ui', 'a', $valor);
        $valor = preg_replace('/[éèêë]/ui', 'e', $valor);
        $valor = preg_replace('/[íìîï]/ui', 'i', $valor);
        $valor = preg_replace('/[óòõôö]/ui', 'o', $valor);
        $valor = preg_replace('/[úùûü]/ui', 'u', $valor);
        $valor = preg_replace('/[ç]/ui', 'c', $valor);
        $valor = trim(preg_replace('/[^a-z0-9]/i', ' ', $valor));
        $valor = preg_replace('/[^a-z0-9]/i', $slug, $valor);

        $this->valor = $valor;
        return $this;
    }

    /**
     * Remover todo o HTML da string exceto o especificado para ficar
     *
     * @param null|string $tag  Lista de tags que não devem ser removidas. Ex: <a><p><i>
     * @return Self
     */
    public function removerHtml(?string $tag = null): self
    {
        if (!$this->validar()) {
            return $this;
        }
        $this->valor = strip_tags($this->valor, $tag);
        return $this;
    }

    public function removerAcento()
    {
        if (!$this->validar()) {
            return $this;
        }
        $valor = $this->valor;
        $valor = preg_replace('/[áàãâä]/u', 'a', $valor);
        $valor = preg_replace('/[ÁÀÃÂÄ]/u', 'A', $valor);
        $valor = preg_replace('/[éèêë]/u', 'e', $valor);
        $valor = preg_replace('/[ÉÈÊË]/u', 'E', $valor);
        $valor = preg_replace('/[íìîï]/u', 'i', $valor);
        $valor = preg_replace('/[ÍÌÎÏ]/u', 'I', $valor);
        $valor = preg_replace('/[óòõôö]/u', 'o', $valor);
        $valor = preg_replace('/[ÓÒÕÔÖ]/u', 'O', $valor);
        $valor = preg_replace('/[úùûü]/u', 'u', $valor);
        $valor = preg_replace('/[ÚÙÛÜ]/u', 'U', $valor);
        $valor = preg_replace('/[ç]/u', 'c', $valor);
        $valor = preg_replace('/[Ç]/u', 'C', $valor);
        $this->valor = $valor;
        return $this;
    }

    /**
     * Corta uma string
     *
     * @param int       $tamanho    Quantidade de caracter que deve ter a string
     * @param string    $simbolo    Simbolo que ficaram no final da string cortada
     * @param bool      $forca      Força o corte da string mesmo sem terminar a palavra
     * @return self                 Retorna a string cortada
     */
    public function cortar(int $tamanho, string $simbolo = '...', bool $forca = false): self
    {
        if (!$this->validar()) {
            return $this;
        }
        $valor = $this->valor;
        if (strlen($valor) > $tamanho && $forca == true) {
            $valor = mb_substr($valor, 0, $tamanho, 'UTF-8') . $simbolo;
        } elseif (strlen($valor) > $tamanho) {
            $valor = mb_substr($valor, 0, strrpos(substr($valor, 0, $tamanho), ' '), 'UTF-8') . $simbolo;
        }
        $this->valor = $valor;
        return $this;
    }

    /**
     * @param String $tipo Seta o tipo de caixa: "A": Caixa alta; "a": caixa baixa; "Aa": Caixa alta na primeira letra; "Aa Aa": Caixa alta na primeira letra de cada palavra
     */
    public function caixa($tipo = 'A')
    {
        if (!$this->validar() || !is_string($this->valor)) {
            return $this;
        }
        if ($tipo == 'A') {
            $this->valor = mb_strtoupper($this->valor, 'UTF-8');
        } elseif ($tipo == 'a') {
            $this->valor = mb_strtolower($this->valor, 'UTF-8');
        } elseif ($tipo == 'Aa') {
            $this->valor = ucfirst(mb_strtolower($this->valor, 'UTF-8'));
        } elseif ($tipo == 'Aa Aa') {
            $this->valor = ucwords(mb_strtolower($this->valor, 'UTF-8'));
        }
        return $this;
    }

    public function replace(string | array $de, string | array $por)
    {
        if (!$this->validar()) {
            return $this;
        }
        $this->valor = str_replace($de, $por, $this->valor);
        return $this;
    }

    public function pregReplace(string | array $de, string | array $por)
    {
        if (!$this->validar()) {
            return $this;
        }
        $this->valor = preg_replace($de, $por, $this->valor);
        return $this;
    }

    public function documento()
    {
        if (!$this->validar()) {
            return $this;
        }
        $valor = preg_replace('/[^0-9]/', '', $this->valor);
        if (!empty($valor) && strlen($valor) == 11) {
            $valor = substr($valor, 0, 3) . '.' . substr($valor, 3, 3) . '.' . substr($valor, 6, 3) . '-' . substr($valor, 9, 2);
        } elseif (!empty($valor) && strlen($valor) == 14) {
            $valor = substr($valor, 0, 2) . '.' . substr($valor, 2, 3) . '.' . substr($valor, 5, 3) . '/' . substr($valor, 8, 4) . '-' . substr($valor, 12, 2);
        }
        $this->valor = $valor;
        return $this;
    }

    public function cpf()
    {
        if (!$this->validar()) {
            return $this;
        }
        $valor = preg_replace('/[^0-9]/', '', $this->valor);
        if (!empty($valor) && strlen($valor) == 11) {
            $valor = substr($valor, 0, 3) . '.' . substr($valor, 3, 3) . '.' . substr($valor, 6, 3) . '-' . substr($valor, 9, 2);
        }
        $this->valor = $valor;
        return $this;
    }

    public function cnpj()
    {
        if (!$this->validar()) {
            return $this;
        }
        $valor = preg_replace('/[^0-9]/', '', $this->valor);
        if (!empty($valor) && strlen($valor) == 14) {
            $valor = substr($valor, 0, 2) . '.' . substr($valor, 2, 3) . '.' . substr($valor, 5, 3) . '/' . substr($valor, 8, 4) . '-' . substr($valor, 12, 2);
        }
        $this->valor = $valor;
        return $this;
    }

    public function decimal()
    {
        if (!$this->validar()) {
            return $this;
        }
        $valor = preg_replace('/[^0-9]/', '', $this->valor);
        if (is_numeric($valor) && substr_count($valor, ',') == 1 && strlen($valor) - strripos($valor, ',') == 3) {
            $this->valor = number_format(str_replace(['.', ','], ['', '.'], $valor), 2, '.', '');
        } elseif (is_numeric($valor)) {
            $this->valor = number_format(str_replace(',', '', $valor), 2, '.', '');
        }
        return $this;
    }

    /**
     * @param String $padrao Padrão quer será retornado o telefone
     */
    public function telefone(String $padrao = '')
    {
        if (!$this->validar()) {
            return $this;
        }

        $numero = preg_replace('/[^0-9]/', '', $this->valor);
        $quantidade = strlen($numero);

        if (!empty($padrao)) {
            $padraoQuant = strlen($padrao);
            $numeroI = 0;
            $numeroFinal = '';

            for ($i = 0; $i < $padraoQuant; $i++) {
                if ($padrao[$i] == 'x') {
                    $numeroFinal .= $numero[$numeroI];
                    $numeroI++;
                } else {
                    $numeroFinal .= $padrao[$i];
                }
            }

            $this->valor = $numeroFinal;
        } elseif ($quantidade == 8 && (substr($numero, 0, 4) == '3003' || substr($numero, 0, 4) == '4004')) {
            $this->valor = substr($numero, 0, 4) . '-' . substr($numero, 4, 4);
        } elseif ($quantidade == 10) {
            $this->valor = '(' . substr($numero, 0, 2) . ') ' . substr($numero, 2, 4) . '-' . substr($numero, 6, 4);
        } elseif ($quantidade == 11 && (substr($numero, 2, 1) == '9')) {
            $this->valor = '(' . substr($numero, 0, 2) . ') ' . substr($numero, 2, 5) . '-' . substr($numero, 7, 4);
        } elseif (substr($numero, 0, 4) == '0800') {
            $this->valor = substr($numero, 0, 4) . ' ' . substr($numero, 4, 3) . ' ' . substr($numero, 7, 4);
        }

        return $this;
    }

    /**
     * @param String $moeda Tipo de moeda será convertido podendo ser: "R$": Real (1.000,00); "$": Dolar (1000.00)
     */
    public function dinheiro(string $tipo = 'R$', string $prefix = '')
    {
        if (!$this->validar()) {
            return $this;
        }

        $valor = $this->valor;
        $real = preg_replace('/[^0-9]/', '', substr($valor, 0, -3));
        $separador = substr($valor, -3, 1);
        $centavo = substr($valor, -2, 2);

        if (!in_array($separador, ['.', ','])) {
            $this->valor = '';
            return $this;
        }
        if (!is_numeric($centavo)) {
            $this->valor = '';
            return $this;
        }

        $prefix = !empty($prefix) ? trim($prefix) . ' ' : '';

        if (in_array($tipo, ['R$', 'r$'])) {
            $this->valor = $prefix . number_format($real . '.' . $centavo, 2, ',', '.');
        } elseif (in_array($tipo, ['U$', 'u$', '$'])) {
            $this->valor = $prefix . number_format($real . '.' . $centavo, 2, '.', '');
        }

        return $this;
    }

    public function cep()
    {
        if (!$this->validar()) {
            return $this;
        }
        $valor = preg_replace('/[^0-9]/', '', $this->valor);
        if (strlen($valor) == 8) {
            $this->valor = substr($valor, 0, 5) . '-' . substr($valor, 5, 3);
        } else {
            $this->valor = '';
        }
        return $this;
    }

    public function json()
    {
        if (!$this->validar() || !is_string($this->valor)) {
            return $this;
        }
        $valor = jsonDecode($this->valor, true);
        if (is_array($valor)) {
            $valor = array_filter($valor);
            if (!empty($valor)) {
                $this->valor = $valor;
            }
        }
        return $this;
    }
}
