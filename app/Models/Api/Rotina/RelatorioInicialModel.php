<?php

namespace App\Models\Api\Rotina;

use ORM\ORM;

final class RelatorioInicialModel extends ORM
{
    protected string $_tabela = TABELA_ANALYTICS;
    // Acesso Dia
    private string $sqlAcessoDiaInicio = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `analytics_acesso_dia` (`id` int(9) NOT NULL, `id_admin_empresa` int(9) NOT NULL, `quantidade_total` int(9) NOT NULL, `quantidade_unico` int(9) NOT NULL, `data_acesso` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    private string $sqlAcessoDiaFinal = 'ALTER TABLE `analytics_acesso_dia` ADD PRIMARY KEY (`id`); ALTER TABLE `analytics_acesso_dia` MODIFY `id` int(9) NOT NULL AUTO_INCREMENT; COMMIT;';
    private string $sqlAcessoDiaQuery = 'INSERT INTO `analytics_acesso_dia` (`id`, `id_admin_empresa`, `quantidade_total`, `quantidade_unico`, `data_acesso`) VALUES ';
    private string $sqlAcessoDia = '';
    private int $sqlAcessoDiaId = 1;
    // Dispositivo
    private string $sqlDispositivoInicio = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `analytics_dispositivo` (`id` int(9) NOT NULL, `id_admin_empresa` int(9) NOT NULL, `quantidade` int(9) NOT NULL, `dispositivo` text COLLATE utf8mb4_unicode_ci NOT NULL, `data_acesso` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    private string $sqlDispositivoFinal = 'ALTER TABLE `analytics_dispositivo` ADD PRIMARY KEY (`id`); ALTER TABLE `analytics_dispositivo` MODIFY `id` int(9) NOT NULL AUTO_INCREMENT;COMMIT;';
    private string $sqlDispositivoQuery = 'INSERT INTO `analytics_dispositivo` (`id`, `id_admin_empresa`, `quantidade`, `dispositivo`, `data_acesso`) VALUES ';
    private string $sqlDispositivo = '';
    private int $sqlDispositivoId = 1;
    // Loja
    private string $sqlLojaInicio = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `analytics_loja` (`id` int(9) NOT NULL, `id_admin_empresa` int(9) NOT NULL, `id_parceiro_loja` int(9) DEFAULT NULL, `parceiro_nome` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL, `quantidade` int(9) NOT NULL, `data_acesso` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    private string $sqlLojaFinal = 'ALTER TABLE `analytics_loja` ADD PRIMARY KEY (`id`); ALTER TABLE `analytics_loja` MODIFY `id` int(9) NOT NULL AUTO_INCREMENT; COMMIT;';
    private string $sqlLojaQuery = 'INSERT INTO `analytics_loja` (`id`, `id_admin_empresa`, `id_parceiro_loja`, `parceiro_nome`, `quantidade`, `data_acesso`) VALUES ';
    private string $sqlLoja = '';
    private int $sqlLojaId = 1;
    // Navegador
    private string $sqlNavegadorInicio = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `analytics_navegador` (`id` int(9) NOT NULL, `id_admin_empresa` int(9) NOT NULL, `quantidade` int(9) NOT NULL, `navegador` text COLLATE utf8mb4_unicode_ci NOT NULL, `data_acesso` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    private string $sqlNavegadorFinal = 'ALTER TABLE `analytics_navegador` ADD PRIMARY KEY (`id`); ALTER TABLE `analytics_navegador` MODIFY `id` int(9) NOT NULL AUTO_INCREMENT; COMMIT;';
    private string $sqlNavegadorQuery = 'INSERT INTO `analytics_navegador` (`id`, `id_admin_empresa`, `quantidade`, `navegador`, `data_acesso`) VALUES ';
    private string $sqlNavegador = '';
    private int $sqlNavegadorId = 1;
    // OS
    private string $sqlOsInicio = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `analytics_os` (`id` int(9) NOT NULL, `id_admin_empresa` int(9) NOT NULL, `quantidade` int(9) NOT NULL, `os` text COLLATE utf8mb4_unicode_ci NOT NULL, `data_acesso` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    private string $sqlOsFinal = 'ALTER TABLE `analytics_os` ADD PRIMARY KEY (`id`); ALTER TABLE `analytics_os` MODIFY `id` int(9) NOT NULL AUTO_INCREMENT; COMMIT;';
    private string $sqlOsQuery = 'INSERT INTO `analytics_os` (`id`, `id_admin_empresa`, `quantidade`, `os`, `data_acesso`) VALUES ';
    private string $sqlOs = '';
    private int $sqlOsId = 1;
    // Url
    private string $sqlUrlInicio = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `analytics_pagina` (`id` int(9) NOT NULL, `id_admin_empresa` int(9) NOT NULL, `quantidade` int(9) NOT NULL, `url` text COLLATE utf8mb4_unicode_ci NOT NULL, `data_acesso` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    private string $sqlUrlFinal = 'ALTER TABLE `analytics_pagina` ADD PRIMARY KEY (`id`); ALTER TABLE `analytics_pagina` MODIFY `id` int(9) NOT NULL AUTO_INCREMENT; COMMIT;';
    private string $sqlUrlQuery = 'INSERT INTO `analytics_pagina` (`id`, `id_admin_empresa`, `quantidade`, `url`, `data_acesso`) VALUES ';
    private string $sqlUrl = '';
    private int $sqlUrlId = 1;
    // Usuario
    private string $sqlUsuarioInicio = 'SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO"; START TRANSACTION; SET time_zone = "+00:00"; CREATE TABLE `analytics_usuario` (`id` int(9) NOT NULL,`id_admin_empresa` int(9) NOT NULL,`id_usuario_cliente` int(1) NOT NULL,`usuario_cpf` varchar(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,`usuario_nome` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,`quantidade` int(9) NOT NULL,`data_acesso` date NOT NULL) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;';
    private string $sqlUsuarioFinal = 'ALTER TABLE `analytics_usuario` ADD PRIMARY KEY (`id`); ALTER TABLE `analytics_usuario` MODIFY `id` int(9) NOT NULL AUTO_INCREMENT; COMMIT;';
    private string $sqlUsuarioQuery = 'INSERT INTO `analytics_usuario` (`id`, `id_admin_empresa`, `id_usuario_cliente`, `usuario_cpf`, `usuario_nome`, `quantidade`, `data_acesso`) VALUES ';
    private string $sqlUsuario = '';
    private int $sqlUsuarioId = 1;

    public function criarArquivoDoAno(int $ano, ?int $cortar = null)
    {
        $dado = $this
            ->campo([
                'id', 'empresa', 'data_criacao', 'usuario', 'vinculo', 'vinculo_nome', 'url',
                'usuario_nome', 'usuario_cpf', 'dispositivo', 'os', 'browser'
            ])
            ->where($this->pegarWhere($ano, $cortar))
            ->read();

        $lista = [];
        foreach ($dado as $r) {
            $data = dataBanco($r->data_criacao);
            if (!array_key_exists($data, $lista)) {
                $lista[$data] = [];
            }
            $lista[$data][] = $r;
        }
        foreach ($lista as $data => $json) {
            $fp = fopen(DIRETORIO_PRIVADO . "/analytics/" . $data . ".json", "a+");
            fwrite($fp, json_encode($json));
            fclose($fp);
        }
    }
    private function pegarWhere(int $ano, ?int $cortar)
    {
        if ($cortar == 1) {
            return ['data_criacao', 'between', [$ano . '-01-01', $ano . '-06-30 23:59:59']];
        } else if ($cortar == 2) {
            return ['data_criacao', 'between', [$ano . '-07-01', $ano . '-12-31 23:59:59']];
        }
        return ['data_criacao', 'between', [$ano . '-01-01', $ano . '-12-31 23:59:59']];
    }

    public function rodarRelatorioAno()
    {
        $this->pegarListaArquivo();
        $this->salvarArquivoSql();
    }

    private function pegarListaArquivo()
    {
        $listaArquivo = listarArquivoDiretorio(DIRETORIO_PRIVADO . '/analytics', ext: ['json']);
        foreach ($listaArquivo as $arquivo) {
            $data = str_replace('.json', '', $arquivo);
            $lista = jsonDecode(file_get_contents(DIRETORIO_PRIVADO . '/analytics/' . $arquivo));
            if (!is_array($lista) || empty($lista)) {
                continue;
            }

            $this->montarDadoAnalytics($data, $lista);
        }
    }

    private function montarDadoAnalytics($data, $lista)
    {

        $analyticsUnico = [];
        $analytics = [];
        foreach ($lista as $r) {
            // Extrutura inicial
            if (!array_key_exists($r->empresa, $analytics)) {
                $analytics[$r->empresa] = [
                    'dia' => [
                        'id_admin_empresa' => $r->empresa,
                        'quantidade_total' => 0,
                        'quantidade_unico' => 0,
                        'data_acesso' => $data
                    ],
                    'loja' => [],
                    'url' => [],
                    'cliente' => [],
                    'os' => [],
                    'navegador' => [],
                    'dispositivo' => [],
                ];
            }

            if (empty($r->dispositivo)) {
                $r->dispositivo = 'Não identificado';
            }
            if (empty($r->os)) {
                $r->os = 'Não identificado';
            }
            if (empty($r->browser)) {
                $r->browser = 'Não identificado';
            }

            // Analytics
            $analytics[$r->empresa]['dia']['quantidade_total']++;
            $comparar = dataBanco($r->data_criacao) . '-' . $r->usuario;
            if (!array_key_exists($comparar, $analyticsUnico)) {
                $analytics[$r->empresa]['dia']['quantidade_unico']++;
                $analyticsUnico[$comparar] = true;
            }

            // Convenio
            $eConvenio = !empty($r->vinculo) && (str_starts_with($r->url, '/convenios') || str_starts_with($r->url, '/parceiro'));
            if ($eConvenio && !array_key_exists($r->vinculo, $analytics[$r->empresa]['loja'])) {
                $analytics[$r->empresa]['loja'][$r->vinculo] = [
                    'id_admin_empresa' => $r->empresa,
                    'id_parceiro_loja' => $r->vinculo,
                    'parceiro_nome' => $r->vinculo_nome,
                    'quantidade' => 1,
                    'data_acesso' => $data
                ];
            } else if ($eConvenio) {
                $analytics[$r->empresa]['loja'][$r->vinculo]['quantidade']++;
            }

            // URL
            if (!array_key_exists($r->url, $analytics[$r->empresa]['url'])) {
                $analytics[$r->empresa]['url'][$r->url] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'url' => $r->url,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['url'][$r->url]['quantidade']++;
            }

            // CLIENTE
            if (!array_key_exists($r->usuario, $analytics[$r->empresa]['cliente'])) {
                $analytics[$r->empresa]['cliente'][$r->usuario] = [
                    'id_admin_empresa' => $r->empresa,
                    'id_usuario_cliente' => $r->usuario,
                    'usuario_nome' => $r->usuario_nome,
                    'usuario_cpf' => $r->usuario_cpf,
                    'quantidade' => 1,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['cliente'][$r->usuario]['quantidade']++;
            }

            // Dispositivo
            if (!array_key_exists($r->dispositivo, $analytics[$r->empresa]['dispositivo'])) {
                $analytics[$r->empresa]['dispositivo'][$r->dispositivo] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'dispositivo' => $r->dispositivo,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['dispositivo'][$r->dispositivo]['quantidade']++;
            }

            // OS
            if (!array_key_exists($r->os, $analytics[$r->empresa]['os'])) {
                $analytics[$r->empresa]['os'][$r->os] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'os' => $r->os,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['os'][$r->os]['quantidade']++;
            }

            // Navegador
            if (!array_key_exists($r->browser, $analytics[$r->empresa]['navegador'])) {
                $analytics[$r->empresa]['navegador'][$r->browser] = [
                    'id_admin_empresa' => $r->empresa,
                    'quantidade' => 1,
                    'navegador' => $r->browser,
                    'data_acesso' => $data
                ];
            } else {
                $analytics[$r->empresa]['navegador'][$r->browser]['quantidade']++;
            }
        }
        $this->montarArquivoBanco($analytics);
    }
    private function montarArquivoBanco($analitics)
    {
        $sqlAcessoDia = [];
        $sqlLoja = [];
        $sqlCliente = [];
        $sqlDispositivo = [];
        $sqlNavegador = [];
        $sqlOs = [];
        $sqlUrl = [];

        foreach ($analitics as $empresa => $r) {
            // ppe($r);
            // (`id`, `id_admin_empresa`, `quantidade_total`, `quantidade_unico`, `data_acesso`)
            $acessoTotal = $r['dia']['quantidade_total'];
            $acessoUnico = empty($r['dia']['quantidade_unico']) ? 1 : $r['dia']['quantidade_unico'];
            $sqlAcessoDia[] = "(" . $this->sqlAcessoDiaId . "," . $empresa . "," . $acessoTotal . "," . $acessoUnico . ",'" . $r['dia']['data_acesso'] . "')";
            $this->sqlAcessoDiaId++;

            // (`id`, `id_admin_empresa`, `id_parceiro_loja`, `parceiro_nome`, `quantidade`, `data_acesso`)
            foreach ($r['loja'] as $loja) {
                if (empty($loja['parceiro_nome'])) {
                    $loja['parceiro_nome'] = 'Não identificado';
                }
                $sqlLoja[] = "(" . $this->sqlLojaId . "," . $empresa . "," . $loja['id_parceiro_loja'] . ",'" . addslashes($loja['parceiro_nome']) . "'," . $loja['quantidade'] . ",'" . $loja['data_acesso'] . "')";
                $this->sqlLojaId++;
            }

            // (`id`, `id_admin_empresa`, `id_usuario_cliente`, `usuario_cpf`, `usuario_nome`, `quantidade`, `data_acesso`)
            foreach ($r['cliente'] as $cliente) {
                if (empty($cliente['usuario_nome'])) {
                    $cliente['usuario_nome'] = 'Não identificado';
                }
                $sqlCliente[] = "(" . $this->sqlUsuarioId . "," . $empresa . "," . $cliente['id_usuario_cliente'] . ",'" . $cliente['usuario_cpf'] . "','" . addslashes($cliente['usuario_nome']) . "'," . $cliente['quantidade'] . ",'" . $cliente['data_acesso'] . "')";
                $this->sqlUsuarioId++;
            }

            // (`id`, `id_admin_empresa`, `quantidade`, `dispositivo`, `data_acesso`)
            foreach ($r['dispositivo'] as $dispositivo) {
                if (empty($dispositivo['dispositivo'])) {
                    $dispositivo['dispositivo'] = 'Não identificado';
                }
                $sqlDispositivo[] = "(" . $this->sqlDispositivoId . "," . $empresa . "," . $dispositivo['quantidade'] . ",'" . addslashes($dispositivo['dispositivo']) . "','" .  $dispositivo['data_acesso'] . "')";
                $this->sqlDispositivoId++;
            }
            // (`id`, `id_admin_empresa`, `quantidade`, `navegador`, `data_acesso`)
            foreach ($r['navegador'] as $navegador) {
                if (empty($navegador['navegador'])) {
                    $navegador['navegador'] = 'Não identificado';
                }
                $sqlNavegador[] = "(" . $this->sqlNavegadorId . "," . $empresa . "," . $navegador['quantidade'] . ",'" . addslashes($navegador['navegador']) . "','" .  $navegador['data_acesso'] . "')";
                $this->sqlNavegadorId++;
            }
            foreach ($r['os'] as $os) {
                if (empty($os['os'])) {
                    $os['os'] = 'Não identificado';
                }
                $sqlOs[] = "(" . $this->sqlOsId . "," . $empresa . "," . $os['quantidade'] . ",'" . addslashes($os['os']) . "','" .  $os['data_acesso'] . "')";
                $this->sqlOsId++;
            }
            foreach ($r['url'] as $url) {
                if (empty($url['url'])) {
                    $os['url'] = 'Não identificado';
                }
                $sqlUrl[] = "(" . $this->sqlUrlId . "," . $empresa . "," . $url['quantidade'] . ",'" . addslashes($url['url']) . "','" .  $url['data_acesso'] . "')";
                $this->sqlUrlId++;
            }
        }

        if ($sqlAcessoDia) {
            $this->sqlAcessoDia .= $this->sqlAcessoDiaQuery;
            $this->sqlAcessoDia .= implode(',', $sqlAcessoDia) . '; ';
        }
        if ($sqlLoja) {
            $this->sqlLoja .= $this->sqlLojaQuery;
            $this->sqlLoja .= implode(',', $sqlLoja) . '; ';
        }
        if ($sqlCliente) {
            $this->sqlUsuario .= $this->sqlUsuarioQuery;
            $this->sqlUsuario .= implode(',', $sqlCliente) . '; ';
        }
        if ($sqlDispositivo) {
            $this->sqlDispositivo .= $this->sqlDispositivoQuery;
            $this->sqlDispositivo .= implode(',', $sqlDispositivo) . '; ';
        }
        if ($sqlNavegador) {
            $this->sqlNavegador .= $this->sqlNavegadorQuery;
            $this->sqlNavegador .= implode(',', $sqlNavegador) . '; ';
        }
        if ($sqlOs) {
            $this->sqlOs .= $this->sqlOsQuery;
            $this->sqlOs .= implode(',', $sqlOs) . '; ';
        }
        if ($sqlUrl) {
            $this->sqlUrl .= $this->sqlUrlQuery;
            $this->sqlUrl .= implode(',', $sqlUrl) . '; ';
        }
    }

    private function salvarArquivoSql()
    {
        $acessoDia = $this->sqlAcessoDiaInicio . ' ' . $this->sqlAcessoDia . ' ' . $this->sqlAcessoDiaFinal;
        $this->salvarArquivoNoDiretorio('analytics_acesso_dia', $acessoDia);
        $sqlLoja = $this->sqlLojaInicio . ' ' . $this->sqlLoja . ' ' . $this->sqlLojaFinal;
        $this->salvarArquivoNoDiretorio('analytics_loja', $sqlLoja);
        $sqlUsuario = $this->sqlUsuarioInicio . ' ' . $this->sqlUsuario . ' ' . $this->sqlUsuarioFinal;
        $this->salvarArquivoNoDiretorio('analytics_usuario', $sqlUsuario);
        $sqlDispositivo = $this->sqlDispositivoInicio . ' ' . $this->sqlDispositivo . ' ' . $this->sqlDispositivoFinal;
        $this->salvarArquivoNoDiretorio('analytics_dispositivo', $sqlDispositivo);
        $sqlNavegador = $this->sqlNavegadorInicio . ' ' . $this->sqlNavegador . ' ' . $this->sqlNavegadorFinal;
        $this->salvarArquivoNoDiretorio('analytics_navegador', $sqlNavegador);
        $sqlOs = $this->sqlOsInicio . ' ' . $this->sqlOs . ' ' . $this->sqlOsFinal;
        $this->salvarArquivoNoDiretorio('analytics_os', $sqlOs);
        $sqlUrl = $this->sqlUrlInicio . ' ' . $this->sqlUrl . ' ' . $this->sqlUrlFinal;
        $this->salvarArquivoNoDiretorio('analytics_url', $sqlUrl);
    }
    private function salvarArquivoNoDiretorio($arquivo, $conteudo)
    {
        $fp = fopen(DIRETORIO_PRIVADO . "/banco/" . $arquivo . ".sql", "w+");
        fwrite($fp, preg_replace(['/\,$/', '/\,{2,}/'], ['', ''], $conteudo) . ';');
        fclose($fp);
    }
}
