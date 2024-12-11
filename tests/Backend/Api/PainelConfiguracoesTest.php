<?php

namespace Tests\Api;

use Tests\Token\Clube;
use App\Classes\Painel\Config\Padrao;
use App\Classes\Painel\Config\Recurso;
use App\Classes\Painel\Config\CampoObrigatorio;

class PainelConfiguracoesTest extends Clube
{
    public string $automatico = 'lbsad';
    protected string $scope = 'painel_config';
    protected string $uri = '/painel-configuracao';
    private string $idEmpresa = '4cceef2a4ee3d677dd15955daace4bba';

    public function __construct()
    {
        $this
            ->tabela(TABELA_PAINEL_CONFIG)
            ->resetar();
        parent::__construct();
    }

    protected function pegarBody(): array
    {
        $retorno = $this->montarPermissoes();
        return [
            'empresa'           => $this->idEmpresa,
            'configuracao'      => Recurso::LISTA,
            'campo_obrigatorio' => CampoObrigatorio::LISTA,
            'permissao'         => $retorno['permissoes'],
            'titulo'            => $retorno['titulos']
        ];
    }

    private function montarPermissoes(): array
    {
        $titulos = [];
        $permissoes = [];
        foreach ((new Padrao())->PERMISSAO as $nomeApp => $permissoesApp) {
            $titulos[$nomeApp] = $permissoesApp['titulo'] ?? '';
            /*if (array_key_exists('acao', $permissoesApp) && !empty($permissoesApp['acao'])) {
                foreach ($permissoesApp['acao'] as $permissao) {
                    $permissoes[] = $nomeApp . '_' . $permissao;
                }
            } else*/
            if (array_key_exists('permissao', $permissoesApp)) {
                foreach (array_keys($permissoesApp['permissao']) as $permissao) {
                    $permissoes[] = $permissao;
                }
            }
        }
        return [
            'titulos'    => $titulos,
            'permissoes' => array_unique($permissoes),
        ];
    }
}
