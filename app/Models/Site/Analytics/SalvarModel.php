<?php

namespace  App\Models\Site\Analytics;

use Modules\Botao;
use App\Helpers\ClubeApiHelper;

final class SalvarModel extends ClubeApiHelper
{
    public function __construct(string $uri, string $vinculo = '')
    {
        parent::__construct();
        $usuario = sessao('USUARIO');
        $this
            ->body([
                'vinculo'      => $vinculo,
                'usuario_tipo' => $usuario['tipo'],
                'usuario_nome' => $usuario['nome'],
                'usuario_cpf'  => $usuario['cpf'],
                'hash'         => $this->pegarHash(),
                'dispositivo'  => DISPOSITIVO_TIPO,
                'os'           => DISPOSITIVO_OS,
                'browser'      => DISPOSITIVO_NAVEGADOR,
                'versao'       => DISPOSITIVO_VERSAO,
                'mobile'       => (new Botao(DISPOSITIVO_MOBILE))->valor(),
                'tablet'       => (new Botao(DISPOSITIVO_TABLET))->valor(),
                'ip'           => ip(),
                'agent'        => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'pais'         => '',
                'estado'       => '',
                'cidade'       => '',
                'latitude'     => '',
                'longitude'    => '',
                'url'          => $uri
            ])
        ->post('/relatorio/analytics');
    }

    private function pegarHash()
    {
        if (!sessaoExiste('ANALYTICS_HASH')) {
            sessao('ANALYTICS_HASH', uuid());
        }
        return sessao('ANALYTICS_HASH');
    }
}
