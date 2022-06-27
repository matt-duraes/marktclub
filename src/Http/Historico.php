<?php

namespace Http;

use Erro\Erro;
use Route\Route;

final class Historico
{
    /**
     * @param Int       $numero         Indice que deseja pegar a URL
     */
    public function url(int $numero = 0): string
    {
        $historico = $_SESSION['FW_HISTORICO'] ?? false;
        if (empty($historico)) {
            return '';
        }
        $historico = array_reverse($historico);
        $indice = $historico[abs($numero)] ?? [];
        return $indice['url'] ?? '';
    }

    /**
     * @param Int       $numero         Indice que deseja pegar a URL
     */
    public function uri(int $numero = 0): string
    {
        $historico = $_SESSION['FW_HISTORICO'] ?? false;
        if (empty($historico)) {
            return '';
        }
        $historico = array_reverse($historico);
        $indice = $historico[abs($numero)] ?? [];
        return $indice['uri'] ?? '';
    }

    /**
     * @param String    $indice         Qual indice deseja pegar, podendo ser vazio para todos ou url e uri
     */
    public function lista(string $indice = ''): array
    {
        if (!empty($indice) && !in_array($indice, ['url', 'uri'])) {
            throw new Erro(
                mensagem: 'Valor inválido para o parâmetro $indice.',
                titulo: 'Parâmetro incorreto.',
                texto: 'Você passou um valor incorreto para o parâmetro <strong>$indice</strong>.',
                sugestao: [
                    'Remova o parâmetro $indice para pegar a URL e a URI.',
                    'Mude o parâmetro para <strong>url</strong> para pegar a lista de URL e <strong>uri</strong> para pegar a lista de URI.'
                ],
                arquivo: 'trace:0'
            );
        }
        $historico = $_SESSION['FW_HISTORICO'] ?? false;
        if (empty($historico)) {
            return [];
        } elseif (empty($indice)) {
            return $historico;
        }
        $lista = [];
        foreach ($historico as $r) {
            if (!empty($indice)) {
                $lista[] = $r[$indice];
            }
        }
        return $lista;
    }

    /**
     * @param Int       $comeco         Indice que irá começar a deletar o histórico
     * @param Int       $quantidade     Quantidade de históricos que devem ser deletados
     */
    public function deletar(int $comeco = 0, int $quantidade = 1): bool
    {
        $historico = $_SESSION['FW_HISTORICO'] ?? false;
        if (empty($historico)) {
            return true;
        }
        if ($comeco < 0) {
            throw new Erro(
                mensagem: 'Valor negativo no parâmetro $comeco invalido.',
                titulo: 'Valor inválido para o parâmetro',
                texto: 'O parâmetro <strong>$comeco</strong> deve ser um número inteiro e positivo.',
                sugestao: [
                    'Mude o parametro para um número inteiro positivo.'
                ],
                arquivo: 'trace:0'
            );
        } elseif ($quantidade < 1) {
            throw new Erro(
                mensagem: 'Parâmetro $quantidade deve ser maior que zero.',
                titulo: 'Valor inválido para o parâmetro',
                texto: 'O parâmetro <strong>$quantidade</strong> deve ser um número inteiro e maior que zero.',
                sugestao: [
                    'Mude o parametro para um número inteiro maior que zero.'
                ],
                arquivo: 'trace:0'
            );
        }
        for ($i = $comeco; $i < $quantidade; $i++) {
            if (isset($historico[$i])) {
                unset($historico[$i]);
            }
        }
        $_SESSION['FW_HISTORICO'] = array_values($historico);
        return true;
    }

    public function salvar()
    {
        $this->podeSalvarHistorico();
        $uri = $this->pegarUriAtual();
        $link = LINK . $uri;

        if (!isset($_SESSION['FW_HISTORICO'])) {
            $_SESSION['FW_HISTORICO'] = [];
        }

        $maximo = env('HISTORICO_MAXIMO', 20);
        $quantidade = count($_SESSION['FW_HISTORICO']);

        $ultimaUrl = end($_SESSION['FW_HISTORICO'])['url'] ?? '';
        if (empty($link)) {
            return false;
        } elseif (!empty($ultimaUrl) && $ultimaUrl == $link) {
            $_SESSION['FW_HISTORICO'][($quantidade - 1)]['data'] = date('Y-m-d H:i:s');
            return true;
        }

        if ($quantidade >= $maximo) {
            array_shift($_SESSION['FW_HISTORICO']);
        }

        $_SESSION['FW_HISTORICO'][] = [
            'url' => $link,
            'uri' => $uri,
            'data' => date('Y-m-d H:i:s')
        ];
        return true;
    }

    private function podeSalvarHistorico(): bool
    {
        $trace = debug_backtrace();
        if (isset($trace[1]) && isset($trace[1]['file']) && $trace[1]['file'] == '/var/www/html/system/App/FWR.php') {
            return true;
        }
        throw new Erro(
            mensagem: 'Método reservado.',
            texto: '
                Esse método não pode ser acessado pelo usuário,
                este é um metodo exclusivo do sistema.
            ',
            titulo: 'Método reservado pelo sistema'
        );
    }

    private function pegarUriAtual(): bool | string
    {
        $uri = $_SERVER['REQUEST_URI'] ?? false;
        if (false === $uri) {
            return false;
        } elseif (empty($uri)) {
            $uri = '/';
        } elseif (!empty($uri)) {
            $uri = substr($uri, 0, 1) == '/' ? $uri : '/' . $uri;
        }
        return $uri;
    }
}
