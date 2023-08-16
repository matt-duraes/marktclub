<?php

namespace App\Models\Api\Saude\Simulacao;

use Exception;
use ORM\Entity;
use Http\Request;
use Modules\Data;
use Modules\Dinheiro;
use Helpers\ValidarHelper;
use App\Classes\Saude\Operadora;
use App\Classes\Saude\PlanoSaude;
use App\Classes\SaudeSimulacao\Status;
use App\Classes\Saude\Operadoras\Amil\Amil;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use App\Classes\Saude\Interface\PlanoInterface;
use App\Classes\Saude\Operadoras\Unimed\Unimed;
use App\Classes\Saude\Interface\RegiaoInterface;
use App\Classes\Saude\Operadoras\Amil\Planos as PlanoAmil;
use App\Classes\Saude\Operadoras\UnimedSeguro\UnimedSeguro;
use App\Classes\Saude\Operadoras\Amil\Regioes as RegiaoAmil;
use App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis\Planos as PlanoCnuFlorianopolis;
use App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis\CentralNacionalUnimedFlorianopolis;

class SimulacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Data $titular;
    public int $quantidade_dependente;
    public Operadora $operadora;
    public int $acomodacao;
    public Dinheiro $valor_titular;
    public array $lista_dependente;
    public Dinheiro $valor_total;
    public null|int|string|PlanoInterface $plano;
    public null|int|string|RegiaoInterface $regiao = null;
    public Status $status;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;
    protected array $ormInsert = [
        'id_admin_empresa'   => '->idEmpresa',
        'id_usuario_cliente' => '->idUsuario'
    ];
    protected array $ormBuscar = [
        'titular', 'quantidade_dependente', 'operadora', 'acomodacao',
        'regiao', 'valor_titular', 'lista_dependente', 'valor_total', 'plano', 'status'
    ];
    protected array $ormSalvar = [
        'titular', 'quantidade_dependente', 'operadora', 'acomodacao',
        'regiao', 'valor_titular', 'lista_dependente', 'valor_total', 'plano', 'status'
    ];

    /**
     * @param Request|null $request
     */
    public function __construct(
        protected readonly ?Request $request = null
    ) {
        $this->validarEmpresa();
        parent::__construct();
    }

    public function regraPosBuscar()
    {
        $this->setarPlano($this->prop('plano'));
        $this->setarRegiao($this->prop('regiao'));
    }

    /**
     * @throws Exception
     */
    public function regraInsert(): void
    {
        if ($this->request === null) {
            return;
        }
        new NovaSimulacaoModel($this->idUsuario);
        $this->lista_dependente = jsonDecode($this->request->lista_dependente, true, true);
        $this->quantidade_dependente = count($this->lista_dependente);
        $this->titular = new Data($this->request->getPost('titular'));
        $this->operadora = new Operadora($this->request->getPost('operadora'));
        $regiao = $this->request->getPost('regiao');
        $plano = $this->request->getPost('plano');
        $this->status = new Status(Status::NOVO);

        $acomodacao = $this->request->getPost('acomodacao');
        $planoSaude = match ($this->operadora->indice()) {
            Operadora::UNIMED => new PlanoSaude(
                new Unimed(
                    titular: $this->titular,
                    acomodacaoSelecionada: $acomodacao
                )
            ),
            Operadora::UNIMED_SEGURO => new PlanoSaude(
                new UnimedSeguro(
                    titular: $this->titular,
                    acomodacaoSelecionada: $acomodacao
                )
            ),
            Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA => new PlanoSaude(
                new CentralNacionalUnimedFlorianopolis(
                    titular: $this->titular,
                    planoSelecionado: $plano,
                    acomodacaoSelecionada: $acomodacao
                )
            ),
            Operadora::AMIL => new PlanoSaude(
                new Amil(
                    titular: $this->titular,
                    regiaoSelecionada: $regiao,
                    planoSelecionado: $plano
                )
            )
        };

        $this->validarDataNascimentoDependente();
        $this->setarPlano($plano);
        $this->setarRegiao($regiao);

        if ($planoSaude->valor === null) {
            mensagemErro('Erro ao tentar simular', 'A Região/Plano não foi encontrado');
        }

        $valor_titular = $planoSaude->valor;
        $valor_dependente = [];
        $valor_total = $valor_titular;
        $contador = 1;
        foreach ($this->lista_dependente as $data) {
            $dataNascimento = new Data($data);
            $valor = $planoSaude->simularValor($dataNascimento);
            $valor_dependente['dependente' . $contador]['data_nascimento'] = $dataNascimento->date();
            $valor_dependente['dependente' . $contador]['valor'] = (new Dinheiro((string)$valor))->decimal();
            $valor_total = $valor_total + $valor;
            $contador++;
        }

        $this->acomodacao = $planoSaude->codigoAcomodacao ?? 0;
        $this->valor_titular = new Dinheiro(number_format($valor_titular, 2, thousands_separator: ''));
        $this->lista_dependente = $valor_dependente;
        $this->valor_total = new Dinheiro(number_format($valor_total, 2, thousands_separator: ''));
    }

    private function setarPlano(mixed $plano)
    {
        if ($this->operadora->indice() == Operadora::AMIL) {
            $this->plano = new PlanoAmil($plano);
        } elseif ($this->operadora->indice() == Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA) {
            $this->plano = new PlanoCnuFlorianopolis($plano);
        }
    }

    private function setarRegiao(mixed $regiao)
    {
        if ($this->operadora->indice() == Operadora::AMIL) {
            $this->regiao = new RegiaoAmil($regiao);
        }
    }

    private function validarDataNascimentoDependente()
    {
        foreach ($this->lista_dependente as $data) {
            $mensagemDataErro = "A data de nascimento {$data} do dependente é invalida.";
            (new ValidarHelper())
                ->valor($data, $mensagemDataErro)
                ->obrigatorio()
                ->vazio()
                ->valido();

            if (dataBanco($data) > hoje()) {
                mensagemErro(
                    'Data de Nascimento',
                    $mensagemDataErro
                );
            }
        }
    }

    protected function getId()
    {
        return $this->prop('id');
    }
}
