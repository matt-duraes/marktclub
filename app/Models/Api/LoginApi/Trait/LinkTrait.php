<?php

namespace App\Models\Api\LoginApi\Trait;

use Http\Response;

trait LinkTrait
{
    public function link(): Response
    {
        $dado = $this->request;
        if (in_array($this->idEmpresa, [1, 1981]) && !array_key_exists('real', $dado)) {
            return $this->linkLoginCfm($dado);
        }
        if ($this->lgpd) {
            return $this->mandarParaTermoLgpd();
        }

        $dominio = str_replace(['https://', 'http://'], '', $this->linkClube);
        $link = 'https://' . $dominio . '/login/api/' . $this->hash;
        if (
            SISTEMA == 'HOMOLOGACAO' &&
            !in_array($dominio, ['cfmmais-hom.cfm.org.br', 'digiohml.youhuul.com', 'uberhml.youhuul.com'])
        ) {
            $link = 'https://apiv4hml.youhuul.com/login/api-ok/' . base64Encode([
                'nome' => $this->dadoUsuario['nome'],
                'data' => agora(),
                'hash' => $this->hash
            ], true);
        }

        return mensagemSucesso([
            'link' => $link
        ], status: 201);
    }

    private function linkLoginCfm($dado)
    {
        $retorno = [];
        foreach ($dado as $ind => $val) {
            if (!in_array($ind, ['nome', 'cpf', 'email_pessoal', 'email_trabalho', 'crm_numero', 'crm_estado', 'termo_lgpd'])) {
                continue;
            }
            $retorno[$ind] = $val;
        }
        return mensagemSucesso([
            'link' => $this->linkClube . '/login/api-acesso/' . base64Encode($retorno, url: true)
        ], status: 201);
    }
}
