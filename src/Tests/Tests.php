<?php

namespace Tests;

use Tests\Api;
use Random\Data;
use Random\Outros;
use Random\Contato;
use Random\Usuario;
use Random\Endereco;
use Random\Documento;
use Database\DataBase;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Helpers\CurlHelper as Curl;
use Helpers\RoboHelper as Robo;

abstract class Tests
{
    use Contato;
    use Data;
    use Documento;
    use Endereco;
    use Usuario;
    use Outros;

    private array $tabelaResetar = [];
    private array $todos = [];
    private array $passou = [];
    private array $falhou = [];
    protected Api|Curl $Curl;
    protected Robo $Robo;
    protected bool $checkRobo = true;
    protected bool $checkCurl = false;
    private CryptHelper $Crypt;

    public function __construct()
    {
        $this->Robo = new Robo();

        $Curl = new ApiHelper('admin:chave_publica admin:chave_privada');
        $chavePublica = $Curl->get('/admin/chave-publica')->object()->dado->chave ?? '';
        $chavePrivada = $Curl->get('/admin/chave-privada')->object()->dado->chave ?? '';
        $this->Crypt = new CryptHelper(chavePublica: $chavePublica, chavePrivada: $chavePrivada);
    }

    protected function cryptEncode(string|array $dado, array $lista = [])
    {
        if (empty($dado)) {
            return $dado;
        } elseif (is_string($dado)) {
            return $this->Crypt->encode($dado);
        }
        foreach ($dado as $ind => $val) {
            if (!empty($lista) && !in_array($ind, $lista)) {
                continue;
            }
            $dado[$ind] = $this->Crypt->encode($val);
        }
        return $dado;
    }

