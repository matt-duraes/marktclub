<?php

namespace App\Models\Site\Loja;

use App\Classes\ParceiroLoja\Status;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Helpers\ClubeApiHelper;
use Helpers\MarkdownHelper;
use stdClass;

final class BuscarModel extends ClubeApiHelper
{
    public function __construct(
        private string $url
    ) {
        parent::__construct();
    }

    public function buscarDados(): stdClass
    {
        $dado = $this
            ->validar(mensagem: 'Página não encontrada', status: 404, login: true)
            ->get('/parceiro-loja/' . $this->url)
            ->object();
        if ($dado->dado->status != Status::CONCLUIDO) {
            mensagemStatus(404);
        }

        return $this->montarRetorno($dado->dado);
    }

    private function montarRetorno($r): stdClass
    {
        $tipo = (new TipoLoja($r->tipo_loja))->indice();
        $Texto = new MarkdownHelper();
        return (object)[
            'id'                 => $r->id,
            'titulo'             => $r->titulo,
            'logo'               => $r->imagem_logo,
            'texto_desconto'     => $r->texto_desconto,
            'texto_procedimento' => $r->texto_procedimento,
            'texto_desconto'     => $Texto->texto($r->texto_desconto),
            'texto_procedimento' => $Texto->texto($r->texto_procedimento),
            'texto_descricao'    => nl2br($r->texto_descricao),
            'texto_restrito'     => '',
            'texto_outro'        => '',
            'procedimento'       => $r->tipo_procedimento,
            'capa_desktop'       => $r->imagem_capa_desktop,
            'capa_mobile'        => $r->imagem_capa_mobile,
            'link'               => $this->gerarLink($tipo, $r->link_integracao),
            'url'                => $r->url,
            'desconto'           => $r->desconto,
            'comissao'           => $r->comissao_minima,
            'arquivo'            => $this->buscarArquivo($tipo, $r->arquivo_clube),
            'tipo'               => $tipo,
            'endereco'           => $r->existe_endereco == 'sim',
            'email'              => $r->existe_email == 'sim',
            'telefone'           => $r->existe_telefone == 'sim',
        ];
    }

    private function gerarLink(string $tipo, string $link = null)
    {
        if ($tipo == TipoLoja::CASHBACK && empty($link)) {
            $link .= '&clickref=' . sessao('USUARIO.id');
        }
        return str_replace(
            [
                'http://clube.marktclub.com.br',
                'https://clube.marktclub.com.br',
                'http://clube.youhuul.com.br',
                'https://clube.youhuul.com.br',
            ],
            LINK,
            $link
        );
    }

    private function buscarArquivo($tipo, $arquivo): array
    {
        if ($tipo != TipoLoja::LOJA || empty($arquivo)) {
            return [];
        }
        $dado = $this
            ->body([
                'arquivo' => $arquivo
            ])
            ->post('/upload-arquivo/dado')
            ->object();
        return $this->montarArquivo($dado->dado ?? []);
    }

    private function montarArquivo($arquivo)
    {
        $retorno = [];
        foreach ($arquivo as $r) {
            $retorno[] = (object)[
                'id'      => $r->id,
                'nome'    => $r->nome,
                'arquivo' => $r->arquivo
            ];
        }
        return $retorno;
    }

    /**
     * Busca o parceiro samsung do clube especifico e retorna apenas o link
     * do arquivo
     *
     */
    public function buscarSamsung()
    {
        $dado = $this->get('/parceiro-loja/' . $this->url)->object();
        return $dado->dado->arquivo_clube ?? '';
    }
}
