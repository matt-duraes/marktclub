<?php

namespace App\Helpers\Alfa;

use App\Classes\SolicitacaoAlfa\Tipo;
use Erro\Erro;
use Erro\Excecao;
use Helpers\ApiHelper;

class Credito
{
    /**
     * @var string Link da API
     */
    private string $link;
    /**
     * @var string Id da API
     */
    private string $client_id;
    /**
     * @var string Secret da API
     */
    private string $client_secret;
    /**
     * @var string Usuário da API
     */
    private string $usuario;
    /**
     * @var string Senha da API
     */
    private string $senha;
    /**
     * @var array|null Lista de CPFs para bypassar a validação (SOMENTE PARA FINALIDADE DE TESTE)
     */
    private ?array $bypass = null;

    /**
     * @throws Erro
     */
    public function __construct()
    {
        $envsAlfa = [
            'ALFA_API_BANCO_LINK'          => env('ALFA_API_BANCO_LINK'),
            'ALFA_API_BANCO_CLIENT_ID'     => env('ALFA_API_BANCO_CLIENT_ID'),
            'ALFA_API_BANCO_CLIENT_SECRET' => env('ALFA_API_BANCO_CLIENT_SECRET'),
            'ALFA_API_BANCO_USUARIO'       => env('ALFA_API_BANCO_USUARIO'),
            'ALFA_API_BANCO_SENHA'         => env('ALFA_API_BANCO_SENHA')
        ];

        foreach ($envsAlfa as $index => $value) {
            if (empty($value)) {
                throw new Erro(
                    "Variável de ambiente $index não foi seta ou está vazia",
                    'Variáveis de Ambiente',
                    "A variável de ambiente $index deve ser preenchida corretamente"
                );
            } elseif (!is_string($value)) {
                throw new Erro(
                    "Esperavamos um valor do tipo STRING na variável de ambiente $index",
                    'Tipagem da variável de ambiente',
                    "A variável de ambiente $index deve ser do tipo STRING"
                );
            }
        }

        $this->link = env('ALFA_API_BANCO_LINK');
        $this->client_id = env('ALFA_API_BANCO_CLIENT_ID');
        $this->client_secret = env('ALFA_API_BANCO_CLIENT_SECRET');
        $this->usuario = env('ALFA_API_BANCO_USUARIO');
        $this->senha = env('ALFA_API_BANCO_SENHA');

        if (!empty(env('ALFA_API_BANCO_BYPASS')) && is_string(env('ALFA_API_BANCO_BYPASS'))) {
            $this->bypass = explode(
                ',',
                preg_replace("/[^0-9]/", '', env('ALFA_API_BANCO_BYPASS'))
            );
        }
    }

    /**
     * @param  array  $dados  Dados da solicitação
     *
     * @return bool Se FALSE não foi possível salvar. Se TRUE salvo com sucesso.
     * @throws Excecao E lançada uma excesão caso o tipo seja inválido ou não corresponder ao disponível
     */
    public function salvar(array $dados): bool
    {
        if ($this->bypass !== null && in_array($dados['cpf'], $this->bypass)) {
            return false;
        }

        $mensagemMontada = $this->criarMensagemSolicitacao($dados);

        $resposta = (new ApiHelper())
            ->post($this->link . '')
            ->header([
                'Content-type'        => 'application/json',
                'Authorization'       => 'Bearer',
                'x-ibm-client-id'     => $this->client_id,
                'x-ibm-client-secret' => $this->client_secret,
                'Login'               => $this->usuario,
                'Senha'               => $this->senha
            ])
            ->body($mensagemMontada)
            ->object();

        if (!is_object($resposta) || !isset($resposta->sucesso) || true !== $resposta->sucesso) {
            return false;
        }

        return true;
    }

    /**
     * @param  array  $dados  Dados da solicitação
     *
     * @return array Mensagem da solicitação pronta para envio
     * @throws Excecao
     */
    private function criarMensagemSolicitacao(array $dados): array
    {
        if (!($dados['tipo'] instanceof Tipo)) {
            throw new Excecao('Tipo de solicitação', 'O tipo de solicitação informada não é válida');
        }

        $codigo = $dados['codigo'] ?? '';
        $valor = $dados['valor'] ?? '';
        $prazo = $dados['prazo'] ?? '';
        $parcela = $dados['parcela'] ?? '';
        $nome = $dados['nome'] ?? '';
        $email = $dados['email'] ?? '';
        $cpf = $dados['cpf'] ?? '';
        $telefone_celular = $dados['telefone_celular'] ?? '';
        $telefone_fixo = $dados['telefone_fixo'] ?? '';
        $empresa = $dados['empresa'];
        $cidade = $dados['cidade'];
        $orgao = $dados['orgao'];
        $mensagem = $dados['mensagem'];
        $taxa = $dados['taxa'];

        $data = date('d/m/Y');

        if ($dados['tipo']->valido(1)) {
            $template = "
                Solicitação de empréstimo consignado.
                Associação: $empresa
                Número da Simulação: $codigo
                Valor: $valor
                Valor da parcela: $parcela
                Quantidade de parcelas: $prazo
                Taxa: $taxa
                Cidade: $cidade
                Órgão: $orgao
                Data: $data
                Mensagem: $mensagem
            ";
        } else {
            $template = "
                Solicitação de portabilidade para empréstimo consignado.
                Associação: $empresa
                Número da Simulação: $codigo
                Valor da parcela atual: $parcela
                Quantidade de parcelas que faltam pagar: $prazo
                Taxa do empréstimo atual: $taxa
                Cidade:$cidade
                Órgão: $orgao
                Data: $data
                Mensagem: $mensagem
            ";
        }

        return [
            'Convenio'        => 'Markt Club',
            'Mensagem'        => $template,
            'EmpresaOrgao'    => $empresa,
            'PeriodoDesejado' => 'Manhã',
            'Assunto'         => "Parceria Markt Club + $empresa + $nome",
            'DadosPessoais'   => [
                'Nome'                => $nome,
                'CPF'                 => $cpf,
                'Email'               => $email,
                'TelefoneCelular'     => $telefone_celular,
                'TelefoneResidencial' => $telefone_fixo,
                'TelefoneComercial'   => ''
            ],
            'Produtos'        => [
                'Codigo' => 1,
                'Nome'   => 'Consignado'
            ],
            'ReceberEmailSMS' => false
        ];
    }
}
