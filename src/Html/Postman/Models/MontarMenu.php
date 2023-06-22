<?php

use Route\Route;

final class MontarMenu
{
    private array $lista = [];
    private string $menu = '';
    private int $temporario = 0;
    public function __construct()
    {
        $this->pegarListaRota();
        $this->criarHtmlMenu();
    }
    public function menu()
    {
        return $this->menu;
    }
    private function criarHtmlMenu()
    {
        $this->menu .= $this->criarHtmlGrupo($this->lista['grupo']);
        foreach ($this->lista['requisicao'] ?? [] as $item) {
            $this->menu .= $this->criarHtmlRequisicao($item);
        }
    }
    private function criarHtmlGrupo($lista)
    {
        if (empty($lista)) {
            return '';
        }
        $html = '';
        foreach ($lista as $grupo) {
            $html .= '
                <div class="grupo fechado">
                    <div class="nome">
                        <i class="pasta pasta_aberta">' . iconePasta(20) . '</i>
                        <i class="pasta pasta_fechada">' . iconePastaAberta(20) . '</i>
                        <p class="nome_grupo">' . $grupo->nome . '</p>
                        <input type="text" class="nome_grupo input_nome display_none" placeholder="Nome do grupo" value="' . $grupo->nome . '">
                        <i class="opcao botao_opcao_grupo">' . iconeOpcao() . '</i>
                    </div>
            ';
            foreach ($grupo->lista as $r) {
                if ($r->tipo == 'diretorio') {
                    $html .= $this->criarHtmlGrupo([$r]);
                    continue;
                } elseif ($r->tipo == 'vazio') {
                    $html .= '<div class="request_vazio">Sem requisição</div>';
                    continue;
                }
                $html .= $this->criarHtmlRequisicao($r);
            }
            $html .= '</div>';
        }
        return $html;
    }
    private function criarHtmlRequisicao($dado)
    {
        if (vazio($dado) || !object_key_exists('dado', $dado)) {
            return '';
        }
        $r = $dado->dado;
        $metodo = $r->metodo == 'POST' ? 'POST' : strCortar($r->metodo, 3, '', true);
        return '
            <div
                class="request" data-id="' . $r->id . '"
                data-metodo="' . $r->metodo . '"
                data-uri="' . $r->uri . '"
            >
                <div class="metodo ' . $r->metodo . '">' . $metodo . '</div>
                <div class="uri">' . $r->uri . '</div>
                <input name="uri" class="input_uri display_none" value="' . $r->uri . '" placeholder="Nome da rota">
                <i class="opcao botao_opcao_requisicao">' . iconeOpcao() . '</i>
            </div>
        ';
    }
    private function pegarListaRota()
    {
        $lista = $this->listarDiretorio(ROOT . '/postman');
        $grupo = $this->ordenarMenuGrupo($lista['grupo'] ?? []);
        $requisicao = $this->ordenarMenuRequisicao($lista['requisicao'] ?? []);
        $this->lista = [
            'grupo' => $grupo,
            'requisicao' => $requisicao
        ];
    }
    private function ordenarMenuGrupo($lista)
    {
        if (!is_array($lista)) {
            return;
        }

        ksort($lista);
        $retorno = [];
        foreach ($lista as $r) {
            $grupo = $this->ordenarMenuGrupo($r->lista['grupo'] ?? []);
            $requisicao = $this->ordenarMenuRequisicao($r->lista['requisicao'] ?? []);
            $lista = array_merge($grupo, $requisicao);
            $retorno[] = (object)[
                'tipo' => 'diretorio',
                'nome' => $r->nome,
                'lista' => !empty($lista) ? $lista : [(object)['tipo' => 'vazio']]
            ];
        }
        return $retorno;
    }
    private function ordenarMenuRequisicao($lista)
    {
        $get = $lista['GET'] ?? [];
        ksort($get);
        $post = $lista['POST'] ?? [];
        ksort($post);
        $put = $lista['PUT'] ?? [];
        ksort($put);
        $delete = $lista['DELETE'] ?? [];
        ksort($delete);
        $lista = array_merge($get, $post, $put, $delete);

        $retorno = [];
        foreach ($lista as $r) {
            $retorno[] = $r;
        }
        return $retorno;
    }
    private function listarDiretorio($path)
    {
        $lista = listarArquivoDiretorio($path);
        $retorno = [];
        foreach ($lista as $item) {
            $arquivo = $path . '/' . $item;
            if (!empty($arquivo) && is_dir($arquivo)) {
                $retorno['grupo'][$item] = (object)[
                    'tipo' => 'diretorio',
                    'nome' => $item,
                    'lista' => $this->listarDiretorio($arquivo)
                ];
                continue;
            } elseif (arquivoExt($arquivo) != 'json') {
                continue;
            }
            $requisicao = $this->montarMenu($path . '/' . $item);
            $retorno['requisicao'][$requisicao->metodo][$requisicao->nome] = (object)[
                'tipo' => 'requisicao',
                'dado' => $requisicao
            ];
        }
        return $retorno;
    }
    private function montarMenu($path)
    {
        if (!file_exists($path)) {
            return $this->menuPadrao();
        }
        $dado = jsonDecode(file_get_contents($path), true, true);
        if (!array_key_exists('id', $dado) || !array_key_exists('metodo', $dado) || !array_key_exists('uri', $dado)) {
            return $this->menuPadrao();
        }
        return (object)$dado;
    }
    private function menuPadrao()
    {
        $this->temporario++;
        return (object)[
            'id' => 'id_' . md5(uniqid(time())),
            'metodo' => 'GET',
            'nome' => 'Temporario ' . $this->temporario,
            'uri' => '/'
        ];
    }
}
