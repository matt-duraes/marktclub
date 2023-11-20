<?php

namespace Helpers;

final class UserAgentHelper
{
    private string $dispositivo = 'Não identificado';
    private string $os = 'Não identificado';
    private string $navegador = 'Não identificado';
    private string $versao = '0';
    private bool $mobile = false;
    private bool $tablet = false;

    public function dispositivo()
    {
        return $this->dispositivo;
    }

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

    public function mobile()
    {
        return $this->mobile;
    }

    public function tablet()
    {
        return $this->tablet;
    }

    /**
     * @param  null|string $userAgente User agent que deseja converter, deixar como null para pegar o user atual
     * @return array
     */
    public function __construct(?string $userAgent = null)
    {
        if (empty($userAgent)) {
            $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        }

        if (empty($userAgent)) {
            return;
        }

        $browscap = null;
        if (true === ini_get('browscap')) {
            $browscap = get_browser($userAgent);
        }
        $browscap = !is_object($browscap) ? (object)[] : $browscap;

        $os = $browscap->platform ?? '';
        $navegador = $browscap->browser ?? '';
        $versao = $browscap->version ?? 0;

        if (
            is_object($browscap) &&
            !empty($os) && $os != 'unknown' &&
            !empty($navegador) && $navegador != 'unknown'
        ) {
            $this->tratar([
                'dispositivo' => $browscap->device_type,
                'os'          => $os,
                'navegador'   => $navegador,
                'versao'      => $versao,
                'mobile'      => $browscap->ismobiledevice ?? false,
                'tablet'      => $browscap->istablet ?? false,
            ]);
            return;
        }
        $this->tratar($this->geral($userAgent));
    }

    private function tratar(array $dado)
    {
        $os = array_key_exists('os', $dado) && is_string($dado['os']) && !empty($dado['os']) && $dado['os'] != 'unknown' ? $dado['os'] : '';
        $dispositivo = array_key_exists('dispositivo', $dado) && is_string($dado['dispositivo']) && !empty($dado['dispositivo']) && $dado['dispositivo'] != 'unknown' ? $dado['dispositivo'] : '';
        $navegador = array_key_exists('navegador', $dado) && $dado['navegador'] != '' ? $dado['navegador'] : '';
        $versao = array_key_exists('versao', $dado) && $dado['versao'] != '' ? $dado['versao'] : '';
        $mobile = array_key_exists('mobile', $dado) && $dado['mobile'] != '' ? $dado['mobile'] : '';
        $tablet = array_key_exists('tablet', $dado) && $dado['tablet'] != '' ? $dado['tablet'] : '';

        $this->navegador = $navegador;
        $this->versao = $versao;

        if (
            preg_match('/win[0-9]{1}/i', $os) ||
            preg_match('/WinVista/i', $os)
        ) {
            $os = 'Windows';
        } elseif (
            preg_match('/iPad/i', $os) ||
            preg_match('/ipadOS/i', $os)
        ) {
            $os = 'iPadOs';
        } elseif (
            preg_match('/iPhone/i', $os) ||
            preg_match('/IOS/i', $os)
        ) {
            $os = 'iOS';
        } elseif (preg_match('/WinPhone/i', $os)) {
            $os = 'Windows Phone';
        } elseif (
            preg_match('/MacOSX/i', $os) ||
            preg_match('/Macintosh/i', $os)
        ) {
            $os = 'Macintosh';
        }

        if (empty($dispositivo) && in_array($os, ['Macintosh', 'Windows', 'Linux', 'FreeBSD'])) {
            $dispositivo = 'Desktop';
        } elseif (
            empty($dispositivo) && ($os == 'iOS' || (in_array($os, ['Android', 'Windows Phone']) && $mobile))
        ) {
            $dispositivo = 'Mobile Phone';
        } elseif (
            empty($dispositivo) && ($os == 'iPadOs' || (in_array($os, ['Android', 'Windows Phone']) && $tablet))
        ) {
            $dispositivo = 'Tablet';
        } elseif (empty($dispositivo) && in_array($os, ['Android', 'Windows Phone'])) {
            $dispositivo = 'Mobile Phone';
        } elseif (
            preg_match('/phone/i', $os) ||
            preg_match('/Mobile Device/i', $os)
        ) {
            $dispositivo = 'Mobile Phone';
        } elseif (
            preg_match('/computer/i', $os)
        ) {
            $dispositivo = 'Desktop';
        } elseif (empty($dispositivo) && preg_match('/Kindle/i', $os)) {
            $dispositivo = 'Kindle';
        } elseif (empty($dispositivo) && preg_match('/iPod/i', $os)) {
            $dispositivo = 'iPod';
        }

        if ($tablet && $os == 'iOS') {
            $os = 'iPadOs';
        }

        if ($dispositivo == 'Mobile Phone') {
            $mobile = true;
            $tablet = false;
        }
        if ($dispositivo == 'Tablet') {
            $tablet = true;
            $mobile = false;
        }

        $this->dispositivo = $dispositivo;
        $this->os = $os;
        $this->mobile = $mobile;
        $this->tablet = $tablet;
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
                return ['os' => $platform ?: null, 'navegador' => $result['browser'], 'versao' => isset($result['version']) ? $result['version'] : null];
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
            } elseif (!empty($platform) && strpos($platform, 'BB') === 0) {
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

        $tablet = false;
        $mobile = false;
        $os = $platform ?: '';
        $dispositivo = '';
        if (
            is_string($os) && !empty($os) &&
            (
                preg_match('/android/i', $os) ||
                preg_match('/ios/i', $os) ||
                preg_match('/WinPhone/i', $os)
            )
        ) {
            $dispositivo = 'Mobile Phone';
            $mobile = true;
        } elseif (is_string($os) && !empty($os) && preg_match('/ipad/i', $os)) {
            $dispositivo = 'Tablet';
            $tablet = true;
        } elseif (
            is_string($os) && !empty($os) &&
            (
                preg_match('/Win[0-9]+/i', $os) ||
                preg_match('/Linux/i', $os) ||
                preg_match('/MacOSX/i', $os) ||
                preg_match('/Macintosh/i', $os) ||
                preg_match('/WinVista/i', $os)
            )
        ) {
            $dispositivo = 'Desktop';
        }

        return [
            'dispositivo' => $dispositivo,
            'os'          => $os,
            'navegador'   => $browser ?: null,
            'versao'      => $version ?: null,
            'mobile'      => $mobile,
            'tablet'      => $tablet
        ];
    }
}
