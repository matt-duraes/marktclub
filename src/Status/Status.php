<?php

namespace Status;

use Helpers\TextoHelper;

abstract class Status implements StatusInterface
{
    protected null|string|int $valor = null;
    private array $indiceNumero = [];
    private array $indiceNome = [];
    private array $numeroNome = [];
    protected bool $test = false;

    public function __toString()
    {
        $nome = $this->nome();
        return is_int($nome) || is_string($nome) ? $nome : '';
    }

    //doc
    /**
     * Construtor para um Status
     *
     * @param null|array $lista   Lista de valores no padrao indice => Nome
     * @param null|array $cor     Lista de cores no padrão indice => Cor
     * @param null|array $numero  Número que deve ser salvo no banco, caso não passe, será automatico
     * @param null|array $empresa Quando o valor muda dependendo da empresa
     */
    public function __construct(
        protected ?array $lista = null,
        protected ?array $cor = null,
        ?array $numero = null,
        protected ?array $empresa = null,
        protected bool $geral = false
    ) {
        $slugEmpresa = $this->pegarSlugEmpresa();

        if($empresa && $geral) {
            $empresa = $this->agruparEmpresa($empresa);
            $this->lista = $empresa['lista'] ?? [];
            $this->cor = $empresa['cor'] ?? null;
            $numero = $empresa['numero'] ?? null;
        } elseif ($empresa && array_key_exists($slugEmpresa, $empresa)) {
            $this->lista = $empresa[$slugEmpresa]['lista'] ?? [];
            $this->cor = $empresa[$slugEmpresa]['cor'] ?? null;
            $numero = $empresa[$slugEmpresa]['numero'] ?? null;
        } elseif ($empresa && array_key_exists('geral', $empresa)) {
            $this->lista = $empresa['geral']['lista'] ?? [];
            $this->cor = $empresa['geral']['cor'] ?? null;
            $numero = $empresa['geral']['numero'] ?? null;
        } elseif ($empresa && array_key_exists(0, $empresa)) {
            $this->lista = $empresa[0]['lista'] ?? [];
            $this->cor = $empresa[0]['cor'] ?? null;
            $numero = $empresa[0]['numero'] ?? null;
        } elseif ($empresa) {
            $this->lista = [];
            $this->cor = null;
            $numero = null;
        }

        $indice = array_key_exists(0, $this->lista) ?
            $this->criarSlug($this->lista) :
            $this->criarArray(array_keys($this->lista));

        $nome = $this->criarArray(array_values($this->lista));
        $numero = empty($numero) ? array_keys($indice) : $numero;
        if (count($indice) != count($nome) || count($indice) != count($numero)) {
            mensagemErro('Erro!', 'O número de valores das listas não batem.');
        }

        $this->indiceNumero = array_combine($indice, $numero);
        $this->indiceNome = array_combine($indice, $nome);
        $this->numeroNome = array_combine($numero, $nome);
    }

    private function agruparEmpresa(array $empresa)
    {
        $retorno = [];
        foreach($empresa as $r) {
            if(array_key_exists('lista', $r)) {
                if(array_key_exists('lista', $retorno)) {
                    $retorno['lista'] = array_merge($retorno['lista'], $r['lista']);
                }else {
                    $retorno['lista'] = $r['lista'];
                }
            }
            if(array_key_exists('numero', $r)) {
                if(array_key_exists('numero', $retorno)) {
                    $retorno['numero'] = array_unique(array_merge($retorno['numero'], $r['numero']));
                }else {
                    $retorno['numero'] = $r['numero'];
                }
            }
            if(array_key_exists('cor', $r)) {
                if(array_key_exists('cor', $retorno)) {
                    $retorno['cor'] = array_merge($retorno['cor'], $r['cor']);
                }else {
                    $retorno['cor'] = $r['cor'];
                }
            }
        }
        return $retorno;
    }

