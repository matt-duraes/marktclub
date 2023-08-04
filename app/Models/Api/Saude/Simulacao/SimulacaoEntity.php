<?php

namespace App\Models\Api\Saude\Simulacao;

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\Amil\Amil;
use App\Classes\Saude\Operadoras\CentralNacionalUnimedFlorianopolis\CentralNacionalUnimedFlorianopolis;
use App\Classes\Saude\Operadoras\Unimed\Unimed;
use App\Classes\Saude\Operadoras\UnimedSeguro\UnimedSeguro;
use App\Classes\Saude\PlanoSaude;
use App\Classes\Saude\Status;
use App\Models\Api\Trait\ValidarEmpresaTrait;
use DateTime;
use Exception;
use Helpers\ValidarHelper;
use Http\Request;
use Modules\Data;
use Modules\Dinheiro;
use ORM\Entity;

class SimulacaoEntity extends Entity
{
    use ValidarEmpresaTrait;

    public Data $titular;
    public int $quantidade_dependentes;
    public Operadora $operadora;
    public int $acomodacao;
    public string $regiao;
    public Dinheiro $valor_titular;
    public string $dependentes;
    public Dinheiro $valor_total;
    public string $plano;
    public Status $status;
    protected ?int $idEmpresa;
    protected ?int $idUsuario;
    protected string $ormTabela = TABELA_SAUDE_SIMULACAO;
    protected array $ormInsert = [
        'id_admin_empresa' => '->idEmpresa',
        'id_usuario'       => '->idUsuario'
    ];
    protected array $ormBuscar = [
        'titular', 'quantidade_dependentes', 'operadora', 'acomodacao',
        'regiao', 'valor_titular', 'dependentes', 'valor_total', 'plano', 'status'
    ];
    protected array $ormSalvar = [
        'titular', 'quantidade_dependentes', 'operadora', 'acomodacao',
        'regiao', 'valor_titular', 'dependentes', 'valor_total', 'plano', 'status'
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

        $this->quantidade_dependentes = 0;
        $this->titular = new Data($this->request->getPost('titular'));
        $this->operadora = new Operadora($this->request->getPost('operadora'));
        $this->regiao = $this->request->getPost('regiao');
        $this->plano = $this->request->getPost('plano');
        $this->status = new Status(Status::REGISTRADO);

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
                    planoSelecionado: $this->plano,
                    acomodacaoSelecionada: $acomodacao
                )
            ),
            Operadora::AMIL => new PlanoSaude(
                new Amil(
                    titular: $this->titular,
                    regiaoSelecionada: $this->regiao,
                    planoSelecionado: $this->plano
                )
            )
        };

        $dependentes = jsonDecode($this->request->getPost('dependentes'), true, true);
        if (!empty($dependentes)) {
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
                $data = new Data($dependentes[$i]);

                (new ValidarHelper())
                    ->valor($data, "{$contador}° Dependente")
                    ->obrigatorio()
                    ->vazio()
                    ->valido();

                if ((new DateTime())->diff((new DateTime($data->date())))->days < 0) {
                    mensagemErro(
                        'Data de Nascimento',
                        "Data de Nascimento do {$contador}° Dependente não é válida"
                    );
                }
                $contador++;
            }
        }

        $valor_titular = $planoSaude->valor;
        $valor_dependentes = [];
        $valor_total = $valor_titular;
        if ($this->quantidade_dependentes > 0) {
            $contador = 1;
            for ($i = 0; $i < $this->quantidade_dependentes; $i++) {
                $dataNascimento = new Data($dependentes[$i]);
                $valor = $planoSaude->simularValor($dataNascimento);
                $valor_dependentes['dependente' . $contador]['data_nascimento'] = $dataNascimento->date();
                $valor_dependentes['dependente' . $contador]['valor'] = (new Dinheiro((string)$valor))->dinheiro();
                $valor_total = $valor_total + $valor;
                $contador++;
            }
        }

        $this->acomodacao = $planoSaude->codigoAcomodacao ?? 0;
        $this->valor_titular = new Dinheiro(number_format($valor_titular, 2, thousands_separator: ''));
        $this->dependentes = jsonEncode($valor_dependentes);
        $this->valor_total = new Dinheiro(number_format($valor_total, 2, thousands_separator: ''));
    }
}
