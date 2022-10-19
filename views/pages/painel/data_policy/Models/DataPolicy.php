<?php

namespace Painel\DataPolicy\Models;

use Erro\Excecao;
use Helpers\CurlHelper;
use Helpers\DataHelper;
use App\Helpers\DataPolicyHelper;

final class DataPolicy
{
    const HASH = '9e98a5a1-87bc-42a8-bbaf-8280b2081620';

    private DataPolicyHelper $DataPolicy;

    public function __construct()
    {
        $this->DataPolicy = new DataPolicyHelper;
    }
    /*
    |--------------------------------------------------------------------------
    | GERAIS
    |--------------------------------------------------------------------------
    */
    public function pegarSelect()
    {
        $usuarioTipo = sessao('USUARIO.tipo');
        $usuarioTag = sessao('USUARIO.tag');

        $lista = [];
        foreach ($this->DataPolicy->contexto() as $r) {
            if ($usuarioTipo == 1 && !in_array($r->id, $usuarioTag)) {
                continue;
            }
            $lista[$r->id] = $r->nome;
        }
        return $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | CONTEXTO
    |--------------------------------------------------------------------------
    */
    public function contexto(string $uri)
    {
        $dado = $this->DataPolicy->contexto();
        if (!$dado) {
            return [];
        }
        $usuarioTipo = sessao('USUARIO.tipo');
        $usuarioTag = sessao('USUARIO.tag');

        $lista = [];
        foreach ($dado as $r) {
            if ($usuarioTipo == 1 && !in_array($r->id, $usuarioTag)) {
                continue;
            }
            $hash = base64Encode([
                'id' => $r->id,
                'nome' => $r->nome
            ], self::HASH);

            $lista[] = (object)[
                'id' => md5($r->id . 'id_contexto'),
                'nome' => $r->nome,
                'link' => LINK . '/' . $uri . '/lista/' . $hash,
                'data' => $r->data_criacao
            ];
        }
        return $lista;
    }
    public function pegarNomeContexto($hash)
    {
        return base64Decode($hash, self::HASH)['nome'];
    }
    public function pegarHashContexto($hash)
    {
        return base64Decode($hash, self::HASH)['hash_contexto'];
    }
    /**
     * Pegar a página do contexto
     *
     * @param string    $hash   Hash do contexto
     * @param bool      $get    Se true, retornar uma string com o GET da página
     */
    public function pegarPaginaContexto($hash, bool $get = false)
    {
        $pagina = (int) base64Decode($hash, self::HASH)['pagina'];
        if ($get) {
            return $pagina > 1 ? '?pagina=' . $pagina : '';
        }
        return $pagina;
    }

    /*
    |--------------------------------------------------------------------------
    | LISTAR
    |--------------------------------------------------------------------------
    */
    public function listarDados(string $tipo, string $uri, string $hash, int $pagina)
    {
        $contexto = base64Decode($hash, self::HASH)['id'];
        if ($tipo == 'proposicao') {
            $dado = $this->DataPolicy->proposicao($contexto, $pagina);
        } else if ($tipo == 'executivo') {
            $dado = $this->DataPolicy->executivo($contexto, $pagina);
        }
        return $this->montarLista($dado, $uri, $contexto, $pagina, $hash);
    }

    private function montarLista($dado, $uri, $contexto, $pagina, $hashContexto)
    {
        if (!$dado) {
            return [];
        }

        $lista = [];
        foreach ($dado as $r) {
            $hash = base64Encode([
                'id' => $r->id,
                'pagina' => $pagina,
                'contexto' => $contexto,
                'hash_contexto' => $hashContexto
            ], self::HASH);

            $lista[] = (object)[
                'id' => md5($r->id . 'id_proposicao'),
                'nome' => $r->nome,
                'titulo' => $r->ementa,
                'link' => LINK . '/' . $uri . '/detalhe/' . $hash
            ];
        }
        return $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | PROPOSIÇÃO
    |--------------------------------------------------------------------------
    */
    public function proposicao(string $hash)
    {
        $dado = base64Decode($hash, self::HASH);

        $id = $dado['id'];
        $pagina = $dado['pagina'];
        $contexto = $dado['contexto'];

        $lista = $this->DataPolicy->proposicao($contexto, $pagina);
        if (!$lista) {
            throw new Excecao(status: 404);
        }
        foreach ($lista as $r) {
            if ($r->id == $id) {
                return $this->montarProposicao($r);
            }
        }
        throw new Excecao(status: 404);
    }
    private function montarProposicao($r)
    {
        return (object)[
            'id' => md5($r->id . 'id_proposicao'),
            'titulo' => $r->ementa,
            'nome' => $r->nome,
            'fonte' => $r->fonte,
            'prioridade' => $r->prioridade,
            'tipo' => $r->tipo,
            'tipo_decisao' => $r->tipo_decisao,
            'data' => (new DataHelper($r->data_apresentacao . ':00'))->formato('d/m/Y H:i'),
            'status' => $r->status,
            'tramitacao' => $this->montarTramitacao($r->tramitacao),
        ];
    }
    private function montarTramitacao($tramitacao)
    {
        if (!$tramitacao) {
            return [];
        }

        $Data = new DataHelper();
        $lista = [];
        $ordem = [];
        foreach ($tramitacao as $r) {
            $lista[] = (object)[
                'id' => md5($r->id . 'id_tramitacao'),
                'titulo' => $r->descricao,
                'data' => $Data->valor($r->data . ':00')->formato('d/m/Y H:i'),
                'link' => $r->url,
                'ordem' => $r->ordem
            ];
            $ordem[] = ['ordem' => $r->ordem];
        }
        array_multisort($ordem, SORT_ASC, $lista);
        return $lista;
    }

    /*
    |--------------------------------------------------------------------------
    | EXECUTIVO
    |--------------------------------------------------------------------------
    */
    public function executivo(string $hash)
    {
        $dado = base64Decode($hash, self::HASH);

        $id = $dado['id'];
        $pagina = $dado['pagina'];
        $contexto = $dado['contexto'];

        $lista = $this->DataPolicy->executivo($contexto, $pagina);
        if (!$lista) {
            throw new Excecao(status: 404);
        }
        foreach ($lista as $r) {
            if ($r->id == $id) {
                return $this->montarExecutivo($r);
            }
        }
        throw new Excecao(status: 404);
    }
    private function montarExecutivo($r)
    {
        return (object)[
            'id' => md5($r->id . 'id_proposicao'),
            'titulo' => $r->ementa,
            'texto' => str_replace('<p></p>', '', '<p>' . implode('</p><p>', explode(PHP_EOL, $r->inteiro_teor)) . '</p>'),
            'nome' => !empty($r->nome) ? $r->nome : 'Sem nome',
            'numero' => !empty($r->numero) ? $r->numero : 'Sem número',
            'fonte' => !empty($r->fonte) ? $r->fonte : 'Sem fonte',
            'link' => $r->url_dou,
            'tipo' => !empty($r->tipo) ? $r->tipo : 'Sem tipo',
            'data' => !empty($r->data_publicacao) ? (new DataHelper($r->data_publicacao))->formato('d/m/Y') : 'Sem data',
            'status' => !empty($r->status) ? $r->status : 'Sem estatus'
        ];
    }
}
