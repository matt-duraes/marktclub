<?php

namespace App\Models\Api\LoginClube\Usuario;

use Erro\Excecao;
use App\Models\Api\LoginClube\UsuarioEntity;

final class BancoModel implements LoginClubeInterface
{
    private UsuarioEntity $Usuario;

    public function fazerLogin(string $login, string $senha, int $idEmpresa): UsuarioEntity
    {
        $this->buscarUsuario($login, $idEmpresa);
        $this->validarSenha($senha);
        $this->validarStatus();

        return $this->Usuario;
    }

    private function buscarUsuario($login, $idEmpresa)
    {
        $Usuario = new UsuarioEntity();

        try {
            $Usuario->buscar($this->pegarWhere($login, $idEmpresa));
            usleep(rand(300000, 800000));
        } catch (\Throwable $e) {
            usleep(rand(500000, 1000000));
            $this->loginSenhaInvalido();
            return;
        }

        $this->Usuario = $Usuario;
    }
    private function pegarWhere($login, $idEmpresa)
    {
        $documento = soNumero($login);
        if (validarCpf($documento)) {
            return [
                'OR',
                [
                    ['documento', $documento],
                    ['empresa', $idEmpresa]
                ],
                [
                    ['documento', $documento],
                    ['empresa', 1],
                    ['tipo', 3]
                ]
            ];
        }
        return [
            'OR',
            [
                ['empresa', $idEmpresa],
                [
                    'OR',
                    ['email_pessoal', $login],
                    ['email_trabalho', $login]
                ]
            ],
            [
                [
                    'OR',
                    ['email_pessoal', $login],
                    ['email_trabalho', $login]
                ],
                ['empresa', 1],
                ['tipo', 3]
            ]
        ];
    }

    private function validarSenha($senha): void
    {
        if (!$this->Usuario->senha->validarSenha($senha)) {
            $this->loginSenhaInvalido();
        }
    }

    private function validarStatus(): void
    {
        $status = $this->Usuario->status->indice();
        if (in_array($status, ['indicacao', 'ativo'])) {
            return;
        } elseif ($status == 'inativo') {
            throw new Excecao(
                titulo: 'Usuário inativo!',
                mensagem: 'Seu usuário está inativo, para continuar, ative seu usuário.',
                lista: [
                    'acao' => 'inativo'
                ],
                codigo: 1000
            );
        } elseif ($status == 'bloqueado') {
            mensagemErro(
                'Usuário bloqueado!',
                'Seu usuário está bloqueado, para desbloqueá-lo, entre em contato com o atendimento.'
            );
        }
        mensagemErro(
            'Usuário inválido!',
            'Ocorreu um erro ao fazer seu login, por favor, tente novamente.'
        );
    }

    private function loginSenhaInvalido()
    {
        mensagemErro(
            'Login inválido!',
            'Usuário não encontrado, o seu login e/ou senha estão incorretos.',
            status: 403
        );
    }
}
