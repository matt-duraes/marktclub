<?php

namespace ORM\Trait;

use PDO;
use Erro\Excecao;
use Modules\Senha;
use ReflectionObject;
use ReflectionProperty;
use Helpers\UploadHelper;
use Helpers\ValidarHelper;
use Status\StatusInterface;
use Modules\ModuleInterface;

trait ValidarTrait
{
    /**
     * Valida se o valor já existe no banco de dados
     *
     * @param  string      $campo
     * @param  string      $mensagem
     * @param  string|null $titulo
     * @return bool
     */
    public function validarCampoDuplicado(string $campo, string $mensagem, ?string $titulo = null, mixed $valor = null): void
    {
        if (empty($valor) && !$this->propriedadeExiste($campo)) {
            return;
        }

        if (empty($valor)) {
            $valor = $this->$campo;
            $valor = $valor instanceof ModuleInterface || $valor instanceof StatusInterface ?
                $valor->banco() : $valor;
        }

        if (is_string($valor)) {
            $valor = [$valor];
        }
        $whereCampo = [];
        $i = 0;
        foreach ($valor as $ind) {
            $whereCampo[':' . $i . '_' . $campo] = $ind;
            $i++;
        }

        $where = "`{$campo}` IN(" . implode(', ', array_keys($whereCampo)) . ')';
        if ($this->ormEntityExiste) {
            $where .= ' AND `id` != :id';
        }

        $DB = $this->ormLeitura ? $this->ormDBLeitura : $this->ormDBEscrita;
        $sql = $DB->prepare("SELECT `id` FROM `{$this->ormTabela}` WHERE {$where}");
        foreach ($whereCampo as $ind => $val) {
            $sql->bindValue($ind, $val);
        }
        if ($this->ormEntityExiste) {
            $sql->bindValue(':id', $this->prop('id'), PDO::PARAM_STR);
        }

        try {
            $run = $sql->execute();
        } catch (\Throwable $e) {
            $this->mensagemCampoDuplicado($titulo, $mensagem);
            return;
        }

        if (!$run || $sql->fetch()) {
            $this->mensagemCampoDuplicado($titulo, $mensagem);
            return ;
        }
    }

    private function mensagemCampoDuplicado(?string $titulo, string $mensagem)
    {
        $mensagem = str_starts_with($mensagem, '!')
            ? substr($mensagem, 1) : 'O valor do campo ' . $mensagem . ' já existe.';
        $titulo = !empty($titulo) ? $titulo : 'Campo duplicado!';
        mensagemErro($titulo, $mensagem);
    }

