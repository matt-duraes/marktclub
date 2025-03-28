<?php

namespace App\Helpers\Ciesc;

use Modules\Cpf;
use Modules\Nome;
use Modules\Email;
use Modules\Genero;
use Modules\EnderecoEstado;
use Helpers\LocalizacaoHelper;
use App\Classes\Usuario\Ativar\Ciesc\LocalTrabalho;

final class UsuarioHelper
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
        if (!in_array($LocalTrabalho->indice(), ['SENAI', 'SESI', 'IEL', 'FIESC', 'CIESC'])) {
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

        $uf = (new LocalizacaoHelper())->pegarUfPeloNomeEstado($this->usuario['estado']);
        $this->Nome = new Nome($this->usuario['nome']);
        $this->Genero = new Genero($this->converterGenero($this->usuario['genero']));
        $this->Email = new Email($this->usuario['email']);
        $this->cidade = $this->usuario['municipio'];
        $this->Estado = new EnderecoEstado($uf);
    }

    private function converterGenero($genero)
    {
        if (empty($genero)) {
            return '';
        }
        $genero = strCaixaBaixa($genero);
        return [
            'm'         => 'masculino',
            'f'         => 'feminino',
            'masculino' => 'masculino',
            'feminino'  => 'feminino',
        ][$genero] ?? $genero;
    }

    private function buscarUsuario(Cpf $Cpf, LocalTrabalho $LocalTrabalho)
    {
        if ($this->eUsuarioFake($Cpf)) {
            return;
        }

        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->apiLinkUsuario,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => jsonEncode([
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
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->token(),
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $usuario = jsonDecode(curl_exec($curl), true, true);
        curl_close($curl);

        $this->usuario = $usuario;
    }

    private function eUsuarioFake($Cpf)
    {
        if (eLocalhost() && in_array($Cpf->numero(), ['61209529009', '91851213040', '41834123070', '29848124098'])) {
            $this->usuario = [
                'beneficiosColaborador' => [
                    'item' => [
                        [
                            'nome'      => nomeAleatorio(),
                            'genero'    => 'M',
                            'email'     => emailAleatorio(),
                            'estado'    => estadoAleatorio(),
                            'municipio' => cidadeAleatorio(),
                        ]
                    ]
                ]
            ];
            return true;
        } elseif (eLocalhost() && $Cpf->numero() == '01234567890') {
            return true;
        }
        return false;
    }

    private function validarUsuario()
    {
        $usuario = $this->usuario;
        if (!validarIndiceExiste($usuario, ['beneficiosColaborador.item.0'])) {
            return;
        }
        $this->existe = true;
        $this->usuario = $usuario['beneficiosColaborador']['item'][0] ?? [];
    }

    private function token()
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => $this->apiLinkToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => jsonEncode([
                'grant_type'=> 'client_credentials'
            ]),
            CURLOPT_HTTPHEADER => [
                'Authorization: Basic ' . base64_encode($this->apiConsumerKey . ':' . $this->apiConsumerSecret),
                'Content-Type: application/json',
                'Accept: application/json'
            ],
        ]);

        $token = jsonDecode(curl_exec($curl), true, true);
        curl_close($curl);

        if (!validarIndiceExiste($token, 'access_token')) {
            mensagemErro('Erro!', 'Ocorreu um erro ao buscar seus dados, por favor, tente novamente.');
        }
        return $token['access_token'];
    }
}
