<?php

namespace App\Controllers\Painel;

use stdClass;
use Erro\Erro;
use Erro\Excecao;
use Helpers\ApiHelper;
use Helpers\AuthHelper;
use Helpers\CryptHelper;
use Controller\Controller;
use Painel\Historico\Models\Entity as HistoricoEntity;

abstract class PadraoController extends Controller
{
    protected function converterNomeApp($app)
    {
        return str_replace('-', '_', $app);
    }

    protected function config(string $app, string $local): stdClass
    {
        if (!file_exists(ROOT . '/views/pages/painel/' . $app)) {
            throw new Excecao(status: 404);
        }

        if ($local == 'index') {
            return $this->configIndex($app);
        } else if ($local == 'ajax') {
            return $this->configAjax($app);
        } else if ($local == 'visualizar') {
            return $this->configVisualizar($app);
        } else if ($local == 'add') {
            return $this->configAdd($app);
        } else if ($local == 'salvar') {
            return $this->configSalvar($app);
        } else if ($local == 'filtrar') {
            return $this->configFiltrar($app);
        } else if ($local == 'download') {
            return $this->configDownload($app);
        } else if ($local == 'deletar') {
            return $this->configDeletar($app);
        } else if ($local == 'ordem') {
            return $this->configOrdem($app);
        }
    }

    private function configIndex($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $appUso = $this->pegarAppUsado($app, 'index');

        $padrao = !str_starts_with($appUso, $app . '.');

        $Index = $this->includeConfig('index', $app);
        if ($padrao && !($Index instanceof \PainelConfig\Index)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Index para esse app.');
        }

        $Filtrar = false;
        if ($config['filtrar'] ?? false) {
            $Filtrar = $this->includeConfig('filtrar', $app);
        }

        $permissaoEditar = $this->pegarPermissaoUsuario('editar', $app, $config['editar'] ?? false);
        $permissaoVisualizar = $this->pegarPermissaoUsuario('visualizar', $app, $config['visualizar'] ?? false);

        $linkAbrir = '';
        if ($permissaoVisualizar) {
            $linkAbrir = $Index->pegarLinkVisualizar();
        } else if ($permissaoEditar) {
            $linkAbrir = $Index->pegarLinkEditar();
        }

        return (object)[
            'titulo' => $config['titulo'] ?? '',
            'permissao' => (object)[
                'buscar' => $config['buscar'] ?? false,
                'filtrar' => $config['filtrar'] ?? false,
                'ordem' => $config['ordem'] ?? false,
                'drag' => $Index->pegarDrag(),
                'index' => $this->pegarPermissaoUsuario('index', $app, true),
                'download' => $this->pegarPermissaoUsuario('download', $app, $config['download'] ?? false),
                'visualizar' => $permissaoVisualizar,
                'add' => $this->pegarPermissaoUsuario('add', $app, $config['add'] ?? false),
                'editar' => $permissaoEditar,
                'deletar' => $this->pegarPermissaoUsuario('deletar', $app, $config['deletar'] ?? false),
                'historico' => $config['historico'] ?? false
            ],
            'ordem' => $Index->pegarOrdem(),
            'api' => (object)[
                'uri' => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ],
            'index' => (object)[
                'app' => $appUso,
                'grade' => $Index->pegarGrade()
            ],
            'filtrar' => (object) [
                'nome' => $Filtrar ? $Filtrar->pegarNome() : [],
                'valor' => $Filtrar ? $Filtrar->pegarReplace() : []
            ],
            'abrir' => $linkAbrir
        ];
    }

    private function configAjax($app): stdClass
    {
        $Ajax = $this->includeConfig('ajax', $app);
        if (!($Ajax instanceof \PainelConfig\Ajax)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Ajax para esse app.');
        }

        return (object)[
            'permissao' => $Ajax->pegarPermissao(),
            'rota' => $Ajax->pegarRota(),
            'metodo' => $Ajax->pegarMetodo(),
            'scope' => $Ajax->pegarScope(),
            'request' => $Ajax->pegarRequest()
        ];
    }

