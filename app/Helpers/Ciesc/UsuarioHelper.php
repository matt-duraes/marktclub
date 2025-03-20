<?php

namespace App\Helpers\Ciesc;

use Modules\Cpf;
use Modules\Nome;
use Modules\Email;
use Modules\Genero;
use Helpers\CurlHelper;
use Modules\EnderecoEstado;
use App\Classes\Usuario\Ativar\Ciesc\LocalTrabalho;

final class UsuarioHelper extends CurlHelper
{
    private string $apiLinkToken;
    private string $apiLinkUsuario;
    private string $apiConsumerKey;
    private string $apiConsumerSecret;
    public bool $existe = false;
    private array $usuario = [];
    public Nome $Nome;
    public Genero $Genero;
    public Email $Email;
    public string $cidade;
    public EnderecoEstado $Estado;

    public function __construct(
        public Cpf $Cpf,
        public LocalTrabalho $LocalTrabalho
    ) {
        $this->apiLinkToken = env('CIESC_API_LINK_TOKEN');
        $this->apiLinkUsuario = env('CIESC_API_LINK_USUARIO');
        $this->apiConsumerKey = env('CIESC_API_CONSUMER_KEY');
        $this->apiConsumerSecret = env('CIESC_API_CONSUMER_SECRET');

        $Cpf->validar('CPF');
        if(!in_array($LocalTrabalho->indice(), ['SENAI', 'SESI', 'IEL', 'SIAPE', 'CIESC'])) {
            mensagemErro('Campo obrigatório!', 'O campo Local de trabalho é obrigatório.');
        }

        $this->buscarUsuario($Cpf, $LocalTrabalho);
        $this->validarUsuario();
        $this->setarDadoUsuario();
    }

    private function setarDadoUsuario()
    {
        if (!$this->existe) {
            return;
        }
        $this->Nome = new Nome($this->usuario['nome']);
        $this->Genero = new Genero($this->converterGenero($this->usuario['genero']));
        $this->Email = new Email($this->usuario['email']);
        $this->cidade = $this->usuario['municipio'];
        $this->Estado = new EnderecoEstado($this->usuario['estado']);
    }
    private function converterGenero($genero)
    {
        if(empty($genero)) {
            return '';
        }
        return [
            'M' => 'masculino',
            'F' => 'feminino',
            'Masculino' => 'masculino',
            'Feminino' => 'feminino',
        ][$genero] ?? $genero;
    }

    private function buscarUsuario(Cpf $Cpf, LocalTrabalho $LocalTrabalho)
    {
        if (!eProducao() && in_array($Cpf->numero(), ['61209529009', '91851213040', '41834123070', '29848124098'])) {
            $uf = estadoAleatorio();
            $this->usuario = [
                'beneficiosColaborador' => [
                    'colaborador' => [
                        'nome'      => nomeCompletoAleatorio(),
                        'genero'    => 'M',
                        'email'     => emailAleatorio(),
                        'estado'    => $uf,
                        'municipio' => cidadeAleatorio($uf),
                    ]
                ]
            ];
            return;
        } elseif(!eProducao() && $Cpf->numero() == '01234567890') {
            return;
        }
        $usuario = $this
            ->header([
                'Authorization' => 'Bearer ' . $this->token(),
                'Accept' => 'application/json'
            ])
            ->json([
                'beneficiosColaboradorRequest' => [
                    'empresa'    => $LocalTrabalho->indice(),
                    'cpf'        => $Cpf->cpf(),
                    'periodos'   => [
                        'dataInicio' => dataRemover(hoje(), 1, 'ano'),
                        'dataFim'    => hoje(),
                    ],
                    'situacoes' => [
                        'situacao' => ['TRABALHANDO', 'EMPREGADO'],
                    ]
                ]
            ])
            ->post($this->apiLinkUsuario)
            ->array();
        $this->usuario = $usuario;
    }

    private function validarUsuario()
    {
        $usuario = $this->usuario;
        if (!validarIndiceExiste($usuario, ['beneficiosColaborador.colaborador'])) {
            return;
        }
        $this->existe = true;
        $this->usuario = $usuario['beneficiosColaborador']['colaborador'];
    }

    private function token()
    {
        $token = $this
            ->json([
                'grant_type' => 'client_credentials',
                'Accept' => 'application/json'
            ])
            ->header([
                'Authorization:' => 'Basic ' . base64_encode($this->apiConsumerKey . ':' . $this->apiConsumerSecret)
            ])
            ->post($this->apiLinkToken)
            ->object();

        if(!validarIndiceExiste($token, 'access_token')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao buscar seus dados, por favor, tente novamente.');
        }
        return $token['access_token'];
    }
}
