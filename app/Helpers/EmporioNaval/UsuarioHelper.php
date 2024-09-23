<?php

namespace App\Helpers\EmporioNaval;

use Erro\Erro;
use Erro\Excecao;
use Helpers\CurlHelper;

class UsuarioHelper
{
    private string $link;

    /**
     * @throws Erro
     */
    public function __construct()
    {
        $envsNaval = [
            'EMPORIO_NAVAL_LINK' => env('EMPORIO_NAVAL_LINK')
        ];

        foreach ($envsNaval as $index => $value) {
            if (empty($value)) {
                throw new Erro(
                    "Variável de ambiente $index não foi seta ou está vazia",
                    'Variáveis de Ambiente',
                    "A variável de ambiente $index deve ser preenchida corretamente"
                );
            } elseif (!is_string($value)) {
                throw new Erro(
                    "Esperavamos um valor do tipo STRING na variável de ambiente $index",
                    'Tipagem da variável de ambiente',
                    "A variável de ambiente $index deve ser do tipo STRING"
                );
            } elseif (!filter_var($value, FILTER_VALIDATE_URL)) {
                throw new Erro(
                    "Esperavamos um valor do tipo LINK na variável de ambiente $index",
                    'Tipagem da variável de ambiente',
                    "A variável de ambiente $index deve ser do tipo LINK válido"
                );
            }
        }

        $this->link = env('EMPORIO_NAVAL_LINK');
    }

    /**
     * @param string $cpf   CPF do Usuário
     * @param string $senha Senha do Usuário
     *
     * @return array
     * @throws Excecao
     */
    public function buscarUsuario(string $cpf, string $senha): array
    {
        $resposta = (new CurlHelper($this->link))
            ->header([
                'Content-Type'   => 'application/x-www-form-urlencoded',
                'Content-Length' => '0'
            ])
            ->parametro([
                'cpf' => $cpf,
                'pwd' => $senha
            ])
            ->post('/Login/bycpf');

        $status = $resposta->status();
        $usuario = $resposta->array();
        if ($status != 200 || !is_array($usuario)) {
            return [];
        }
        return $usuario;
    }
}
