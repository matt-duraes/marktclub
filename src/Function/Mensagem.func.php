<?php

use Erro\Excecao;
use Http\Response;

if (!function_exists('mensagemErro')) {
    // doc
    /**
     * Retorna uma exceção do sistema baseado nos dados informados
     *
     * @param  string         $titulo    Título para a mensagem de erro
     * @param  string         $mensagem  Texto da mensagem de erro
     * @param  null|int       $status    Status de erro que deseja retornar podendo ser 400, 401, 403, 404 ou 500
     * @param  null|Throwable $error     Throwable do erro original para debugar em localhost
     * @param  null|string    $localhost Mensagem para ser exibida em localhost
     * @throws Excecao        Gera uma excecao do sistema
     */
    function mensagemErro(
        string $titulo,
        string $mensagem,
        ?int $status = null,
        ?Throwable $error = null,
        ?string $localhost = null,
        int $codigo = 0
    ): void {
        $eLocalhost = defined('SISTEMA') && SISTEMA == 'LOCALHOST';
        if ($eLocalhost && !empty($localhost)) {
            $mensagem = $localhost;
        }
        if ($eLocalhost && $error instanceof Throwable) {
            $traducao = [
                'Typed property'                             => 'A propriedade digitada',
                'must not be accessed before initialization' => 'não deve ser acessado antes da inicialização'
            ];
            $errorMensagem = str_replace(
                array_keys($traducao),
                array_values($traducao),
                $error->getMessage()
            );
            $mensagem = '<strong style="font-weight: bold; color: red">Erro localhost: </strong>'
                . $mensagem . PHP_EOL
                . $errorMensagem . PHP_EOL
                . $error->getFile() . PHP_EOL
                . $error->getLine();
        }
        $status = is_int($status) && in_array($status, [400, 401, 403, 404]) ? $status : 400;

        throw new Excecao(titulo: $titulo, mensagem: $mensagem, status: $status, codigo: $codigo);
    }
}
if (!function_exists('mensagemErroVazio')) {
    // doc
    /**
     * Retorna uma exceção do sistema baseado nos dados informados
     *
     * @param  string         $campo     Campo que está validando
     * @param  null|int       $status    Status de erro que deseja retornar podendo ser 400, 401, 403, 404 ou 500
     * @param  null|Throwable $error     Throwable do erro original para debugar em localhost
     * @param  null|string    $localhost Mensagem para ser exibida em localhost
     * @throws Excecao        Gera uma excecao do sistema
     */
    function mensagemErroVazio(
        string $campo,
        ?int $status = null,
        ?Throwable $error = null,
        ?string $localhost = null
    ): void {
        mensagemErro('Campo obrigatório!', 'O campo ' . $campo . ' é obrigatório.', $status, $error, $localhost);
    }
}
if (!function_exists('mensagemErroValido')) {
    // doc
    /**
     * Retorna uma exceção do sistema baseado nos dados informados
     *
     * @param  string         $campo     Campo que está validando
     * @param  null|int       $status    Status de erro que deseja retornar podendo ser 400, 401, 403, 404 ou 500
     * @param  null|Throwable $error     Throwable do erro original para debugar em localhost
     * @param  null|string    $localhost Mensagem para ser exibida em localhost
     * @throws Excecao        Gera uma excecao do sistema
     */
    function mensagemErroValido(
        string $campo,
        ?int $status = null,
        ?Throwable $error = null,
        ?string $localhost = null
    ): void {
        mensagemErro('Campo inválido!', 'O campo ' . $campo . ' está inválido.', $status, $error, $localhost);
    }
}

if (!function_exists('mensagemStatus')) {
    // doc
    /**
     * Retorna uma exceção do sistema com status HTML informado
     *
     * @param  int         $status    Status de erro que deseja retornar podendo ser 400, 401, 403, 404 ou 500
     * @param  null|string $localhost Mensagem para ser exibida em localhost
     * @throws Excecao     Gera uma excecao do sistema
     */
    function mensagemStatus(
        int $status,
        ?Throwable $error = null,
        ?string $localhost = null
    ): void {
        $eLocalhost = defined('SISTEMA') && SISTEMA == 'LOCALHOST';
        if ($eLocalhost && !empty($localhost)) {
            $mensagem = $localhost;
        }
        if ($eLocalhost && $error instanceof Throwable) {
            $traducao = [
                'Typed property'                             => 'A propriedade digitada',
                'must not be accessed before initialization' => 'não deve ser acessado antes da inicialização'
            ];
            $errorMensagem = str_replace(
                array_keys($traducao),
                array_values($traducao),
                $error->getMessage()
            );
            $mensagem = '<strong style="font-weight: bold; color: red">Erro localhost: </strong>'
                . $mensagem . PHP_EOL
                . $errorMensagem . PHP_EOL
                . $error->getFile() . PHP_EOL
                . $error->getLine();
        }
        $status = in_array($status, [400, 401, 403, 404, 500]) ? $status : 400;
        if (!empty($mensagem)) {
            throw new Excecao(mensagem: $mensagem, status: $status);
        }
        throw new Excecao(status: $status);
    }
}

if (!function_exists('mensagemSucesso')) {
    //doc
    /**
     * Gera uma mensagem de sucesso
     *
     * @param array|stdClass $dado         Array ou object com os dados da resposta
     * @param int            $status       Status da resposta podendo ser 200 ou 201
     * @param array          $criptografar Lista de dados para criptografar
     *
     * @return Response     Retorna um ResponseInterface com um array no formato: ["status" => "sucesso", "dado" => $dado]
     * @throws Erro\Excecao Retorna uma exceção caso seja passa um status errado
     */
    function mensagemSucesso(array|stdClass $dado, int $status = 200, array $criptografar = []): Response
    {
        if (!in_array($status, [200, 201])) {
            throw new Excecao(titulo: 'Campo inválido!', mensagem: 'Você passou um status inválido.');
        }

        if ($criptografar) {
            $dado = criptografarDado(dado: $dado, criptografia: $criptografar);
        }

        return new Response(json: [
            'status' => 'sucesso',
            'dado'   => $dado
        ], status: $status);
    }
}
