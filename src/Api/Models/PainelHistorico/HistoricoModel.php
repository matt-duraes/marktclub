<?php

namespace ApiModel\PainelHistorico;

use ApiModel\PainelHistorico\Trait\PropriedadeTrait;
use ApiModel\PainelHistorico\Trait\WhereTrait;
use Erro\Excecao;
use Modules\Pagina;
use ORM\ORM;
use stdClass;
use System\Classes\PainelHistorico\Acao;
use System\Trait\Model\PaginaTrait;

final class HistoricoModel extends ORM
{
    use PaginaTrait;
    use WhereTrait;
    use PropriedadeTrait;

    protected string $ormTabela = TABELA_PAINEL_HISTORICO;
    private int $idUsuario;
    protected Pagina $pagina;

    public function __construct()
    {
        parent::__construct();
        $this->idUsuario = TOKEN['usuario']->id;
    }

    /**
     * @throws Excecao
     */
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
                'id'             => $r->uuid,
                'nome'           => $r->nome_real,
                'imagem'         => imagemUsuario($r->imagem_tipo, $r->imagem_arquivo, $r->imagem_facebook, $r->imagem_google),
                'minha_mensagem' => $this->idUsuario == $r->id,
                'acao'           => (new Acao($r->acao))->indice(),
                'mensagem'       => $r->mensagem,
                'data_criacao'   => $r->data_criacao
            ];
        }
        return $returno;
    }
}
