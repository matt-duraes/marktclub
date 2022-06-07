<?php

namespace Erro\Retorno;

final class PaginaStatusRetorno
{
    private $metodo;
    private $header;

    /**
     * @param Int       $status         Status HTML da página de retorno podendo ser 400, 401, 403, 404, 500
     */
    public function __construct(
        private int $status
    ) {
        $this->metodo = $_SERVER['REQUEST_METHOD'];
        $this->header = getallheaders();
        $this->render();
    }

    private function render()
    {
        $status = in_array($this->status, [400, 401, 403, 404, 500]) ? $this->status : 400;
        $metodo = $this->metodo;
        $header = $this->header;

        http_response_code($status);
        $contentType = $_SERVER['HTTP_CONTENT_TYPE'] ?? $_SERVER['CONTENT_TYPE'] ?? $_SERVER['HTTP_ACCEPT'] ?? $_SERVER['ACCEPT'] ?? $header['Content-type'] ?? $header['Content-Type'] ?? $header['content-type'] ?? $header['Accept'] ?? $header['accept'] ?? '';
        $contentType = explode(',', $contentType)[0] ?? '';

        if ($metodo == 'GET' && $contentType != 'application/json') {
            if (file_exists(ROOT . '/resources/views/' . mb_strtolower(ROUTE_DIRETORIO, 'UTF-8') . '/status_html/' . $status . '.php')) {
                require_once ROOT . '/resources/views/' . mb_strtolower(ROUTE_DIRETORIO, 'UTF-8') . '/status_html/' . $status . '.php';
            } else {
                require_once ROOT . '/system/Status/' . $status . '.php';
            }
        } elseif (401 == $status) {
            return $this->jsonRetorno('Erro de permissão!', 'Você não autenticou essa requisição.', 401);
        } elseif (403 == $status) {
            return $this->jsonRetorno('Erro de permissão!', 'Você não tem permissão para acessar essa rota.', 403);
        } elseif (400 == $status) {
            return $this->jsonRetorno('Erro de requisição!', 'Foi enviado uma requisição ruim (Bad Request), verifique os dados enviado e tente novamente.', 400);
        } elseif (404 == $status) {
            return $this->jsonRetorno('Página não existe!', 'Essa página não existe ou foi movida para outra URL.', 404);
        } elseif (500 <= $status) {
            return $this->jsonRetorno('Erro interno!', 'Ocorreu um erro interno, por favor, tente novamente.', 500);
        }
    }

    private function jsonRetorno(string $titulo, string $texto, int $codigo)
    {
        echo json_encode(['erro' => true, 'titulo' => $titulo, 'mensagem' => $texto, 'codigo' => $codigo]);
        exit();
    }
}
