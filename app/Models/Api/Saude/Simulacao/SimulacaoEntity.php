<?php

namespace App\Models\Api\Saude\Simulacao;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\Amil;
use App\Classes\Saude\Operadoras\CentralNacionalUnimed;
use App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis;
use App\Classes\Saude\Operadoras\Unimed;
use App\Classes\Saude\Operadoras\UnimedSeguro;
use App\Classes\Saude\Plano;
use App\Classes\Saude\PlanoSaude;
use App\Classes\Saude\Regiao;
use App\Classes\Saude\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use Exception;
use Helpers\ValidarHelper;
use Http\Request;
use Modules\Data;
use Modules\Dinheiro;
use ORM\Entity;

class SimulacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Data $data_nascimento;
    public int $quantidade_dependentes;
    public Operadora $operadora;
    public int $acomodacao;
    public Regiao $regiao;
    public Dinheiro $valor_titular;
    public string $valor_dependentes;
    public Dinheiro $valor_total;
    public Plano $plano;
    public Status $status;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario'       => '->idUsuario'
    ];
    protected array $ormBuscar = [
        'data_nascimento', 'quantidade_dependentes', 'operadora', 'acomodacao',
        'regiao', 'valor_titular', 'valor_dependentes', 'valor_total', 'plano', 'status'
    ];
    protected array $ormSalvar = [
        'data_nascimento', 'quantidade_dependentes', 'operadora', 'acomodacao',
        'regiao', 'valor_titular', 'valor_dependentes', 'valor_total', 'plano', 'status'
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

    /**
     * @throws Exception
     */
    public function regraInsert(): void
    {
        if ($this->request === null) {
            return;
        }

        $acomodacao = $this->request->getPost('acomodacao');
        $this->quantidade_dependentes = 0;
        $this->data_nascimento = new Data($this->request->getPost('data_nascimento'));
        $this->operadora = new Operadora($this->request->getPost('operadora'));
        $this->regiao = new Regiao($this->request->getPost('regiao'));
        $this->plano = new Plano($this->request->getPost('plano'));
        $this->status = new Status(Status::REGISTRADO);
        $planoSaude = match ($this->operadora->indice()) {
            Operadora::UNIMED => new PlanoSaude(
                new Unimed($this->data_nascimento, $acomodacao)
            ),
            Operadora::UNIMED_SEGURO => new PlanoSaude(
                new UnimedSeguro($this->data_nascimento, $acomodacao)
            ),
            Operadora::CENTRAL_NACIONAL_UNIMED => new PlanoSaude(
                new CentralNacionalUnimed($this->data_nascimento, $acomodacao, $this->plano, $this->regiao)
            ),
            Operadora::CENTRAL_NACIONAL_UNIMED_FLORIPA => new PlanoSaude(
                new CentralNacionalUnimedFlorianopolis($this->data_nascimento, $acomodacao, $this->plano)
            ),
            Operadora::AMIL => new PlanoSaude(
                new Amil($this->data_nascimento, $acomodacao, $this->plano, $this->regiao)
            )
        };

        $dependentes = [];
        if (!empty($this->request->getPost('dependentes'))) {
            $dependentes = explode(',', $this->request->getPost('dependentes'));
            $this->quantidade_dependentes = count($dependentes);

            (new ValidarHelper())
                ->valor(
                    $this->quantidade_dependentes,
                    'Quantidade de Dependentes',
                    'Só é permitido no máximo 4 Dependentes'
                )
                ->tamanho('<=', 4, 'numero');

            $contador = 1;
            for ($i = 0; $i < $this->quantidade_dependentes; $i++) {
                (new ValidarHelper())
                    ->valor($dependentes[$i], 'Dependente ' . $contador)
                    ->obrigatorio()
                    ->data();
                $contador++;
            }
        }

        $valor_titular = $planoSaude->valor;
        $valor_dependentes = [];
        $valor_total = $valor_titular;
        if ($this->quantidade_dependentes > 0) {
            $contador = 1;
            for ($i = 0; $i < $this->quantidade_dependentes; $i++) {
                $valor = $planoSaude->simularValor(new Data($dependentes[$i]));
                $valor_dependentes['dependente-' . $contador] = (new Dinheiro((string)$valor))->dinheiro();
                $valor_total = $valor_total + $valor;
                $contador++;
            }
        }

        $this->acomodacao = $planoSaude->codigoAcomodacao;
        $this->valor_titular = new Dinheiro((string)$valor_titular);
        $this->valor_dependentes = jsonEncode($valor_dependentes);
        $this->valor_total = new Dinheiro((string)$valor_total);
    }
}
