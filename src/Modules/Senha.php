<?php

namespace Modules;

use Modules\Trait\ValidarTrait;

final class Senha implements ModuleInterface
{
    use ValidarTrait;

    private ?string $senha = '';
    private bool $mesmaSenha = false;
    private bool $mudouSenha = false;

    public function __toString()
    {
        return '';
    }

    /**
     * Pega o valor padrão independente do tipo de modulo
     */
    public function valor()
    {
        return '';
    }

    // doc
    /**
     * Modulo para senha
     *
     * @param null|string   $senha          Senha do usuário
     * @param int           $forca          Força da senha de 1 a 4, sendo 4 mais forte
     */
    public function __construct(
        ?string $senha,
        private int $forca = 4
    ) {
        $algoritimo = is_string($senha) ? password_get_info($senha) : [];
        if (empty($senha)) {
            $this->senha = '';
            $this->vazio = true;
            $this->valido = false;
            return;
        } elseif (array_key_exists('algoName', $algoritimo) && !empty($algoritimo['algoName']) && $algoritimo['algoName'] != 'unknown') {
            $this->senha = $senha;
            $this->vazio = false;
            $this->valido = true;
            return;
        } elseif (!preg_match($this->pegarExpressaoRegular(), $senha)) {
            $this->senha = '';
            $this->valido = false;
            return;
        }
        $this->senha = password_hash($senha, PASSWORD_DEFAULT, ['cost' => 11]);
        $this->mudouSenha = true;
    }

    private function pegarExpressaoRegular(): string
    {
        if ($this->forca == 4) {
            return '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[$*&@#\_])[0-9a-zA-Z$*&@#\_]{8,}$/';
        } elseif ($this->forca == 3) {
            return '/^(?=.*\d)(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[$*&@#\_])[0-9a-zA-Z$*&@#\_]{8,}$/';
        } elseif ($this->forca == 2) {
            return '/^(?=.*\d)(?=.*[a-zA-Z])(?=.*[0-9])[0-9a-zA-Z$*&@#\_]{8,}$/';
        } elseif ($this->forca == 1) {
            return '/^(?=.*\d)[0-9a-zA-Z$*&@#\_]{8,}$/';
        }
    }

    // doc
    /**
     * Pega a mensagem de erro se a senha não for válida
     *
     * @return string Mensagem de erro
     */
    public function mensagem(): string
    {
        if ($this->valido) {
            return '';
        } elseif ($this->forca == 4) {
            return 'Sua senha deve ter pelo menos 1 letra maiuscula, 1 letra minúscula, 1 número, 1 caracter especial e no mínimo 8 digitos.';
        } elseif ($this->forca == 3) {
            return 'Sua senha deve ter pelo menos 1 letra, 1 número, 1 caracter especial e no mínimo 8 digitos.';
        } elseif ($this->forca == 2) {
            return 'Sua senha deve ter pelo menos 1 letra, 1 número e no mínimo 8 digitos.';
        } elseif ($this->forca == 1) {
            return 'Sua senha deve ter pelo menos 8 digitos.';
        }
        return 'Sua senha não está em um formato válido.';
    }

    // doc
    /**
     * Valida a senha com o salt
     *
     * @param string    $senha       A senha que o usuário digitou para poder comparar com o salt
     * @param bool                   Retorna true para sim e false para não
     */
    public function validarSenha(string $senha): bool
    {
        return !empty($senha) && !empty($this->senha) && password_verify($senha, $this->senha);
    }

    // doc
    /**
     * Salva uma nova senha para o usuário
     *
     * @param   string  $senha  Nova senha
     * @return  void
     */
    public function mudarSenha(string $senha): void
    {
        if (empty($senha)) {
            $this->senha = '';
            $this->vazio = true;
            $this->valido = false;
            return;
        } elseif (!preg_match($this->pegarExpressaoRegular(), $senha)) {
            $this->senha = '';
            $this->vazio = false;
            $this->valido = false;
            return;
        }
        $this->mudouSenha = true;
        $this->mesmaSenha = !empty($this->senha) ? password_verify($senha, $this->senha) : false;
        $this->vazio = false;
        $this->valido = true;
        $this->senha = password_hash($senha, PASSWORD_DEFAULT, ['cost' => 11]);
    }

    // doc
    /**
     * Verifica se a senha enviada é a mesma senha atual
     *
     * @return bool Retorna true para sim ou false para não
     */
    public function mesmaSenha(): bool
    {
        return $this->mesmaSenha;
    }

    // doc
    /**
     * Verifica se a senha foi alterada
     *
     * @return bool Retorna true para sim e false pra não
     */
    public function mudouSenha(): bool
    {
        return $this->mudouSenha;
    }

    /**
     * Pega o salt da senha. NÃO USAR, ISSO É APENAS PARA O SISTEMA
     */
    public function senha(): string
    {
        return $this->senha;
    }
}
