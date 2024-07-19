<?php

namespace App\Models\Api\ParceiroLoja;

use Helpers\OrmHelper;

final class LinkSiteModel
{
    public string $link;

    public function __construct(
        private LojaEntity $Loja
    ) {
        $this->link = $Loja->link_site;
        if ($Loja->id == '814b9d1792724316417c96b8fc33aacb') {
            $this->link = 'https://api.marktclub.net.br/integracao/link/' . $this->criarHash();
        } elseif ($Loja->id == '75f36834053439727abd97d4003af9cc') {
            $this->link = LINK . '/solicitacao-link/confirmar/' . $this->criarHashOld();
        }
    }

    private function criarHash()
    {
        if (!array_key_exists('usuario', TOKEN) || !array_key_exists('empresa', TOKEN)) {
            return '';
        }

        return base64Encode([
            'parceiro' => $this->Loja->get('id'),
            'usuario'  => TOKEN['usuario']->id,
            'empresa'  => TOKEN['empresa']->id,
            'data'     => dataAdicionar(agora(), 5, 'minutos', 'Y-m-d H:i:s')
        ], true);
    }

    private function criarHashOld()
    {
        if (!array_key_exists('usuario', TOKEN)) {
            return '';
        }
        $clube = (new OrmHelper(TABELA_CONSTRUTOR_CLUBE))->pegarPrimeiroRegistro(
            where: ['id_admin_empresa', TOKEN['empresa']->id],
            campo: ['uuid', 'logo_principal', 'titulo', 'cor_principal', 'link_clube'],
            retorno: 'object'
        );
        return base64Encode([
            'parceiro' => [
                'id'     => $this->Loja->get('id'),
                'titulo' => $this->Loja->titulo,
                'imagem' => $this->Loja->imagem_logo,
                'limite' => $this->Loja->limite_voucher,
                'link'   => $this->Loja->link_site
            ],
            'usuario' => TOKEN['usuario']->id,
            'empresa' => TOKEN['empresa']->id,
            'data'    => dataAdicionar(agora(), 5, 'minutos', 'Y-m-d H:i:s'),
            'clube'   => [
                'id'         => $clube->uuid,
                'titulo'     => $clube->titulo,
                'cor'        => $clube->cor_principal,
                'imagem'     => arquivoPrivado($clube->logo_principal),
                'link_clube' => $clube->link_clube
            ]
        ], true);
    }
}
