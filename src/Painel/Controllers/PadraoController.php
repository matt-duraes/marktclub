<?php

namespace PainelController;

use stdClass;
use Erro\Excecao;
use Http\Response;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Controller\Controller;

abstract class PadraoController extends Controller
{
    /**
     * Converte o nome do app para o padrão real
     *
     * @param  string $app App que deseja converter
     * @return string
     */
    protected function converterNomeApp(string $app): string
    {
        return str_replace('-', '_', $app);
    }

    /**
     * Converter um nome com padrao nome_aqui para NomeAqui
     *
     * @param  string $nome Nome que deseja converter
     * @return string
     */
    protected function converterNomeParaClass(string $nome): string
    {
        return str_replace(' ', '', strCaixaAltaAlta(str_replace(['_', '-'], ' ', $nome)));
    }

    protected function config(string $app, string $local, ?string $indice = null): stdClass
    {
        if (!file_exists(ROOT . '/views/pages/painel/' . $app)) {
            throw new Excecao(status: 404);
        }

        if ($local == 'index') {
            return $this->configIndex($app);
        } elseif ($local == 'ajax') {
            return $this->configAjax($app, $indice);
        } elseif ($local == 'visualizar') {
            return $this->configVisualizar($app);
        } elseif (in_array($local, ['add', 'editar'])) {
            return $this->configAdd($app, $local);
        } elseif ($local == 'salvar') {
            return $this->configSalvar($app);
        } elseif ($local == 'filtrar') {
            return $this->configFiltrar($app);
        } elseif ($local == 'download') {
            return $this->configDownload($app);
        } elseif ($local == 'deletar') {
            return $this->configDeletar($app);
        } elseif ($local == 'ordem') {
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
        } elseif ($permissaoEditar) {
            $linkAbrir = $Index->pegarLinkEditar();
        }

        return (object)[
            'titulo'    => $config['titulo'] ?? '',
            'permissao' => (object)[
                'buscar'     => $config['buscar'] ?? false,
                'filtrar'    => $config['filtrar'] ?? false,
                'ordem'      => $config['ordem'] ?? false,
                'drag'       => $Index->pegarDrag(),
                'index'      => $this->pegarPermissaoUsuario('index', $app, true),
                'download'   => $this->pegarPermissaoUsuario('download', $app, $config['download'] ?? false),
                'visualizar' => $permissaoVisualizar,
                'add'        => $this->pegarPermissaoUsuario('add', $app, $config['add'] ?? false),
                'editar'     => $permissaoEditar,
                'deletar'    => $this->pegarPermissaoUsuario('deletar', $app, $config['deletar'] ?? false),
                'historico'  => $config['historico'] ?? false
            ],
            'ordem' => $Index->pegarOrdem(),
            'api'   => (object)[
                'uri'          => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ],
            'index' => (object)[
                'app'          => $appUso,
                'grade'        => $Index->pegarGrade(),
                'replace'      => $padrao ? $Index->pegarReplace() : [],
                'ultima_linha' => $padrao ? $Index->pegarUltimaLinha() : '',
                'css'          => $padrao ? $Index->pegarCss() : '',
                'js'           => $padrao ? $Index->pegarJs() : '',
                'copiar'       => $padrao ? $Index->pegarCopiar() : false,
            ],
            'filtrar' => (object) [
                'nome'  => $Filtrar ? $Filtrar->pegarNome() : [],
                'valor' => $Filtrar ? $Filtrar->pegarReplace() : []
            ],
            'abrir' => $linkAbrir
        ];
    }

