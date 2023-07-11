<?php

namespace App\Helpers\Alfa;

use App\Models\Api\SolicitacaoAlfa\SolicitacaoEntity;
use Erro\Erro;
use Erro\Excecao;
use Helpers\CurlHelper;

class AlfaCredito
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
     * @var SolicitacaoEntity Entidade da solicitação de crédito
     */
    private SolicitacaoEntity $solicitacaoEntity;

    /**
     * @throws Erro
     */
    public function __construct(SolicitacaoEntity $solicitacaoEntity)
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
        $this->solicitacaoEntity = $solicitacaoEntity;

        if (!empty(env('ALFA_API_BANCO_BYPASS')) && is_string(env('ALFA_API_BANCO_BYPASS'))) {
            $this->bypass = explode(
                ',',
                preg_replace('/[^0-9]/', '', env('ALFA_API_BANCO_BYPASS'))
            );
        }
    }

    /**
     * @return bool    Se FALSE não foi possível enviar. Se TRUE enviado com sucesso.
     * @throws Excecao Caso de erro na requisição
     */
    public function enviarSolicitacao(): bool
    {
        if ($this->bypass !== null && in_array($this->solicitacaoEntity->get('documento_cpf'), $this->bypass)) {
            return false;
        }

        $mensagemMontada = $this->criarMensagemSolicitacao();

        $resposta = (new CurlHelper())
            ->post($this->link)
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

        if (!is_object($resposta) || !object_key_exists('sucesso', $resposta) || true !== $resposta->sucesso) {
            return false;
        }

        return true;
    }

    /**
     * @return array   Mensagem da solicitação pronta para envio
     * @throws Excecao
     */
    private function criarMensagemSolicitacao(): array
    {
        $data = date('d/m/Y');
        // phpcs:disable
        if ($this->solicitacaoEntity->get('tipo')->numero() === 1) {
            $template = "
                Solicitação de empréstimo consignado.
                Associação: {$this->solicitacaoEntity->get('empresa')}
                Número da Simulação: {$this->solicitacaoEntity->get('codigo_solicitacao')}
                Valor: {$this->solicitacaoEntity->get('valor_emprestimo')}
                Valor da parcela: {$this->solicitacaoEntity->get('valor_parcela_atual')}
                Quantidade de parcelas: {$this->solicitacaoEntity->get('prazo')}
                Taxa: {$this->solicitacaoEntity->get('taxa')}
                Cidade: {$this->solicitacaoEntity->get('cidade')}
                Órgão: {$this->solicitacaoEntity->get('orgao')}
                Data: $data
                Mensagem: {$this->solicitacaoEntity->get('observacao')}
            ";
        } else {
            $template = "
                Solicitação de portabilidade para empréstimo consignado.
                Associação: {$this->solicitacaoEntity->get('empresa')}
                Número da Simulação: {$this->solicitacaoEntity->get('codigo_solicitacao')}
                Valor da parcela atual: {$this->solicitacaoEntity->get('valor_parcela_atual')}
                Quantidade de parcelas que faltam pagar: {$this->solicitacaoEntity->get('quantidade_parcelas_restantes')}
                Taxa do empréstimo atual: {$this->solicitacaoEntity->get('valor_emprestimo')}
                Cidade: {$this->solicitacaoEntity->get('cidade')}
                Órgão: {$this->solicitacaoEntity->get('orgao')}
                Data: $data
                Mensagem: {$this->solicitacaoEntity->get('observacao')}
            ";
        }

        return [
            'Convenio'        => 'Markt Club',
            'Mensagem'        => $template,
            'EmpresaOrgao'    => $this->solicitacaoEntity->get('empresa'),
            'PeriodoDesejado' => 'Manhã',
            'Assunto'         => "Parceria Markt Club + {$this->solicitacaoEntity->get('empresa')} + {$this->solicitacaoEntity->get('nome')}",
            'DadosPessoais'   => [
                'Nome'                => $this->solicitacaoEntity->get('nome'),
                'CPF'                 => $this->solicitacaoEntity->get('documento_cpf'),
                'Email'               => $this->solicitacaoEntity->get('email'),
                'TelefoneCelular'     => $this->solicitacaoEntity->get('telefone_celular'),
                'TelefoneResidencial' => $this->solicitacaoEntity->get('telefone_fixo'),
                'TelefoneComercial'   => ''
            ],
            'Produtos'        => [
                'Codigo' => 1,
                'Nome'   => 'Consignado'
            ],
            'ReceberEmailSMS' => false
        ];
        // phpcs:enable
    }
}