    private function configVisualizar($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $appUso = $this->pegarAppUsado($app, 'visualizar');

        $padrao = !str_starts_with($appUso, $app . '.');

        $Visualizar = $this->includeConfig('visualizar', $app);
        if ($padrao && !($Visualizar instanceof \PainelConfig\Visualizar)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Visualizar para esse app.');
        }

        $permissaoEditar = $this->pegarPermissaoUsuario('editar', $app, $config['editar'] ?? false);

        return (object)[
            'titulo' => $config['titulo'] ?? '',
            'permissao' => (object)[
                'historico' => $config['historico'] ?? false,
                'visualizar' => $this->pegarPermissaoUsuario('visualizar', $app, $config['visualizar'] ?? false),
                'status' => $this->pegarPermissaoUsuario('status', $app, $config['visualizar'] ?? false),
                'editar' => $permissaoEditar,
            ],
            'visualizar' => (object)[
                'app' => $appUso,
                'replace' => $padrao ? $Visualizar->pegarReplace() : [],
                'status' => $padrao ? $Visualizar->pegarStatus() : [],
                'tipo' => $padrao ? $Visualizar->pegarTipo() : '',
                'html' => $padrao ? $Visualizar->pegarHtml() : '',
                'css' => $padrao ? $Visualizar->pegarCss() : '',
                'js' => $padrao ? $Visualizar->pegarJs() : '',
            ],
            'link' => (object)[
                'editar' => $padrao ? $Visualizar->pegarLinkEditar() : ''
            ],
            'api' => (object)[
                'uri' => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ],
        ];
    }
    private function configAdd($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $Add = $this->includeConfig('add', $app);
        if (!($Add instanceof \PainelConfig\Add)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Add para esse app.');
        }

        return (object)[
            'titulo' => $config['titulo'] ?? '',
            'model' => $config['entity'] ?? '',
            'permissao' => (object)[
                'add' => $this->pegarPermissaoUsuario('add', $app, $config['add'] ?? false),
                'editar' => $this->pegarPermissaoUsuario('editar', $app, $config['editar'] ?? false),
            ],
            'add' => (object) [
                'app' => $this->pegarAppUsado($app, 'add'),
                'html' => $Add->pegarHtml(),
                'css' => $Add->pegarCss(),
                'js' => $Add->pegarJs(),
                'link' => $Add->pegarLink()
            ],
            'api' => (object)[
                'uri' => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ],
        ];
    }

    private function pegarCamposPermitido($campo, $app, $tipo)
    {
        $camposPermitido = [];
        $painelCampo = sessao('PAINEL.campo', padrao: []);
        if (is_array($painelCampo) && array_key_exists($app, $painelCampo) && $painelCampo[$app]) {
            $camposPermitido = $painelCampo[$app][$tipo] ?? $painelCampo[$app]['salvar'] ??
                $painelCampo[$app]['add'] ?? $painelCampo[$app]['geral'] ?? [];
        }
        if (!$camposPermitido || !$campo) {
            return $campo;
        }

        $retorno = [];
        foreach ($campo as $val) {
            if (in_array($val, $camposPermitido)) {
                $retorno[] = $val;
            }
        }
        return $retorno;
    }
    private function configSalvar($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $salvar = $this->includeConfig('salvar', $app);


        $campoSalvar = $this->pegarCamposPermitido($salvar['salvar'] ?? [], $app, 'salvar');
        $campoInsert = $this->pegarCamposPermitido($salvar['insert'] ?? [], $app, 'insert');
        $campoUpdate = $this->pegarCamposPermitido($salvar['update'] ?? [], $app, 'update');

        return (object)[
            'model' => $config['entity'] ?? '',
            'permissao' => (object)[
                'add' => $this->pegarPermissaoUsuario('add', $app, $config['add'] ?? false),
                'editar' => $this->pegarPermissaoUsuario('editar', $app, $config['editar'] ?? false),
                'historico' => $config['historico'] ?? false
            ],
            'salvar' => (object)[
                'salvar' => $campoSalvar,
                'insert' => $campoInsert,
                'update' => $campoUpdate,
            ],
            'api' => object([
                'uri' => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ])
        ];
    }

    private function configFiltrar($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $appUso = $this->pegarAppUsado($app, 'filtrar');

        $padrao = !str_starts_with($appUso, $app . '.');

        $Filtrar = $this->includeConfig('filtrar', $app);
        if ($padrao && !($Filtrar instanceof \PainelConfig\Filtrar)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Filtrar para esse app.');
        }

        return (object) [
            'permissao' => (object)[
                'index' => $this->pegarPermissaoUsuario('index', $app, true),
                'filtrar' => $config['filtrar'] ?? false,
            ],
            'filtrar' => (object)[
                'app' => $appUso,
                'input' => $Filtrar->pegarInput(),
                'nome' => $Filtrar->pegarNome(),
                'valor' => $Filtrar->pegarReplace()
            ]
        ];
    }

    private function configDownload($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $appUso = $this->pegarAppUsado($app, 'download');

        $padrao = !str_starts_with($appUso, $app . '.');

        $Download = $this->includeConfig('download', $app);
        if ($padrao && !($Download instanceof \PainelConfig\Download)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Download para esse app.');
        }

        return (object) [
            'permissao' => (object)[
                'index' => $this->pegarPermissaoUsuario('index', $app, true),
                'download' => $this->pegarPermissaoUsuario('download', $app, $config['filtrar'] ?? false)
            ],
            'download' => (object)[
                'app' => $appUso,
                'html' => $Download->pegarHtml(),
                'campo' => $Download->pegarCampo(),
                'replace' => $Download->pegarReplace()
            ],
            'api' => object([
                'uri' => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ])
        ];
    }

