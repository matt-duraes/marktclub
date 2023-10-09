<?php

namespace PainelModel\Perfil;

use Helpers\ApiHelper;

final class Equipe
{
    private array $usuario = [];
    private string $imagemPadrao = LINK_PADRAO . '/images/painel/usuario_padrao_preto.png';

    public function __construct()
    {
        $this->usuario = sessao('PERFIL_EQUIPE_LISTA', padrao: []);
    }

    public function unico(?string $id)
    {
        if (array_key_exists($id, $this->usuario)) {
            $this->pegarPerfil();
        }
        return $this->usuario[$id] ?? $this->usuarioPadrao();
    }

    public function lista(array $id)
    {
        $retorno = [];
        foreach ($id as $usuario) {
            $retorno[] = $this->unico($usuario);
        }
        return $retorno;
    }

    private function pegarPerfil()
    {
        $lista = (new ApiHelper(token: true))
            ->get('/usuario-equipe/perfil')
            ->object()->dado ?? [];

        $usuario = [];
        foreach ($lista as $r) {
            if (empty($r->imagem)) {
                $r->imagem = $this->imagemPadrao;
            }
            $usuario[$r->id] = $r;
        }
        $this->usuario = $usuario;
        sessao('PERFIL_EQUIPE_LISTA', $usuario);
    }

    private function usuarioPadrao()
    {
        return (object)[
            'id'     => '',
            'nome'   => 'Sem usuário',
            'perfil' => '',
            'imagem' => $this->imagemPadrao
        ];
    }
}
