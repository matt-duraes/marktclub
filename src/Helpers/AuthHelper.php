<?php

namespace Helpers;

final class AuthHelper
{
    /**
     * Validar se usuário está logado
     * @param Null|String       $local          Qual o local está, por exemplo: site, painel, etc
     * @param Bool              $location       Se vai salvar a URL para usar no location
     */
    public function validar(?string $local = null, bool $location = true)
    {
        $local = $local != null ? $local : mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        $userAgent = md5($_SERVER['HTTP_USER_AGENT']);

        if (
            sessaoExiste('USUARIO') &&
            sessaoExiste('AUTH_' . $local . '_' . $userAgent . '_HASH') &&
            sessaoExiste('AUTH_' . $local . '_' . $userAgent) &&
            sessao('AUTH_' . $local . '_' . $userAgent . '_HASH') == sessao('AUTH_' . $local . '_' . $userAgent)
        ) {
            return true;
        }

        if ($location) {
            $protocolo = explode('/', LINK)[0];
            $hostname = explode('/', str_replace(['http://', 'https://'], '', LINK))[0];
            sessao('AUTH_' . $local . '_LOCATION', $protocolo . '//' . $hostname . $_SERVER['REQUEST_URI']);
        }
        return false;
    }

    /**
     * Retorna o link para o location após o login
     * @param   Null|String       $local        Qual o local está, por exemplo: site, painel, etc
     * @param   Null|String       $link         Para qual link deve ser redirecionado, caso exista
     *                                              um link de redirecionamento, será ignorado
     */
    public function location(?string $local = null, ?string $link = null)
    {
        $local = $local != null ? $local : mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        $link = $link == null ? LINK : $link;

        if (sessaoExiste('AUTH_' . $local . '_LOCATION')) {
            $link = sessao('AUTH_' . $local . '_LOCATION');
            sessaoDeletar('AUTH_' . $local . '_LOCATION');
        }
        return $link;
    }

    /**
     * Gera a auth do usuário
     * @param Array             $usuario        Array com os dados do usuário
     * @param Null|String       $local          Qual o local está, por exemplo: site, painel, etc
     */
    public function criar(array $usuario, ?string $local = null): Bool
    {
        $this->deletar();

        $local = $local != null ? $local : mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        $hash = uuid();

        $userAgent = md5($_SERVER['HTTP_USER_AGENT']);
        sessao('AUTH_' . $local . '_' . $userAgent . '_HASH', $hash);
        sessao('AUTH_' . $local . '_' . $userAgent, $hash);
        sessao('USUARIO', $usuario);

        return true;
    }

    /**
     * Deleta a sesão atual caso exista
     */
    public function deletar(): bool
    {
        sessaoDestruir();
        $this->deletarCookie();

        return true;
    }

    /**
     * Cria um cookie de auth
     *
     * @param null|string   $local      Qual o local está, por exemplo: site, painel, etc
     * @return string Retona uma string com o hash criado ou false para falha
     */
    public function criarCookie(?string $local = null): string | bool
    {
        $local = $local != null ? $local : mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        $nome = hashIpUser($local . '_auth');
        $hash = hashUnico();
        if (criarCookie($nome, $hash, dia: 365)) {
            return $hash;
        }
        return false;
    }

    /**
     * Deleta o cookie de auth
     *
     * @param null|string   $local      Qual o local está, por exemplo: site, painel, etc
     * @return bool Retorna false para falha ou true para sucesso
     */
    public function deletarCookie(?string $local = null): bool
    {
        $local = $local != null ? $local : mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        $nome = hashIpUser($local . '_auth');
        return deletarCookie($nome);
    }
}