    private function configDeletar($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $deletar = $this->includeConfig('deletar', $app);
        return (object)[
            'permissao' => (object)[
                'deletar' => $this->pegarPermissaoUsuario('deletar', $app, $config['deletar'] ?? false),
                'historico' => $config['historico'] ?? false
            ],
            'deletar' => (object) [
                'status' => isset($deletar['status']) && is_numeric($deletar['status']) ? (int) $deletar['status'] : null,
                'campo' => isset($deletar['campo']) && is_numeric($deletar['campo']) ? (int) $deletar['campo'] : null,
            ],
            'api' => object([
                'uri' => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ])
        ];
    }

    private function configOrdem($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        return (object)[
            'permissao' => $this->pegarPermissaoUsuario('index', $app, true),
            'model' => $config['model'] ?? '',
        ];
    }

    private function includeConfig($tipo, $app)
    {
        if (!file_exists(ROOT . '/views/pages/painel/' . $app . '/config/' . $tipo . '.php')) {
            return false;
        }
        return require ROOT . '/views/pages/painel/' . $app . '/config/' . $tipo . '.php';
    }

    private function pegarPermissaoUsuario($acao, $app, $config)
    {
        if (!$config) {
            return false;
        } else if (sessao('USUARIO.dev')) {
            return true;
        }

        $permissaoUsuario = sessao('USUARIO.permissao', padrao: []);
        $permissaoPainel = sessao('PAINEL.permissao.lista', padrao: []);
        return in_array($app . '_' . $acao, $permissaoUsuario) && in_array($app . '_' . $acao, $permissaoPainel);
    }

    private function pegarAppUsado($app, $tipo)
    {
        if (file_exists(ROOT . '/views/pages/painel/' . $app . '/Views/' . $tipo . '/index.view')) {
            return $app . '.Views';
        }
        return 'default';
    }

    /**
     * @param String        $app        App da ação
     * @param Null|String   $id         ID para relacionar
     * @param String        $acao       Ação que está sendo executada
     * @param Mixed         $dado       Dado que estão sendo manipulados
     */
    protected function salvarHistorico(string $app, ?string $id = null, string $acao = '', $dado = ''): bool
    {
        $Entity = new HistoricoEntity(
            app: $app,
            relacionamento: $id,
            acao: $acao,
            dado: $dado
        );
        try {
            $Entity->salvar();
            sessao('HISTORICO_ID', $Entity->id);
            return true;
        } catch (Erro) {
            return false;
        }
    }

    protected function validarSeEstaLogado()
    {
        if (!(new AuthHelper)->validar()) {
            throw new Excecao(
                titulo: 'Usuário deslogado!',
                mensagem: 'Se login expirou, refaça o login para continuar.'
            );
        }
    }

    protected function criptografarListaDado(array $lista, array $permitido = [], array $criptografia = [])
    {
        $Crypt = $criptografia ? new CryptHelper(chavePublica: $this->pegarChavePublica($criptografia)) : null;

        $retorno = [];
        foreach ($lista as $ind => $val) {
            if (!empty($permitido) && !in_array($ind, $permitido)) {
                continue;
            }
            if (is_string($val) && validarData($val)) {
                $val = dataBanco($val);
            } else if (is_string($val) && validarDataHora($val)) {
                $val = dataHoraBanco($val);
            }
            $retorno[$ind] = $criptografia && !empty($val) && in_array($ind, $criptografia) ?
                $Crypt->encode($val) :
                $val;
        }
        return $retorno;
    }
    private function pegarChavePublica(array $criptografia)
    {
        if (empty($criptografia)) {
            return '';
        }
        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-publica')->object();
        return $chave->dado->chave ?? '';
    }
    private function pegarChavePrivada()
    {
        $Api = new ApiHelper(token: true);
        $chave = $Api->get('/admin/chave-privada')->object();
        return $chave->dado->chave ?? '';
    }

    protected function tratarListaDeRetorno($dado, $criptografia, $retorno = 'object')
    {
        if (empty($criptografia)) {
            return $dado;
        }
        $chave = $this->pegarChavePrivada();
        $lista = [];
        foreach ($dado as $ind => $val) {
            if (is_string($ind)) {
                $lista[$ind] = in_array($ind, $criptografia) ? descriptografarDado($val, $criptografia, $chave) : $val;
                continue;
            }
            $dadoDescriptografado = descriptografarDado($val, $criptografia, $chave);
            $lista[$ind] = $retorno == 'object' ? object($dadoDescriptografado) : $dadoDescriptografado;
        }
        return $lista;
    }
}
