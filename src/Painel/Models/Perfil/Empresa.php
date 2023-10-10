<?php

namespace PainelModel\Perfil;

use Helpers\ApiHelper;

final class Empresa
{
    private array $empresa = [];
    private string $imagemPadrao = LINK_PADRAO . '/images/painel/empresa_padrao_preto.png';

    public function __construct()
    {
        $this->empresa = sessao('PERFIL_EMPRESA_LISTA', padrao: []);
    }

    public function unico(?string $id)
    {
        if (array_key_exists($id, $this->empresa)) {
            $this->pegarPerfil();
        }
        return $this->empresa[$id] ?? $this->empresaPadrao();
    }

    public function lista(array $id)
    {
        $retorno = [];
        foreach ($id as $empresa) {
            $retorno[] = $this->unico($empresa);
        }
        return $retorno;
    }

    private function pegarPerfil()
    {
        $lista = (new ApiHelper(token: true))
            ->get('/comercial-empresa/perfil')
            ->object()->dado ?? [];

        $empresa = [];
        foreach ($lista as $r) {
            if (empty($r->imagem)) {
                $r->imagem = $this->imagemPadrao;
            }
            $empresa[$r->id] = $r;
        }
        $this->empresa = $empresa;
        sessao('PERFIL_EMPRESA_LISTA', $empresa);
    }

    private function empresaPadrao()
    {
        return (object)[
            'id'     => '',
            'nome'   => 'Sem usuário',
            'imagem' => $this->imagemPadrao
        ];
    }
}