    private function configAjax($app, $indice): stdClass
    {
        $Ajax = $this->includeConfig('ajax', $app);
        if (!($Ajax instanceof \PainelConfig\Ajax)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Ajax para esse app.');
        }

        $Ajax->indice($indice);

        return (object)[
            'permissao' => $Ajax->pegarPermissao(),
            'rota'      => $Ajax->pegarRota(),
            'metodo'    => $Ajax->pegarMetodo(),
            'request'   => $Ajax->pegarRequest()
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
            'titulo'    => $config['titulo'] ?? '',
            'permissao' => (object)[
                'historico'  => $config['historico'] ?? false,
                'visualizar' => $this->pegarPermissaoUsuario('visualizar', $app, $config['visualizar'] ?? false),
                'status'     => $this->pegarPermissaoUsuario('status', $app, $config['visualizar'] ?? false),
                'editar'     => $permissaoEditar,
            ],
            'visualizar' => (object)[
                'app'     => $appUso,
                'replace' => $padrao ? $Visualizar->pegarReplace() : [],
                'status'  => $padrao ? $Visualizar->pegarStatus() : [],
                'tipo'    => $padrao ? $Visualizar->pegarTipo() : '',
                'html'    => $padrao ? $Visualizar->pegarHtml() : '',
                'css'     => $padrao ? $Visualizar->pegarCss() : '',
                'js'      => $padrao ? $Visualizar->pegarJs() : '',
            ],
            'link' => (object)[
                'editar' => $padrao ? $Visualizar->pegarLinkEditar() : ''
            ],
            'api' => (object)[
                'uri'          => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ],
        ];
    }

