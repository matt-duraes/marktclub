<?php

namespace ApiModel\PainelHistorico;

use ORM\ORM;
use stdClass;
use Http\Request;
use System\Trait\Model\PaginaTrait;
use System\Classes\PainelHistorico\Acao;

final class HistoricoModel extends ORM
{
    use PaginaTrait;

    protected string $_tabela = TABELA_PAINEL_HISTORICO;
    private int $idUsuario;

    public function __construct(
        protected Request $request
    ) {
        parent::__construct();
        $this->idUsuario = TOKEN['usuario']->get('id');
    }

    public function listarDados(): stdClass
    {
        $dado = $this
            ->campo(['uuid', 'acao', 'mensagem', 'data_criacao'])
            ->pagina($this->pegarPagina(), 10)
            ->where($this->pegarWhere())
            ->order('id', 'DESC')
            ->tabela(TABELA_USUARIO_EQUIPE)->join('id', 'id_usuario_equipe')
            ->campo(['id', 'nome_real', 'imagem_arquivo', 'imagem_facebook', 'imagem_google', 'imagem_tipo'])
            ->read();

        $dado->lista = $this->montarRetorno($dado->lista);
        return $dado;
    }

    protected function montarRetorno(array $dado): array
    {
        if (!$dado) {
            return [];
        }

        $returno = [];
        foreach ($dado as $r) {
            $returno[] = [
                'id' => $r->uuid,
                'nome' => $r->nome_real,
                'imagem' => imagemUsuario($r->imagem_tipo, $r->imagem_arquivo, $r->imagem_facebook, $r->imagem_google),
                'minha_mensagem' => $this->idUsuario == $r->id,
                'acao' => (new Acao($r->acao))->indice(),
                'mensagem' => $r->mensagem,
                'data_criacao' => $r->data_criacao
            ];
        }
        return $returno;
    }

    protected function pegarWhere(): array
    {
        $request = $this->request;

        $where = [
            ['status', 1],
            ['id_relacionado', 'like', '%"' . $request->relacionado . '"%'],
            ['app', 'like', '%"' . str_replace('-', '_', $request->app) . '"%']
        ];
        $dataDe = $request->data_de;
        if (!empty($dataDe) && validarData($dataDe)) {
            $where[] = ['data_criacao', '>=', dataBanco($dataDe)];
        }
        $dataAte = $request->data_ate;
        if (!empty($dataAte) && validarData($dataAte)) {
            $where[] = ['data_criacao', '<=', dataBanco($dataAte) . ' 23:59:59'];
        }
        $pesquisa = $request->pesquisa;
        if (!empty($pesquisa)) {
            $where[] = ['mensagem', 'like', '%' . $pesquisa . '%'];
        }
        return $where;
    }
}
