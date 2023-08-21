<?php

namespace App\Models\Api\ComunicacaoPublicidade;

use ORM\Entity;
use Modules\Data;
use Helpers\OrmHelper;
use App\Classes\Geral\Status;
use App\Classes\Geral\Publicado;
use App\Classes\ComunicacaoPublicidade\Tipo;

final class PublicidadeEntity extends Entity
{
    protected string $ormTabela = TABELA_COMUNICACAO_PUBLICIDADE;
    protected array $ormBuscar = [
        'titulo', 'data_inicio', 'data_final', 'id_parceiro_loja',
        'data_criacao', 'status', 'imagem_desktop', 'imagem_mobile', 'link', 'tipo'
    ];
    protected array $ormSalvar = [
        'titulo', 'data_inicio', 'data_final', 'id_parceiro_loja',
        'status', 'imagem_desktop', 'imagem_mobile', 'link', 'tipo'
    ];
    protected string $ormValidarSalvar = '
        id_parceiro_loja|Parceiro|obrigatorio|vazio
        titulo|Titulo|obrigatorio|vazio
        data_inicio|Data de início|obrigatorio|vazio|valido
        data_final|Data final|obrigatorio|vazio|valido
        imagem_desktop|Imagem Desktop|obrigatorio|vazio
        tipo|Tipo|obrigatorio|vazio|valido
        status|Status|obrigatorio|vazio|valido
    ';
    protected int $id_parceiro_loja;
    public string|array $parceiro;
    public Status $status;
    public string $titulo;
    public string $link;
    public Tipo $tipo;
    public Data $data_inicio;
    public Data $data_final;
    public string $imagem_desktop;
    public string $imagem_mobile;
    public Publicado $publicado;

    protected function regraSalvar()
    {
        $idParceiro = is_array($this->parceiro) ? $this->parceiro['id'] ?? '' : $this->parceiro;
        $this->id_parceiro_loja = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarIdPeloUuid($idParceiro);
        $this->imagem_desktop = arquivoPrivadoId($this->imagem_desktop);
        $this->imagem_mobile = arquivoPrivadoId($this->imagem_mobile);
        $this->validarDataInicioMenorQueFinal();
    }

    protected function regraPosBuscar()
    {
        $this->setarParceiro();
        $this->imagem_desktop = arquivoPrivado($this->imagem_desktop);
        $this->imagem_mobile = arquivoPrivado($this->imagem_mobile);
        $this->publicado = new Publicado(
            $this->data_inicio,
            $this->data_final,
            $this->status->indice() == Status::ATIVO
        );
    }

    private function setarParceiro()
    {
        $Parceiro = (new OrmHelper(TABELA_PARCEIRO_LOJA))->pegarPrimeiroRegistro(
            where: ['id', $this->id_parceiro_loja],
            campo: ['uuid', 'url', 'titulo', 'imagem']
        );
        $this->parceiro = [
            'id'     => $Parceiro['uuid'],
            'url'    => $Parceiro['url'],
            'logo'   => LINK_ARQUIVO . '/convenio/' . $Parceiro['imagem'],
            'titulo' => $Parceiro['titulo']
        ];
    }

    private function validarDataInicioMenorQueFinal(): void
    {
        if ($this->data_inicio->date() > $this->data_final->date()) {
            mensagemErro('Data Inválida!', 'A data de inicio não pode ser maior que a data final.');
        }
    }
}