    protected function cryptDecode(string $dado)
    {
        return empty($dado) ? '' : $this->Crypt->decode($dado);
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK LIVRE
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica se o valor é igual do valor comparado
     *
     * @param mixed     $valor          Valor a comparar
     * @param mixed     $comparacao     Valor a ser comparado
     * @param string    $mensagem       Caso queira passar uma mensagem personalizada no final
     */
    protected function checkIgual($valor, $comparacao, ?string $mensagem = null)
    {
        $mensagem = !empty($mensagem) ? ' (' . $mensagem . ')' : '';
        if ($valor == $comparacao) {
            $this->setarRetorno(
                true,
                'O valor <strong>' . $valor . '</strong> é igual ao valor comparado <strong>'
                    . $comparacao . '</strong>.' . $mensagem
            );
            return $this;
        }
        $this->setarRetorno(
            false,
            'O valor <strong>' . $valor . '</strong> é difrente do valor comparado <strong>'
                . $comparacao . '</strong>.' . $mensagem
        );
        return $this;
    }

    /**
     * Verifica se o valor é diferente do valor comparado
     *
     * @param mixed $valor          Valor a comparar
     * @param mixed $comparacao     Valor a ser comparado
     */
    protected function checkDiferente($valor, $comparacao)
    {
        if ($valor != $comparacao) {
            $this->setarRetorno(
                true,
                'O valor <strong>' . $valor . '</strong> é difrente do valor comparado <strong>'
                    . $comparacao . '</strong>.'
            );
            return $this;
        }
        $this->setarRetorno(
            false,
            'O valor <strong>' . $valor . '</strong> é igual ao valor comparado <strong>' . $comparacao . '</strong>.'
        );
        return $this;
    }
    /**
     * Verifica se o valor é vazio
     *
     * @param mixed $valor          Valor a validar
     */
    protected function checkVazio($valor)
    {
        if (!vazio($valor)) {
            $this->setarRetorno(false, 'O valor <strong>' . $valor . '</strong> não está vazio.');
            return $this;
        }
        $this->setarRetorno(true, 'O valor <strong>' . $valor . '</strong> é vazio.');
        return $this;
    }
    /**
     * Verifica se o valor não é vazio
     *
     * @param mixed $valor          Valor a validar
     */
    protected function checkNaoVazio($valor)
    {
        if (!vazio($valor)) {
            $this->setarRetorno(true, 'O valor <strong>' . $valor . '</strong> não está vazio.');
            return $this;
        }
        $this->setarRetorno(false, 'O valor <strong>' . $valor . '</strong> é vazio.');
        return $this;
    }


    /*
    |--------------------------------------------------------------------------
    | CHECK DO ROBO E CURL
    |--------------------------------------------------------------------------
    */
    /**
     * Verifica se o status é igual ao enviado
     *
     * @param int   $status     Status para comparar
     * @param bool  $igual      Se é igual ou diferente do valor comparado
     */
    protected function checkStatus(int $status, bool $igual = true)
    {
        $checkStatus = $this->checkCurl ? $this->Curl->status() : $this->Robo->status();
        $texto = $this->checkCurl ? 'da resposta' : 'do robo';
        if ($checkStatus != $status && $igual) {
            $this->setarRetorno(
                false,
                '
                    Status <strong>' . $texto . '</strong> deveria ser <strong>' . $status . '</strong>
                    mas foi retornado <strong>' . $checkStatus . '</strong>.
                '
            );
        } elseif ($checkStatus == $status && !$igual) {
            $this->setarRetorno(
                false,
                'Status <strong>' . $texto . '</strong> não poderia ser igual a <strong>' . $status . '</strong>.'
            );
        } elseif ($checkStatus == $status && $igual) {
            $this->setarRetorno(
                true,
                'Status <strong>' . $texto . '</strong> é igual a <strong>' . $status . '</strong>.'
            );
        } elseif ($checkStatus != $status && !$igual) {
            $this->setarRetorno(
                true,
                'Status <strong>' . $texto . '</strong> é diferente de <strong>' . $status . '</strong>.'
            );
        }
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK CURL
    |--------------------------------------------------------------------------
    */
    /**
     * Verifica se o indice existe
     *
     * @param string $indice Indice que deseja verificar
     */
    protected function checkIndiceExiste(string $indice)
    {
        $campo = explode('.', $indice);
        $valorTemporario = $this->Curl->array();
        foreach ($campo as $item) {
            if (!array_key_exists($item, $valorTemporario)) {
                $this->setarRetorno(false, 'A resposta não contém o índice <strong>' . $indice . '</strong>.');
                return $this;
            }
            $valorTemporario = $valorTemporario[$item];
        }
        $this->setarRetorno(true, 'A resposta contém o índice <strong>' . $indice . '</strong>.');
        return $this;
    }

    /**
     * Verifica se o indice não existe
     *
     * @param string $indice Indice que deseja verificar
     */
    protected function checkIndiceNaoExiste(string $indice)
    {
        $campo = explode('.', $indice);
        $valorTemporario = $this->Curl->array();
        foreach ($campo as $item) {
            if (!array_key_exists($item, $valorTemporario)) {
                $this->setarRetorno(true, 'A resposta não contém o índice <strong>' . $indice . '</strong>.');
                return $this;
            }
            $valorTemporario = $valorTemporario[$item];
        }
        $this->setarRetorno(false, 'A resposta contém o índice <strong>' . $indice . '</strong>.');
        return $this;
    }

    /**
     * Verifica se a resposta da requisição ->dado é igual a array enviado
     *
     * @param array     $array  Array que deve ser comparado
     * @param bool      $igual  Se for false, só valida os dados que tem nos 2 arrais
     */
    protected function checkRespostaDadoIgual(array $array, bool $igual = true, array $crypt = [])
    {
        $resposta = $this->Curl->array();
        if (existeErro($resposta, 'dado')) {
            $this->setarRetorno(false, 'A resposta da requisição não tem o dado para validar.');
            return $this;
        }
        $dado = $resposta['dado'];
        $erro = false;
        foreach ($dado as $ind => $valor) {
            $existe = array_key_exists($ind, $array);
            if (!$existe && $igual) {
                $erro = true;
                $this->setarRetorno(false, 'Não existe o índice <strong>' . $ind . '</strong> na resposta.');
                continue;
            } elseif (!$existe) {
                continue;
            }

            $valor = in_array($ind, $crypt) ? $this->cryptDecode($valor) : $valor;
            $valorComparacao = in_array($ind, $crypt) ? $this->cryptDecode($array[$ind]) : $array[$ind];
            if ($valor != $valorComparacao) {
                $valor = is_array($valor) ? jsonEncode($valor) : $valor;
                $valorComparacao = is_array($valorComparacao) ? jsonEncode($valorComparacao) : $valorComparacao;
                $erro = true;
                $this->setarRetorno(
                    false,
                    'O valor do índice <strong>' . $ind . '</strong> deveria ser <strong>'
                        . $valorComparacao . '</strong> mas foi <strong>' . $valor . '</strong>.'
                );
                continue;
            }
        }
        if (!$erro) {
            $this->setarRetorno(true, 'A resposta é igual ao array enviado para comparar.');
        }

        return $this;
    }

    /**
     * Verifica se o indice é igual ao valor passado
     *
     * @param string    $indice     Indice que deve ser validado
     * @param mixed     $valor      Valor que o indice deve retornar
     */
    protected function checkIndiceIgual(string $indice, $valor)
    {
        $campo = explode('.', str_replace('->', '.', $indice));
        $valorTemporario = $this->Curl->array();

        foreach ($campo as $item) {
            if (!array_key_exists($item, $valorTemporario)) {
                $this->setarRetorno(false, 'Não foi possível validar se o índice <strong>'
                    . $indice . '</strong> é igual a <strong>' . $valor . '</strong> porque o índice não existe.');
                return $this;
            }
            $valorTemporario = $valorTemporario[$item];
        }

        $valor = is_array($valor) || is_object($valor) ? jsonEncode($valor) : $valor;
        $valorTemporario = is_array($valorTemporario) || is_object($valorTemporario)
            ? jsonEncode($valorTemporario) : strip_tags($valorTemporario);

        if ($valor != $valorTemporario) {
            $this->setarRetorno(false, 'O índice <strong>' . $indice . '</strong> deveria ter o valor <strong>'
                . $valor . '</strong> mas foi encontrado o valor <strong>' . $valorTemporario . '</strong>.');
            return $this;
        }
        $this->setarRetorno(
            true,
            'O índice <strong>' . $indice . '</strong> é igual a <strong>' . $valor . '</strong>.'
        );
        return $this;
    }

    /**
     * Verifica se o indice é diferente ao valor passado
     *
     * @param string    $indice     Indice que deve ser validado
     * @param mixed     $valor      Valor que o indice deve retornar
     */
    protected function checkIndiceDiferente(string $indice, $valor)
    {
        $campo = explode('.', str_replace('->', '.', $indice));
        $valorTemporario = $this->Curl->array();
        foreach ($campo as $item) {
            if (!array_key_exists($item, $valorTemporario)) {
                $this->setarRetorno(false, 'Não foi possível validar se o índice <strong>'
                    . $indice . '</strong> é diferente a <strong>' . $valor . '</strong> porque o índice não existe.');
                return $this;
            }
            $valorTemporario = $valorTemporario[$item];
        }

        $valor = is_array($valor) || is_object($valor) ? jsonEncode($valor) : $valor;
        $valorTemporario = is_array($valorTemporario) || is_object($valorTemporario)
            ? jsonEncode($valorTemporario) : $valorTemporario;

        if ($valor == $valorTemporario) {
            $this->setarRetorno(
                false,
                'O índice <strong>' . $indice . '</strong> não é diferente de <strong>' . $valor . '</strong>.'
            );
            return $this;
        }
        $this->setarRetorno(true, 'O índice <strong>' . $indice . '</strong> veio <strong>'
            . $valorTemporario . '</strong> e é diferente de <strong>' . $valor . '</strong>.');

        return $this;
    }

    /**
     * Valida se o indice é do tipo
     *
     * @param string $indice    Indice que deseja validar
     * @param string $tipo      Tipo podendo ser array, object, string, numero, booleano ou inteiro
     */
    private function checkTipoIndice(string $indice, string $tipo)
    {
        if (!in_array($tipo, ['array', 'object', 'string', 'numero', 'booleano', 'inteiro'])) {
            $this->setarRetorno(
                false,
                'Não foi possível validar o tipo do índice <strong>'
                    . $indice . '</strong> porque o tipo não é um valor aceito.'
            );
        }

        $campo = explode('.', str_replace('->', '.', $indice));
        $valor = $this->Curl->array();
        foreach ($campo as $item) {
            if (!array_key_exists($item, $valor)) {
                $this->setarRetorno(
                    false,
                    'Não foi possível validar o tipo do índice <strong>' . $indice . '</strong> porque ele não existe.'
                );
                return $this;
            }
            $valor = $valor[$item];
        }

        if (
            ($tipo == 'array' && is_array($valor)) ||
            ($tipo == 'object' && is_object($valor)) ||
            ($tipo == 'string' && is_string($valor)) ||
            ($tipo == 'numero' && is_numeric($valor)) ||
            ($tipo == 'booleano' && is_bool($valor)) ||
            ($tipo == 'inteiro' && is_int($valor))
        ) {
            $this->setarRetorno(
                false,
                'O índice <strong>' . $indice . '</strong> é do tipo <strong>' . $tipo . '</strong>.'
            );
            return $this;
        }
        $this->setarRetorno(
            true,
            'O índice <strong>' . $indice . '</strong> não é do tipo <strong>' . $tipo . '</strong>.'
        );
        return $this;
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK SÓ DO ROBO
    |--------------------------------------------------------------------------
    */

    /**
     * Verifica se a URL do robo é igual ou diferente da enviada
     *
     * @param string    $link   Link que deve ser comparado
     * @param bool      $igual  Se é igual ou diferente do valor comparado
     */
    protected function checkUrl(string $link, bool $igual = true): self
    {
        $roboUrl = $this->Robo->url();
        if ($roboUrl != $link && $igual) {
            $this->setarRetorno(false, 'A URL do Robo deveria ser ' . $link . ' mas foi retornado ' . $roboUrl . '.');
        } elseif ($roboUrl == $link && !$igual) {
            $this->setarRetorno(false, 'A URL do Robo não poderia ser igual a ' . $link . '.');
        } elseif ($roboUrl == $link && $igual) {
            $this->setarRetorno(true, 'A URL do Robo é igual a ' . $link . '.');
        } elseif ($roboUrl != $link && !$igual) {
            $this->setarRetorno(true, 'A URL do Robo é diferente de ' . $link . '.');
        }
        return $this;
    }

    /**
     * Verifica se o texto exista na página da requisição do robo
     *
     * @param string $texto     Texto que tem que existe na request
     */
    protected function checkExisteTexto(string $texto)
    {
        if ($this->Robo->textoExiste($texto)) {
            $this->setarRetorno(true, 'Existe o texto <strong>' . $texto . '</strong> na resposta do Robo.');
            return $this;
        }
        $this->setarRetorno(false, 'Não existe o texto <strong>' . $texto . '</strong> na resposta do Robo.');
        return $this;
    }
    /*
    |--------------------------------------------------------------------------
    | PUBLIC
    |--------------------------------------------------------------------------
    */
    public function test()
    {
        $retorno = (object) [
            'todos' => $this->todos,
            'passou' => $this->passou,
            'falhou' => $this->falhou,
            'status' => $this->checkCurl ? $this->Curl->status() : $this->Robo->status(),
            'metodo' => $this->checkCurl ? $this->Curl->metodo() : $this->Robo->metodo(),
            'url' => $this->checkCurl ? $this->Curl->url() : $this->Robo->url(),
            'curl' => $this->checkCurl ? $this->Curl : false,
            'robo' => $this->checkRobo ? $this->Robo : false
        ];
        $this->todos = [];
        $this->passou = [];
        $this->falhou = [];
        return $retorno;
    }
    private function setarRetorno($passou, $mensagem)
    {
        $resposta = (object)[
            'mensagem' => $mensagem,
            'status' => $passou ? 'passou' : 'falhou'
        ];
        $this->todos[] = $resposta;
        if ($passou) {
            $this->passou[] = $resposta;
        }
        if (!$passou) {
            $this->falhou[] = $resposta;
        }
    }

    protected function curl(string $url)
    {
        $this->checkCurl = true;
        $this->checkRobo = false;

        $this->Curl = new Curl($url);
        return $this;
    }
    /**
     * Seta um API para Curl
     *
     * @param null|string   $scope      Escopo da ação que deseja criar
     * @param
     */
    protected function api(string $scope)
    {
        $this->checkCurl = true;
        $this->checkRobo = false;

        $this->Curl = new Api($scope);
        return $this;
    }

    /**
     * Adicionar tabela para resetar
     *
     * @param   string  $tabela     Nome da tabela que dese ser resetada
     */
    protected function tabela(string $tabela)
    {
        $path = ROOT . '/database/' . $tabela;
        if (!file_exists($path . '/base.php')) {
            return $this;
        }
        $arquivo = listarArquivoDiretorio($path, inicio: 'tabela:');
        $diretorio = array_key_exists(0, $arquivo) ? str_replace('tabela:', '', $arquivo[0]) : $tabela;

        $this->tabelaResetar[] = [$path, $diretorio, $tabela];
        return $this;
    }
    protected function resetar()
    {
        $lista = $this->tabelaResetar;
        if (!$lista) {
            return;
        }
        foreach ($lista as $r) {
            $Database = include $r[0] . '/base.php';
            $Database->tabela = $r[1];
            $Database->diretorio = $r[2];

            $Database->sistemaDeletar();
            $Database->sistemaCriar();
        }
        $Database->sistemaRelacionar();
    }
}
