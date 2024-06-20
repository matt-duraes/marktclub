<?php

namespace ApiModel\PainelHistorico;

use ORM\ORM;
use stdClass;
use Erro\Excecao;
use Modules\Pagina;
use System\Trait\Model\PaginaTrait;
use System\Classes\PainelHistorico\Acao;
use ApiModel\PainelHistorico\Trait\WhereTrait;
use ApiModel\PainelHistorico\Trait\PropriedadeTrait;

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
            ->campo(['uuid', 'acao', 'mensagem', 'arquivo', 'data_criacao'])
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
                'arquivo'        => $this->montarArquivo(jsonDecode($r->arquivo, true, true)),
                'data_criacao'   => $r->data_criacao
            ];
        }
        return $returno;
    }

    private function montarArquivo(array $arquivo)
    {
        $retorno = [];
        foreach ($arquivo as $val) {
            $link = arquivoPublico(diretorio: 'historico', arquivo: $val, privado: true);
            if (!empty($link)) {
                $retorno[] = $link;
            }
        }
        return $retorno;
    }
}
