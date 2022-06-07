<?php

namespace Helpers;

final class UserAgentHelper
{

    private string $os;
    private string $navegador;
    private string $versao;

    public function os()
    {
        return $this->os;
    }
    public function navegador()
    {
        return $this->navegador;
    }
    public function versao()
    {
        return $this->versao;
    }


    /**
     * @param null|string $userAgente User agent que deseja converter, deixar como null para pegar o user atual
     * @return array
     */
    public function __construct(?string $userAgent = null)
    {
        if (empty($userAgent)) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }
        $browscap = !empty($userAgent) ? get_browser($userAgent) : '';
        if (
            is_object($browscap) &&
            isset($browscap->platform) && !empty($browscap->platform) && $browscap->platform != 'unknown' &&
            isset($browscap->browser) && !empty($browscap->browser) && $browscap->browser != 'unknown' &&
            isset($browscap->version) && $browscap->version > 0
        ) {
            $this->tratar([
                'os' => $browscap->platform,
                'navegador' => $browscap->browser,
                'versao' => $browscap->version,
            ]);
            return;
        }
        $this->tratar($this->geral($userAgent));
    }

    private function tratar(array $dado)
    {
        if (in_array($dado['os'], ['Win7', 'Win8', 'Win8.1', 'Win32', 'WinVista'])) {
            $this->os = 'Windows';
        } elseif (in_array($dado['os'], ['MacOSX'])) {
            $this->os = 'Macintosh';
        }
        $this->navegador = $dado['navegador'];
        $this->versao = (float) $dado['versao'];
    }

    private function geral(?string $userAgent = null)
    {
        if (empty($userAgent)) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }
        if (empty($userAgent)) {
            return ['os' => null, 'navegador' => null, 'versao' => null];
        }

        $platform = null;
        $browser = null;
        $version = null;

        $empty = ['os' => $platform, 'navegador' => $browser, 'versao' => $version];

        if (!$userAgent) {
            return $empty;
        }

        if (preg_match('/\((.*?)\)/m', $userAgent, $parent_matches)) {
            preg_match_all(
                '/(?P<platform>BB\d+;|Android|CrOS|Tizen|iPhone|iPad|iPod|Linux|(Open|Net|Free)BSD|Macintosh|Windows(\ Phone)?|Silk|linux-gnu|BlackBerry|PlayBook|X11|(New\ )?Nintendo\ (WiiU?|3?DS|Switch)|Xbox(\ One)?)(?:\ [^;]*)?(?:;|$)/imx',
                $parent_matches[1],
                $result
            );

            $priority = ['Xbox One', 'Xbox', 'Windows Phone', 'Tizen', 'Android', 'FreeBSD', 'NetBSD', 'OpenBSD', 'CrOS', 'X11'];

            $result['platform'] = array_unique($result['platform']);
            if (count($result['platform']) > 1) {
                $keys = array_intersect($priority, $result['platform']);
                if ($keys) {
                    $platform = reset($keys);
                } else {
                    $platform = $result['platform'][0];
                }
            } elseif (isset($result['platform'][0])) {
                $platform = $result['platform'][0];
            }
        }

        if ($platform == 'linux-gnu' || $platform == 'X11') {
            $platform = 'Linux';
        } elseif ($platform == 'CrOS') {
            $platform = 'Chrome OS';
        }

        preg_match_all(
            '%(?P<browser>Camino|Kindle(\ Fire)?|Firefox|Iceweasel|IceCat|Safari|MSIE|Trident|AppleWebKit|TizenBrowser|(?:Headless)?Chrome|YaBrowser|Vivaldi|IEMobile|Opera|OPR|Silk|Midori|Edge|Edg|CriOS|UCBrowser|Puffin|OculusBrowser|SamsungBrowser|Baiduspider|Googlebot|YandexBot|bingbot|Lynx|Version|Wget|curl|Valve\ Steam\ Tenfoot|NintendoBrowser|PLAYSTATION\ (\d|Vita)+)(?:\)?;?)(?:(?:[:/ ])(?P<version>[0-9A-Z.]+)|/(?:[A-Z]*))%ix',
            $userAgent,
            $result
        );

        // If nothing matched, return null (to avoid undefined index errors)
        if (!isset($result['browser'][0]) || !isset($result['version'][0])) {
            if (preg_match('%^(?!Mozilla)(?P<browser>[A-Z0-9\-]+)(/(?P<version>[0-9A-Z.]+))?%ix', $userAgent, $result)) {
                return ['os' => $platform ?: null, 'navegador' => $result['browser'], 'versao' => isset($result['version']) ? $result['version'] ?: null : null];
            }

            return $empty;
        }

        if (preg_match('/rv:(?P<version>[0-9A-Z.]+)/i', $userAgent, $rv_result)) {
            $rv_result = $rv_result['version'];
        }

        $browser = $result['browser'][0];
        $version = $result['version'][0];

        $lowerBrowser = array_map('strtolower', $result['browser']);

        $find = function ($search, &$key, &$value = null) use ($lowerBrowser) {
            $search = (array) $search;

            foreach ($search as $val) {
                $xkey = array_search(strtolower($val), $lowerBrowser);
                if ($xkey !== false) {
                    $value = $val;
                    $key = $xkey;

                    return true;
                }
            }

            return false;
        };

        $key = 0;
        $pKey = preg_grep('/playstation \d/i', $result['browser']);
        $val = '';
        if ($browser == 'Iceweasel' || strtolower($browser) == 'icecat') {
            $browser = 'Firefox';
        } elseif ($find('Playstation Vita', $key)) {
            $platform = 'PlayStation Vita';
            $browser = 'Browser';
        } elseif ($find(['Kindle Fire', 'Silk'], $key, $val)) {
            $browser = $val == 'Silk' ? 'Silk' : 'Kindle';
            $platform = 'Kindle Fire';
            $version = $result['version'][$key];
            if (!$version || !is_numeric($version[0])) {
                $version = $result['version'][array_search('Version', $result['browser'])];
            }
        } elseif ($find('NintendoBrowser', $key) || $platform == 'Nintendo 3DS') {
            $browser = 'NintendoBrowser';
            $version = $result['version'][$key];
        } elseif ($find('Kindle', $key, $platform)) {
            $browser = $result['browser'][$key];
            $version = $result['version'][$key];
        } elseif ($find('OPR', $key)) {
            $browser = 'Opera';
            $version = $result['version'][$key];
        } elseif ($find('Opera', $key, $browser)) {
            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($find('Puffin', $key, $browser)) {
            $version = $result['version'][$key];
            if (strlen($version) > 3) {
                $part = substr($version, -2);
                if (ctype_upper($part)) {
                    $version = substr($version, 0, -2);

                    $flags = ['IP' => 'iPhone', 'IT' => 'iPad', 'AP' => 'Android', 'AT' => 'Android', 'WP' => 'Windows Phone', 'WT' => 'Windows'];
                    if (isset($flags[$part])) {
                        $platform = $flags[$part];
                    }
                }
            }
        } elseif ($find('YaBrowser', $key, $browser)) {
            $browser = 'Yandex';
            $version = $result['version'][$key];
        } elseif ($find(['Edge', 'Edg'], $key, $browser)) {
            $browser = 'Edge';
            $version = $result['version'][$key];
        } elseif ($find(['IEMobile', 'Midori', 'Vivaldi', 'OculusBrowser', 'SamsungBrowser', 'Valve Steam Tenfoot', 'Chrome', 'HeadlessChrome'], $key, $browser)) {
            $version = $result['version'][$key];
        } elseif ($rv_result && $find('Trident', $key)) {
            $browser = 'MSIE';
            $version = $rv_result;
        } elseif ($find('UCBrowser', $key)) {
            $browser = 'UC Browser';
            $version = $result['version'][$key];
        } elseif ($find('CriOS', $key)) {
            $browser = 'Chrome';
            $version = $result['version'][$key];
        } elseif ($browser == 'AppleWebKit') {
            if ($platform == 'Android') {
                $browser = 'Android Browser';
            } elseif (strpos($platform, 'BB') === 0) {
                $browser = 'BlackBerry Browser';
                $platform = 'BlackBerry';
            } elseif ($platform == 'BlackBerry' || $platform == 'PlayBook') {
                $browser = 'BlackBerry Browser';
            } else {
                $find('Safari', $key, $browser) || $find('TizenBrowser', $key, $browser);
            }

            $find('Version', $key);
            $version = $result['version'][$key];
        } elseif ($pKey) {
            $pKey = reset($pKey);

            $platform = 'PlayStation ' . preg_replace('/\D/', '', $pKey);
            $browser = 'NetFront';
        }

        return ['os' => $platform ?: null, 'navegador' => $browser ?: null, 'versao' => $version ?: null];
    }
}
