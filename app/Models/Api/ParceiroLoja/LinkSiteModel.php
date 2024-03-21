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
            $this->link = 'https://api.marktclub.net.br/integracao/link/' . $this->criarLinkDell();
        } elseif ($Loja->id == '75f36834053439727abd97d4003af9cc') {
            $this->link = LINK . '/solicitacao-link/confirmar/' . $this->criarHash();
        }
    }

    private function criarHash()
    {
        if (!array_key_exists('usuario', TOKEN)) {
            return '';
        }
        $clube = (new OrmHelper(TABELA_CONSTRUTOR_CLUBE))->pegarPrimeiroRegistro(
            where: ['id', TOKEN['empresa']->id],
            campo: ['uuid', 'logo_principal', 'titulo', 'cor_principal', 'link_clube'],
            retorno: 'object'
        );
        return base64Encode([
            'parceiro' => [
                'id'     => $this->Loja->get('id'),
                'titulo' => $this->Loja->titulo,
                'imagem' => $this->Loja->link_logo,
                'limite' => $this->Loja->limite_voucher
            ],
            'usuario' => TOKEN['usuario']->id,
            'empresa' => TOKEN['empresa']->id,
            'data'    => dataAdicionar(agora(), 10, 'minutos', 'Y-m-d H:i:s'),
            'clube'   => [
                'id'         => $clube->uuid,
                'titulo'     => $clube->titulo,
                'cor'        => $clube->cor_principal,
                'imagem'     => arquivoPrivado($clube->logo_principal),
                'link_clube' => $clube->link_clube
            ]
        ], true);
    }

    private function criarLinkDell()
    {
        $nome = $this->Loja->titulo;
        $url = $this->Loja->url;
        $site = $this->Loja->link_site;
        $imagem = $this->Loja->link_logo;

        $clube = (new OrmHelper(TABELA_CONSTRUTOR_CLUBE))->pegarPrimeiroRegistro(
            where: ['id', TOKEN['empresa']->id],
            campo: ['logo_principal', 'titulo', 'cor_principal', 'link_clube'],
            retorno: 'object'
        );
        $logo = arquivoPrivado($clube->logo_principal ?? '');
        $titulo = $clube->titulo ?? '';
        $cor = $clube->cor_principal ?? '';
        $link = $clube->link_clube ?? '';

        $criado_em = time();
        $vence_em = time() + 120;
        $uuid = uuid();

        $body = json_encode([
            'name'         => $nome,
            'iss'          => 'parceiro',
            'picture'      => $imagem,
            'logo'         => $logo,
            'titulo_clube' => $titulo,
            'link'         => $link,
            'cor'          => $cor,
            'sub'          => $uuid,
            'uri'          => $url,
            'url'          => $site,
            'iat'          => $criado_em,
            'exp'          => $vence_em
        ]);

        $iv = $this->criarIv();

        try {
            $hash = openssl_encrypt($body, 'AES-256-CBC', 'parceiro', 0, $iv);
            return str_replace(['+', '/', '='], ['-', '_', ':'], $iv . $hash);
        } catch (\Throwable) {
            return false;
        }
    }

    private function criarIv()
    {
        $retorno = '';
        $caractere = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $caractere_tamanho = strlen($caractere);
        $tamanho = openssl_cipher_iv_length('AES-256-CBC');
        for ($n = 1; $n <= $tamanho; $n++) {
            $rand = mt_rand(1, $caractere_tamanho);
            $retorno .= $caractere[$rand - 1];
        }
        return $retorno;
    }
}
