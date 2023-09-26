<?php

namespace Helpers;

use Erro\Excecao;

final class AuthHelper
{
    private string $location = LINK;

    /**
     * Validar se usuário está logado
     *
     * @param string|null $local    Qual o local está, por exemplo: site, painel, etc
     * @param bool        $location Se vai salvar a URL para usar no location
     *
     * @throws Excecao
     */
    public function validar(string $local = null, bool $location = true): bool
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
        if ($location) {
            $this->criarLocation($local, LINK . URI);
        }

        return false;
    }

    private function criarLocation($local, $link)
    {
        sessao('AUTH_' . $local . '_LOCATION', $link);
    }

    /**
     * Retorna o link para o location após o login
     *
     * @param string|null $local Qual o local está, por exemplo: site, painel, etc
     * @param string|null $link  Para qual link deve ser redirecionado, caso exista um link de redirecionamento,
     *                           será ignorado
     *
     * @return mixed
     * @throws Excecao
     */
    public function location(string $local = null, string $link = null): mixed
    {
        if (is_null($local)) {
            $local = mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        }

        $link = $link ?? LINK;
        $nomeDaLocation = 'AUTH_' . $local . '_LOCATION';

        if (sessaoExiste($nomeDaLocation)) {
            $link = sessao($nomeDaLocation);
            sessaoDeletar($nomeDaLocation);
        }

        return $link;
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
        $location = $this->location();
        $this->deletar();

        if (is_null($local)) {
            $local = mb_strtoupper(ROUTE_DIRETORIO, 'UTF-8');
        }

        $hash = uuid();
        $userAgent = md5($_SERVER['HTTP_USER_AGENT'] ?? 'unknown');
        if (!array_key_exists('imagem', $usuario) || empty($usuario['imagem'])) {
            $usuario['imagem'] = LINK . '/images/site/usuario_padrao_preto.png';
        }

        sessao('AUTH_' . $local . '_' . $userAgent . '_HASH', $hash);
        sessao('AUTH_' . $local . '_' . $userAgent, $hash);
        sessao('USUARIO', $usuario);
        $this->criarLocation($local, $location);

        return true;
    }

    /**
     * Deleta a sessão atual caso exista
     *
     * @return bool
     */
    public function deletar(): bool
    {
        sessaoDestruir();
        return true;
    }
}
