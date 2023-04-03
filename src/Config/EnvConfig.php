<?php

namespace System\Config;

use Exception;

final class EnvConfig
{
    /**
     * @var string
     */
    private string $root = __DIR__ . '/../..';
    /**
     * Env principal do projeto
     *
     * @var array
     */
    private array $envProducao = [];
    /**
     * Env que está vinculado ao domínio do APP
     *
     * @var array
     */
    private array $envUso = [];
    /**
     * @var string
     */
    private string $host = '';
    /**
     * Tipo do APP que está rodando podendo ser PRODUCAO, HOMOLOGACAO E LOCALHOST
     *
     * @var string
     */
    private string $tipoApp = '';

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $this->host = explode(':', $_SERVER['HTTP_HOST'] ?? '')[0];

        $this->pegarEnvProducao();
        $this->pegarEnvLocal();

        define('__ENV_USO', $this->envUso);
        define('__ENV_PRODUCAO', $this->envProducao);
        define('SISTEMA', $this->tipoApp);
    }

    /**
     * @throws Exception
     */
    private function pegarEnvProducao(): void
    {
        $arquivo = __DIR__ . '/../../.env';

        if (!file_exists($arquivo)) {
            throw new Exception(message: 'Você precisa criar um arquivo de configuração .env na raiz do seu projeto.');
        }
        $env = $this->montarEnv($arquivo);

        $this->envUso = $env;
        $this->envProducao = $env;
        $this->setTipoArquivo($env['APP_TIPO'] ?? 'producao');
    }

    /**
     * @throws Exception
     */
    private function montarEnv(string $arquivo): array
    {
        $dado = file($arquivo) ?? [];
        if (!$dado) {
            throw new Exception(
                message: 'Ocorreu um erro ao pegar o arquivo '
                    . $arquivo . ', verifique as permissões do arquivo e tente novamente.'
            );
        }

        $lista = [];
        foreach ($dado as $linha) {
            if (empty(trim($linha))) {
                continue;
            }
            $explode = explode('=', $linha);
            $indice = $explode[0];
            unset($explode[0]);
            $valor = '';
            if ($explode) {
                $valor = trim(implode('=', $explode));
            }
            if (!empty($valor) && is_string($valor) && is_array(jsonDecode($valor, true))) {
                $valor = jsonDecode($valor, true);
            }
            $lista[$indice] = $valor;
        }
        return $lista;
    }

    /**
     * @param  string  $arquivo
     * @return void
     */
    private function setTipoArquivo(string $arquivo): void
    {
        $arquivo = mb_strtoupper($arquivo, 'UTF-8');
        if (in_array($arquivo, ['LOCALHOST', 'PRODUCAO', 'HOMOLOGACAO'])) {
            $this->tipoApp = $arquivo;
            return;
        }
        $this->tipoApp = 'LOCALHOST';
    }

    /**
     * @throws Exception
     */
    private function pegarEnvLocal(): void
    {
        $listaEnv = array_diff(scandir($this->root), ['.', '..']);
        $arquivo = array_filter($listaEnv, function ($valor) {
            if (preg_match('/^\.env\./', $valor)) {
                return $valor;
            }
        });
        if (!$arquivo) {
            return;
        }

        foreach ($arquivo as $arquivo) {
            $env = $this->montarEnv($this->root . '/' . $arquivo);
            $url = $env['APP_URL'] ?? '';
            if (
                !empty($url) &&
                (is_string($url) && $url == $this->host) ||
                (is_array($url) && in_array($this->host, $url))
            ) {
                $this->envUso = $env;
                $this->setTipoArquivo($env['APP_TIPO'] ?? 'localhost');
                break;
            }
        }
    }
}