    private function pegarSlugEmpresa()
    {
        if (sessaoExiste('EMPRESA')) {
            return sessao('EMPRESA.slug');
        } elseif (defined('TOKEN') && array_key_exists('empresa', TOKEN)) {
            return TOKEN['empresa']->slug;
        }
        return 'geral';
    }

    private function criarSlug(array $lista): array
    {
        $Texto = new TextoHelper();
        $i = 1;
        $array = [];
        foreach ($lista as $val) {
            $array[$i] = $Texto->valor($val)->slug('_')->r();
            $i++;
        }
        return $array;
    }

    private function criarArray(array $lista): array
    {
        $i = 1;
        $array = [];
        foreach ($lista as $val) {
            $array[$i] = $val;
            $i++;
        }
        return $array;
    }

    // doc
    /**
     * Pega um array com a lista de valores válidos no formato indice => nome
     *
     * @param  null|string $titulo Título para ficar no primeiro valor do array tendo o indice vazio: "" => $titulo
     * @return array
     */
    public function select(?string $titulo = null): array
    {
        if (!empty($titulo)) {
            return ['' => $titulo] + $this->indiceNome;
        }
        return $this->indiceNome;
    }

    /**
     * Pega a lista de cores
     *
     * @return array
     */
    public function cor(): array
    {
        $retorno = [];
        foreach ($this->cor as $indice => $cor) {
            $retorno[$indice] = [
                'indice' => $indice,
                'nome'   => $this->lista[$indice] ?? '',
                'cor'    => $cor
            ];
        }
        return $retorno;
    }

    /**
     * Pega a lista de números
     *
     * @return array
     */
    public function listarNumero(): array
    {
        return array_values($this->indiceNumero);
    }

    // doc
    /**
     * Pega o valor do número do valor selecionado
     *
     * @param   null|string|int     Valor caso queira ignorar o valor geral do status
     * @return null|int Retorna null caso o valor seja inválido ou o int do valor
     */
    public function numero(null|string|int $valor = null): ?int
    {
        $valor = !empty($valor) ? $valor : $this->valor;
        if (!$this->valido($valor)) {
            return null;
        } elseif (is_numeric($valor)) {
            return $valor;
        }
        return $this->indiceNumero[$valor];
    }

    // doc
    /**
     * Pega o nome do valor selecionado
     *
     * @param   null|string|int     Valor caso queira ignorar o valor geral do status
     * @return null|int Retorna null caso o valor seja inválido ou a string do nome
     */
    public function nome(null|string|int $valor = null): ?string
    {
        $valor = !empty($valor) ? $valor : $this->valor;
        if (!$this->valido($valor)) {
            return null;
        }
        if (is_numeric($valor)) {
            return $this->numeroNome[$valor] ?? '';
        }
        return $this->indiceNome[$valor] ?? '';
    }

    // doc
    /**
     * Pega o valor do indice do valor selecionado
     *
     * @param   null|string|int     Valor caso queira ignorar o valor geral do status
     * @return null|int Retorna null caso o valor seja inválido ou o numero o valor
     */
    public function indice(null|string|int $valor = null): ?string
    {
        $valor = !empty($valor) ? $valor : $this->valor;
        if (!$this->valido($valor)) {
            return '';
        } elseif (!is_numeric($valor)) {
            return $valor;
        }
        return array_flip($this->indiceNumero)[$valor];
    }

    // doc
    /**
     * Valida se o indice/valor é valido
     *
     * @return bool retorna true se o valor seja válido
     */
    public function valido(null|string|int $valor = null): bool
    {
        $valor = !empty($valor) ? $valor : $this->valor;
        return !is_null($valor) && in_array(
            $valor,
            array_merge(array_keys($this->indiceNumero), array_values($this->indiceNumero))
        );
    }

    // doc
    /**
     * Valida se o valor está vazio
     *
     * @return bool retorna true se o valor seja válido
     */
    public function vazio(): bool
    {
        return empty($this->valor);
    }
}