    private function ormValidarDadoParaSalvar(array $dado, array $coluna): array
    {
        foreach ($dado as $ind => $valor) {
            if (!array_key_exists($ind, $coluna)) {
                throw new Excecao(
                    titulo: 'Erro no banco!',
                    mensagem: 'A coluna ' . $ind . ' não existe no banco de dado.'
                );
            }

            $titulo = !empty($coluna[$ind]->titulo) ? $coluna[$ind]->titulo : $ind;
            if ($valor instanceof UploadHelper) {
                $this->ormArquivoSalvar[] = $valor;
                $valor = $valor->nome();
            } elseif ($valor instanceof Senha && empty($valor->senha())) {
                continue;
            } elseif ($valor instanceof ModuleInterface) {
                $valor = $this->ormPegarValorModule($valor);
            } elseif ($valor instanceof StatusInterface) {
                $valor = $valor->numero();
            }

            if (
                !empty($valor) && in_array($coluna[$ind]->tipo, ['bigint', 'int', 'mediumint', 'smallint', 'tinyint'])
            ) {
                $valor = (int) preg_replace('/[^0-9]/', '', $valor);
            } elseif (!empty($valor) && $coluna[$ind]->tipo == 'date') {
                $valor = date('Y-m-d', strtotime(str_replace('/', '-', $valor)));
            } elseif (!empty($valor) && $coluna[$ind]->tipo == 'datetime') {
                $valor = date('Y-m-d H:i:s', strtotime(str_replace('/', '-', $valor)));
            } elseif (!empty($valor) && $coluna[$ind]->tipo == 'time') {
                $valor = date('H:i:s', strtotime(str_replace('/', '-', $valor)));
            } elseif (!empty($valor) && is_array($valor)) {
                $valor = json_encode($valor);
            }

            if (
                $coluna[$ind]->obrigatorio == true && empty($valor) ||
                (
                    ($coluna[$ind]->tipo == 'date' && preg_replace('/[^0-9]/', '', $valor) == '00000000') ||
                    ($coluna[$ind]->tipo == 'datetime' && preg_replace('/[^0-9]/', '', $valor) == '00000000000000') ||
                    ($coluna[$ind]->tipo == 'time' && preg_replace('/[^0-9]/', '', $valor) == '000000')
                )
            ) {
                throw new Excecao(titulo: 'Campo obrigatório!', mensagem: 'O campo ' . $titulo . ' é obrigatório.');
            } elseif (
                is_numeric($coluna[$ind]->tamanho) &&
                $coluna[$ind]->tamanho > 0 &&
                is_string($valor) &&
                mb_strlen($valor, 'UTF-8') > $coluna[$ind]->tamanho
            ) {
                $diferenca = mb_strlen($valor, 'UTF-8') - $coluna[$ind]->tamanho;
                throw new Excecao(
                    titulo: 'Tamanho maior que o permitido!',
                    mensagem: 'O campo ' . $titulo . ' tem ' . $diferenca . ' caracteres a mais que o permitido.'
                );
            } elseif (
                !empty($valor) &&
                in_array($coluna[$ind]->tipo, ['bigint', 'int', 'mediumint', 'smallint', 'tinyint']) &&
                !preg_match('/^\-{0,1}[0-9]+$/', $valor)
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um número inteiro.'
                );
            } elseif (
                $coluna[$ind]->validar &&
                in_array('cep', $coluna[$ind]->validar) &&
                !empty($valor) &&
                !preg_match('/^[0-9]{2}\.{0,1}[0-9]{3}\-{0,1}[0-9]{3}$/', $valor)
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um CEP válido.'
                );
            } elseif (
                !empty($valor) &&
                $coluna[$ind]->tipo == 'json' &&
                (!is_array(jsonDecode($valor, true)) && !is_array($valor))
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é uma string json.'
                );
            } elseif (
                !empty($valor) &&
                in_array('cpf', $coluna[$ind]->validar) &&
                !$this->validarCpf(str_pad($valor, 11, '0', STR_PAD_LEFT))
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um CPF válido.'
                );
            } elseif (
                !empty($valor) &&
                in_array('cnpj', $coluna[$ind]->validar) &&
                !$this->validarCnpj(str_pad($valor, 14, '0', STR_PAD_LEFT))
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um CNPJ válido.'
                );
            } elseif (
                !empty($valor) &&
                in_array('telefone', $coluna[$ind]->validar) &&
                !$this->validarTelefone($valor)
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um telefone válido.'
                );
            } elseif (
                !empty($valor) &&
                in_array('email', $coluna[$ind]->validar) &&
                !filter_var($valor, FILTER_VALIDATE_EMAIL)
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um e-mail válido.'
                );
            } elseif (
                !empty($valor) &&
                in_array('url', $coluna[$ind]->validar) &&
                !filter_var($valor, FILTER_VALIDATE_URL)
            ) {
                throw new Excecao(
                    titulo: 'Formato incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é uma url válida.'
                );
            } elseif (
                !empty($valor) &&
                in_array('positivo', $coluna[$ind]->validar) &&
                (!is_numeric($valor) ||
                    $valor < 0)
            ) {
                throw new Excecao(
                    titulo: 'Campo incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um valor positivo.'
                );
            } elseif (
                !empty($valor) &&
                in_array('negativo', $coluna[$ind]->validar) &&
                (!is_numeric($valor) ||
                    $valor >= 0)
            ) {
                throw new Excecao(
                    titulo: 'Campo incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um valor negativo.'
                );
            } elseif (
                !empty($valor) &&
                $coluna[$ind]->tipo == 'time' &&
                !preg_match('/^([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/', $valor)
            ) {
                throw new Excecao(
                    titulo: 'Campo incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não é um formato de hora válido.'
                );
            } elseif (
                !empty($valor) &&
                preg_replace('/[^0-9]/', '', $valor) != '00000000' &&
                $coluna[$ind]->tipo == 'date' &&
                (!preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $valor) &&
                    !preg_match('/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})$/', $valor))
            ) {
                throw new Excecao(
                    titulo: 'Campo incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não está no formato 00/00/0000.'
                );
            } elseif (
                !empty($valor) &&
                preg_replace('/[^0-9]/', '', $valor) != '00000000000000' &&
                $coluna[$ind]->tipo == 'datetime' &&
                (
                    // @codingStandardsIgnoreStart
                    !preg_match("/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/", $valor) &&
                    !preg_match("/^(0[1-9]|[1-2][0-9]|3[0-1])\/(0[1-9]|1[0-2])\/([0-9]{4})\ ([0-1][0-9]|2[0-3]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/", $valor)
                    // @codingStandardsIgnoreEnd
                )
            ) {
                throw new Excecao(
                    titulo: 'Campo incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não está no formato 00/00/0000 00:00:00.'
                );
            } elseif (
                !empty($valor) &&
                $coluna[$ind]->tipo == 'decimal' &&
                !preg_match("/^[0-9]*\.[0-9]{2}$/", $valor)
            ) {
                throw new Excecao(
                    titulo: 'Campo incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não está no formato 0.00.'
                );
            } elseif (
                !empty($valor) &&
                $coluna[$ind]->tipo == 'float' &&
                !filter_var($valor, FILTER_VALIDATE_FLOAT)
            ) {
                throw new Excecao(
                    titulo: 'Campo incorreto!',
                    mensagem: 'O campo ' . $titulo . ' não está no formato float.'
                );
            }

            $dado[$ind] = is_string($valor) ? trim($valor) : $valor;
        }
        return $dado;
    }

    private function validarCpf(string $cpf): bool
    {
        $cpf = preg_replace('/[^0-9]/', '', $cpf);
        if (strlen($cpf) != 11) {
            return false;
        }
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }
        return true;
    }

    private function validarCnpj(string $cnpj): bool
    {
        $cnpj = preg_replace('/[^0-9]/', '', $cnpj);
        if (strlen($cnpj) != 14) {
            return false;
        }
        if (preg_match('/(\d)\1{13}/', $cnpj)) {
            return false;
        }
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto)) {
            return false;
        }
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    private function validarTelefone($telefone)
    {
        $telefone = preg_replace('/[^0-9]/', '', $telefone);
        $quantidade = is_string($telefone) ? mb_strlen($telefone, 'UTF-8') : 0;
        return (
            ($quantidade == 8 && in_array(substr($telefone, 0, 4), ['4004', '4003', '3003'])) ||
            (
                $quantidade == 11 &&
                (in_array(substr($telefone, 0, 4), ['0800', '0300']) || substr($telefone, 2, 1) == 9)
            ) ||
            ($quantidade == 10 && substr($telefone, 2, 1) != 9));
    }

    private function validarData(string $data): bool
    {
        return preg_replace('/[^0-9]/', '', $data) != '00000000' &&
            preg_match('/^([0-9]{4})-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/', $data);
    }

    protected function ormValidarViaHelper(string $acao)
    {
        $dado = $this->ormSetReal;
        $reflect = new ReflectionObject($this);
        $propriedadePublica = $reflect->getProperties(ReflectionProperty::IS_PUBLIC);
        $chaves = array_merge(['id', 'uuid', 'cod'], array_keys($dado));
        foreach ($propriedadePublica as $r) {
            if (in_array($r->name, $chaves)) {
                continue;
            }
            $name = $r->name;
            if ($this->propriedadeExiste($name)) {
                $dado[$name] = $this->$name;
            }
        }

        $validacao = $this->ormValidarSalvar;
        if ($acao == 'insert' && !empty($this->ormValidarInsert)) {
            $validacao .= PHP_EOL . $this->ormValidarInsert;
        } elseif ($acao == 'update' && !empty($this->ormValidarUpdate)) {
            $validacao .= PHP_EOL . $this->ormValidarUpdate;
        }

        if (empty($validacao)) {
            return;
        }
        $explodeValidacao = explode(PHP_EOL, $validacao);
        foreach ($explodeValidacao as $linha) {
            $campoValidacao = trim(explode('|', $linha)[0]);
            if (!in_array($campoValidacao, $dado) && $this->propriedadeExiste($campoValidacao)) {
                $dado[$campoValidacao] = $this->$campoValidacao;
            }
        }

        (new ValidarHelper(dado: $dado))->validar($validacao);
    }
}