    private function configAdd($app, $acao): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $Add = $this->includeConfig($acao, $app);
        if (!($Add instanceof \PainelConfig\Add)) {
            mensagemStatus(500, localhost: 'Não foi encontrado um PainelConfig/Add para esse app.');
        }
        return (object)[
            'titulo'    => $config['titulo'] ?? '',
            'model'     => $config['entity'] ?? '',
            'permissao' => (object)[
                'add'        => $this->pegarPermissaoUsuario('add', $app, $config['add'] ?? false),
                'editar'     => $this->pegarPermissaoUsuario('editar', $app, $config['editar'] ?? false),
                'visualizar' => $this->pegarPermissaoUsuario('visualizar', $app, $config['visualizar'] ?? false),
            ],
            'add' => (object) [
                'app'  => $this->pegarAppUsado($app, 'add'),
                'html' => $Add->pegarHtml(),
                'css'  => $Add->pegarCss(),
                'js'   => $Add->pegarJs(),
                'link' => $Add->pegarLink()
            ],
            'api' => (object)[
                'uri'          => $config['api']['uri'],
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
            'model'     => $config['entity'] ?? '',
            'permissao' => (object)[
                'add'       => $this->pegarPermissaoUsuario('add', $app, $config['add'] ?? false),
                'editar'    => $this->pegarPermissaoUsuario('editar', $app, $config['editar'] ?? false),
                'historico' => $config['historico'] ?? false
            ],
            'salvar' => (object)[
                'salvar' => $campoSalvar,
                'insert' => $campoInsert,
                'update' => $campoUpdate,
            ],
            'api' => (object)[
                'uri'          => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ]
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
                'index'   => $this->pegarPermissaoUsuario('index', $app, true),
                'filtrar' => $config['filtrar'] ?? false,
            ],
            'filtrar' => (object)[
                'app'   => $appUso,
                'input' => $Filtrar->pegarInput(),
                'nome'  => $Filtrar->pegarNome(),
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
                'index'    => $this->pegarPermissaoUsuario('index', $app, true),
                'download' => $this->pegarPermissaoUsuario('download', $app, $config['filtrar'] ?? false)
            ],
            'download' => (object)[
                'app'     => $appUso,
                'html'    => $Download->pegarHtml(),
                'campo'   => $Download->pegarCampo(),
                'replace' => $Download->pegarReplace()
            ],
            'api' => (object)[
                'uri'          => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ]
        ];
    }

    private function configDeletar($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        $deletar = $this->includeConfig('deletar', $app);
        return (object)[
            'permissao' => (object)[
                'deletar'   => $this->pegarPermissaoUsuario('deletar', $app, $config['deletar'] ?? false),
                'historico' => $config['historico'] ?? false
            ],
            'deletar' => (object) [
                'status' => isset($deletar['status']) && is_numeric($deletar['status']) ? (int) $deletar['status'] : null,
                'campo'  => isset($deletar['campo']) && is_numeric($deletar['campo']) ? (int) $deletar['campo'] : null,
            ],
            'api' => (object)[
                'uri'          => $config['api']['uri'],
                'criptografar' => $config['api']['criptografar'] ?? []
            ]
        ];
    }

    private function configOrdem($app): stdClass
    {
        $config = $this->includeConfig('config', $app);
        return (object)[
            'permissao' => (object)[
                'editar' => $this->pegarPermissaoUsuario('editar', $app, true)
            ],
            'api' => (object)[
                'uri'          => $config['api']['uri']
            ]
        ];
    }

    private function includeConfig($acao, $app)
    {
        if ($acao == 'add' && file_exists(ROOT . '/views/pages/painel/' . $app . '/config/insert.php')) {
            $path = ROOT . '/views/pages/painel/' . $app . '/config/insert.php';
        } elseif ($acao == 'editar' && file_exists(ROOT . '/views/pages/painel/' . $app . '/config/update.php')) {
            $path = ROOT . '/views/pages/painel/' . $app . '/config/update.php';
        } elseif (in_array($acao, ['add', 'editar']) && file_exists(ROOT . '/views/pages/painel/' . $app . '/config/add.php')) {
            $path = ROOT . '/views/pages/painel/' . $app . '/config/add.php';
        } elseif (file_exists(ROOT . '/views/pages/painel/' . $app . '/config/' . $acao . '.php')) {
            $path = ROOT . '/views/pages/painel/' . $app . '/config/' . $acao . '.php';
        } else {
            return false;
        }
        return require $path;
    }

    private function pegarPermissaoUsuario($acao, $app, $config)
    {
        if (!$config) {
            return false;
        } elseif (sessao('USUARIO.dev')) {
            return true;
        }

        $permissaoUsuario = sessao('USUARIO.permissao', padrao: []);
        $permissaoPainel = sessao('PAINEL.permissao.lista', padrao: []);
        return in_array($app . '_' . $acao, $permissaoUsuario) && in_array($app . '_' . $acao, $permissaoPainel);
    }

    private function pegarAppUsado($app, $tipo)
    {
        if (file_exists(ROOT . '/views/pages/painel/' . $app . '/Views/' . $tipo . '/index.view')) {
            return $app;
        }
        return 'default';
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
            } elseif (is_string($val) && validarDataHora($val)) {
                $val = dataHoraBanco($val);
            }
            $retorno[$ind] = $criptografia && !empty($val) && in_array($ind, $criptografia) ?
                $Crypt->encode($val) :
                $val;
        }
        return $retorno;
    }

    protected function pegarChavePublica(array $criptografia)
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
        $dado = $this->tratarListaDeRetornoLaco($dado, $criptografia, $retorno, $chave);
        return is_array($dado) && $retorno == 'object' ? object($dado) : $dado;
    }

    private function tratarListaDeRetornoLaco($dado, $criptografia, $retorno, $chave)
    {
        $lista = [];
        foreach ($dado as $ind => $val) {
            if (!is_array($val) && !is_object($val)) {
                $lista[$ind] = in_array($ind, $criptografia) ? descriptografarDado($val, $criptografia, $chave) : $val;
                continue;
            }
            $lista[$ind] = $this->tratarListaDeRetornoLaco(
                is_array($dado) ? $dado[$ind] : $dado->$ind,
                is_string($ind) && array_key_exists($ind, $criptografia) ? $criptografia[$ind] : $criptografia,
                $retorno,
                $chave
            );
        }
        return $lista;
    }

    protected function validarRetornoApi(ApiHelper $dado, $view = false): Response | stdClass
    {
        $status = $dado->status();
        if (true === $view && 401 === $status) {
            return new Response(url: LINK . '/login');
        } elseif (401 === $status) {
            return new Response(json: ['status' => 'deslogado'], status: 401);
        } elseif (204 == $status) {
            return new Response(status: 204);
        }

        $dado = $dado->object();
        $erro = !object_key_exists('status', $dado) || 'sucesso' != $dado->status;
        if ($erro && $view) {
            mensagemStatus(500);
        } elseif ($erro && object_key_exists('erro', $dado)) {
            mensagemErro(
                $dado->erro->titulo ?? 'Erro!',
                $dado->erro->mensagem ?? 'Ocorreu um erro, por favor, tente novamente.'
            );
        }
        return $dado;
    }
}
