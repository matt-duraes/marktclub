<?php

namespace System\Config;

use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Session as SessionSession;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

final class Session
{
    /**
     */
    public function start(): void
    {
        $__SESSION_PRIVACIDADE = env('SESSION_PRIVACIDADE', '');
        if (!empty($__SESSION_PRIVACIDADE)) {
            session_cache_limiter($__SESSION_PRIVACIDADE);
        }

        $__SESSION_CACHE = env('SESSION_CACHE', '');
        if (
            session_cache_limiter() != 'nocache' &&
            preg_match('/^[0-9]+$/', $__SESSION_CACHE) &&
            $__SESSION_CACHE > 0
        ) {
            session_cache_expire($__SESSION_CACHE);
        }

        $__SESSION_DIRETORIO = str_replace('{{ROOT}}', ROOT, env('SESSION_DIRETORIO', ''));
        if (!empty($__SESSION_DIRETORIO) && file_exists($__SESSION_DIRETORIO)) {
            session_save_path($__SESSION_DIRETORIO);
        }

        $__SESSION_SAMESITE = env('SESSION_SAMESITE', 'Strict');
        $session = new SessionSession(
            new NativeSessionStorage([
                'cookie_secure'   => true,
                'cookie_httponly' => true,
                'cookie_path'     => '/',
                'cookie_samesite' => $__SESSION_SAMESITE
            ])
        );

        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        $session->setName('__Host-id_' . md5(ip() . $userAgent));
        $session->start();
    }

    /**
     * @return AttributeBag
     */
    public function storage(): AttributeBag
    {
        $diretorio = defined('ROUTE_DIRETORIO') ? ROUTE_DIRETORIO : 'GERAL';
        return new AttributeBag(
            'SESSAO_' . strCaixaAlta($diretorio)
        );
    }
}
