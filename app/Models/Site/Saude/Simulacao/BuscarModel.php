<?php

namespace App\Models\Site\Saude\Simulacao;

use stdClass;
use Modules\Data;
use Modules\EnderecoEstado;
use App\Helpers\ClubeApiHelper;

final class BuscarModel extends ClubeApiHelper
{
    public array $retorno = [];

    public function __construct(
        private string $plano,
        private Data $titular,
        private array $dependente = [],
        private ?EnderecoEstado $estado = null,
        private ?string $cidade = null
    )
    {
        parent::__construct();
        $this->montarDependente();
        $this->validarDado();
        $this->fazerSimulacao();
    }

    private function montarDependente()
    {
        $dependente = [];
        foreach($this->dependente as $data)
        {
            $Valor = new Data($data);
            if($Valor->vazio()) {
                continue;
            }
            $dependente[] = $Valor->date();
        }
        $this->dependente = $dependente;
    }

    private function validarDado()
    {
        if(empty($this->plano)) {
            $this->erroPadrao();
        }
        $this->titular->validar(campo: 'Data do titular');
        foreach($this->dependente as $data)
        {
            if(!validarDate($data)) {
                mensagemErro('Erro!', 'A data ' . dataBr($data) . ' do dependente não é válida.');
            }
        }
    }

    private function fazerSimulacao()
    {
        $busca = $this
            ->body([
                'convenio' => $this->plano,
                'titular' => $this->titular->date(),
                'dependente' => $this->dependente,
                'estado' => $this->estado->uf(),
                'cidade' => $this->cidade ? $this->cidade : ''
            ])
            ->post('/saude-simulacao/simular')
            ->array();

        if(!validarIndiceExiste($busca, 'status', valor: 'sucesso')) {
            $this->erroPadrao('Erro na busca da API.');
        }
        $this->retorno = $busca['dado'];
    }

    private function erroPadrao(?string $mensagem = null)
    {
        mensagemErro(
            titulo: 'Erro!',
            mensagem: 'Ocorreu um erro ao fazer a simulação, por favor, tente novamente, caso o erro continue, refaça seu login.',
            localhost: $mensagem
        );
    }
}
