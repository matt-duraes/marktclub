<?php

namespace Helpers;

use Erro\Excecao;

final class AuthHelper
{
    /**
     * Validar se usuário está logado
     *
     * @param string|null $local Qual o local está, por exemplo: site, painel, etc
     *
     * @throws Excecao
     */
    public function validar(string $local = null): bool
    {
        if (is_null($local)) {
            $local = mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        }

        $userAgent = md5($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');

        if (
            sessaoExiste('USUARIO')
            && sessaoExiste('AUTH_' . $local . '_' . $userAgent . '_HASH')
            && sessaoExiste('AUTH_' . $local . '_' . $userAgent)
            && sessao('AUTH_' . $local . '_' . $userAgent . '_HASH') == sessao('AUTH_' . $local . '_' . $userAgent)
        ) {
            return true;
        }

        return false;
    }

    /**
     * Gera a auth do usuário
     *
     * @param array       $usuario Array com os dados do usuário
     * @param string|null $local   Qual o local está, por exemplo: site, painel, etc
     *
     * @return bool
     * @throws Excecao
     */
    public function criar(array $usuario, ?string $local = null): bool
    {
        $this->deletar();

        if (is_null($local)) {
            $local = mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        }

        $hash = uuid();
        $userAgent = md5($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
        if (!array_key_exists('imagem', $usuario) || empty($usuario['imagem'])) {
            $usuario['imagem'] = LINK_PADRAO . '/images/site/usuario_padrao_preto.png';
        }

        sessao('AUTH_' . $local . '_' . $userAgent . '_HASH', $hash);
        sessao('AUTH_' . $local . '_' . $userAgent, $hash);
        sessao('USUARIO', $usuario);

        return true;
    }

    /**
     * Deleta a sessão atual caso exista
     *
     * @return bool
     */
    public function deletar(?string $local = null): bool
    {
        sessaoDestruir();
        return true;
    }
}
