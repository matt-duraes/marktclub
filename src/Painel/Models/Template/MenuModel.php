<?php

namespace PainelModel\Template;

final class MenuModel
{
    public function montarMenu(string $menu)
    {
        $lista = sessao('PAINEL.menu');

        $permissaoUsuario = sessao('USUARIO.permissao');
        $permissaoPainel = sessao('PAINEL.permissao');

        $link = [];
        $dropDown = false;
        $dropDownLista = (object)[];
        foreach ($lista as $r) {
            if (in_array($r['tipo'], ['titulo', 'dropdown']) && !vazio($dropDownLista)) {
                $link[] = $dropDownLista;
                $dropDownLista = (object)[];
                $dropDown = false;
            }

            $permissao = $r['permissao'];
            $temPermissao = false;
            foreach ($permissao as $rPermissao) {
                if (
                    $rPermissao == '*' ||
                    (in_array($rPermissao, $permissaoUsuario) && in_array($rPermissao, $permissaoPainel))
                ) {
                    $temPermissao = true;
                }
            }
            if (!$temPermissao) {
                continue;
            }

            if ($r['tipo'] == 'titulo') {
                $link[] = (object)[
                    'tipo'      => 'titulo',
                    'titulo'    => strCaixaAlta($r['titulo']),
                    'permissao' => $permissao
                ];
            } elseif ($r['tipo'] == 'dropdown') {
                $dropDown = true;
                $dropDownLista = (object)[
                    'tipo'      => 'dropdown',
                    'titulo'    => $r['titulo'],
                    'icone'     => $r['icone'],
                    'aberto'    => in_array($menu, $r['menu']),
                    'permissao' => $permissao,
                    'lista'     => []
                ];
            } elseif ($dropDown && $r['tipo'] == 'menu') {
                $dropDownLista->lista[] = (object)[
                    'titulo'    => $r['titulo'],
                    'url'       => LINK . $r['url'],
                    'icone'     => $r['icone'],
                    'pagina'    => in_array($menu, $r['menu']),
                    'permissao' => $permissao
                ];
            } elseif ($r['tipo'] == 'menu') {
                $link[] = (object)[
                    'tipo'      => 'menu',
                    'titulo'    => $r['titulo'],
                    'url'       => LINK . $r['url'],
                    'icone'     => $r['icone'],
                    'pagina'    => in_array($menu, $r['menu']),
                    'permissao' => $permissao
                ];
            }
        }
        if (!vazio($dropDownLista)) {
            $link[] = $dropDownLista;
        }
        return $link;
    }
}
