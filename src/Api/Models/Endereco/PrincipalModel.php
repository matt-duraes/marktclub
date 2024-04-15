<?php

namespace ApiModel\Endereco;

use ORM\ORM;
use stdClass;
use Where\Where;
use Helpers\LocalizacaoHelper;
use ApiModel\Endereco\Trait\CampoTrait;
use ApiModel\Endereco\Trait\RetornoTrait;

final class PrincipalModel extends ORM
{
    use CampoTrait;
    use RetornoTrait;

    protected string $ormTabela = TABELA_SISTEMA_ENDERECO;
    private Where $Where;
    public string $bairro = '';
    public string $cidade = '';
    public string $estado = '';
    public array $endereco = [];
    public ?stdClass $busca = null;

    public function __construct(
        public string $vinculo,
        public string $local_principal,
        public string $local_secundario,
        private ?string $latitude = null,
        private ?string $longitude = null
    ) {
        parent::__construct();
        $this->pegarEstadoCidade();
        $this->montarWhere();
        $this->buscarPrincipal();
        $this->validarRetorno();
    }

    private function pegarEstadoCidade()
    {
        if (empty($this->latitude) || empty($this->longitude)) {
            return;
        }
        $Localizacao = new LocalizacaoHelper();
        $endereco = $Localizacao->pegarEnderecoPelaGeolocalizacao($this->latitude, $this->longitude, false);

        if ($endereco['pais'] != 'BR') {
            return;
        }

        $this->bairro = $endereco['bairro'];
        $this->cidade = $endereco['cidade'];
        $this->estado = $endereco['estado'];
    }

    private function montarWhere()
    {
        $Where = new Where($this, []);
        $Where
            ->linha('bairro')
            ->linha('cidade')
            ->linha('estado')
            ->linha('vinculo', campo: 'id_vinculo')
            ->linha('local_principal')
            ->linha('local_secundario');

        $this->Where = $Where;
    }

    public function buscarPrincipal()
    {
        $dado = $this
            ->campo($this->pegarCampos())
            ->where($this->Where)
            ->order('principal', 'DESC')
            ->primeiro();

        if (vazio($dado) && !empty($this->estado)) {
            $this->refazerBuscaDiminuindoLocalizacao();
            return;
        }
        $this->busca = $dado;
    }

    private function refazerBuscaDiminuindoLocalizacao()
    {
        if (!empty($this->bairro)) {
            $this->bairro = '';
        } elseif (!empty($this->cidade)) {
            $this->cidade = '';
        } elseif (!empty($this->estado)) {
            $this->estado = '';
        }
        $this->montarWhere();
        $this->buscarPrincipal();
    }

    private function validarRetorno()
    {
        if (vazio($this->busca)) {
            return;
        }
        $this->endereco = $this->montarRetorno([$this->busca])[0] ?? [];
    }
}
