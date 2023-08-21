<?php

namespace App\Models\Api\Carteirinha;

use ORM\ORM;
use Erro\Excecao;
use Modules\DataHora;
use App\Classes\Carteirinha\Helper;
use App\Models\Api\UsuarioCliente\ClienteEntity;

class CarteiraModel extends ORM
{
    protected string $ormTabela = TABELA_CARTEIRA;
    private string $linkArquivo;

    public function __construct(
        private ?ClienteEntity $Usuario = null
    ) {
        parent::__construct();
        $this->linkArquivo = LINK_ARQUIVO;
    }

    /**
     * @param ClienteEntity $Usuario
     *
     * @return array
     * @throws Excecao
     */
    public function pegarCarteirinha(): array
    {
        $dado = $this
            ->select()
            ->campo([
                'texto', 'texto_perdido', 'bg_frente', 'bg_fundo'
            ])
            ->where([
                ['id_admin_empresa', $this->Usuario->id_admin_empresa],
                ['status', 'in', Helper::STATUS_LIBERADO]
            ])
            ->tabela(TABELA_COMERCIAL_EMPRESA)
            ->join('id', 'id_admin_empresa')
            ->campo(['cod', 'nome_fantasia'], 'empresa')
            ->tabela(TABELA_CONSTRUTOR_CLUBE)
            ->join('empresa', 'id_admin_empresa')
            ->campo(['cod', 'logo'], 'empresa')
            ->read();

        return $this->montarRetorno($dado ?? []);
    }

    private function montarRetorno($lista)
    {
        $retorno = [];

        $dataEmissao = new DataHora(agora());
        foreach ($lista as $r) {
            $retorno[] = [
                'usuario' => [
                    'nome'            => $this->Usuario->nome->nome(),
                    'matricula'       => $this->Usuario->matricula,
                    'numero_cartao'   => '',
                    'documento'       => $this->Usuario->cpf->cpf() ?? false,
                    'documento_rg'    => $this->Usuario->rg ?? false,
                    'endereco_estado' => $this->Usuario->endereco_estado ?? false
                ],
                'empresa' => [
                    'nome' => $r->empresa_nome_fantasia
                ],
                'texto' => [
                    'principal' => $r->texto,
                    'perdido'   => $r->texto_perdido
                ],
                'imagem' => [
                    'logo'   => $this->linkArquivo . '/construtor/' . $r->empresa_logo,
                    'frente' => $this->linkArquivo . '/construtor/' . $r->bg_frente,
                    'fundo'  => $this->linkArquivo . '/construtor/' . $r->bg_fundo
                ],
                'data' => [
                    'aniversario' => $this->Usuario->aniversario ?? false,
                    'filiacao'    => $this->Usuario->data_filiacao ?? false,
                    'emissao'     => $dataEmissao->data()
                ]
            ];
        }

        return criptografarDado(
            dado: $retorno,
            criptografia: Helper::CRIPTOGRAFAR,
            lista: true
        );
    }
}
