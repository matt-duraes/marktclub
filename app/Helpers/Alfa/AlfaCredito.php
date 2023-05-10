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
                preg_replace("/[^0-9]/", '', env('ALFA_API_BANCO_BYPASS'))
            );
        }
    }

    /**
     * @return bool Se FALSE não foi possível enviar. Se TRUE enviado com sucesso.
     * @throws Excecao Caso de erro na requisição
     */
    public function enviarSolicitacao(): bool
    {
        if ($this->bypass !== null && in_array($this->solicitacaoEntity->documento_cpf, $this->bypass)) {
            return false;
        }

        $mensagemMontada = $this->criarMensagemSolicitacao($this->solicitacaoEntity);

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
     * @param  SolicitacaoEntity  $solicitacaoEntity  Entidade da solicitação
     *
     * @return array Mensagem da solicitação pronta para envio
     */
    private function criarMensagemSolicitacao(SolicitacaoEntity $solicitacaoEntity): array
    {
        $data = date('d/m/Y');

        if ($solicitacaoEntity->tipo->numero() === 1) {
            $template = "
                Solicitação de empréstimo consignado.
                Associação: $solicitacaoEntity->empresa
                Número da Simulação: $solicitacaoEntity->codigo_solicitacao
                Valor: $solicitacaoEntity->valor_emprestimo
                Valor da parcela: $solicitacaoEntity->valor_parcela_atual
                Quantidade de parcelas: $solicitacaoEntity->prazo
                Taxa: $solicitacaoEntity->taxa
                Cidade: $solicitacaoEntity->cidade
                Órgão: $solicitacaoEntity->orgao
                Data: $data
                Mensagem: $solicitacaoEntity->observacao
            ";
        } else {
            $template = "
                Solicitação de portabilidade para empréstimo consignado.
                Associação: $solicitacaoEntity->empresa
                Número da Simulação: $solicitacaoEntity->codigo_solicitacao
                Valor da parcela atual: $solicitacaoEntity->valor_parcela_atual
                Quantidade de parcelas que faltam pagar: $solicitacaoEntity->quantidade_parcelas_restantes
                Taxa do empréstimo atual: $solicitacaoEntity->valor_emprestimo
                Cidade: $solicitacaoEntity->cidade
                Órgão: $solicitacaoEntity->orgao
                Data: $data
                Mensagem: $solicitacaoEntity->observacao
            ";
        }

        return [
            'Convenio'        => 'Markt Club',
            'Mensagem'        => $template,
            'EmpresaOrgao'    => $solicitacaoEntity->empresa,
            'PeriodoDesejado' => 'Manhã',
            'Assunto'         => "Parceria Markt Club + $solicitacaoEntity->empresa + $solicitacaoEntity->nome",
            'DadosPessoais'   => [
                'Nome'                => $solicitacaoEntity->nome,
                'CPF'                 => $solicitacaoEntity->documento_cpf,
                'Email'               => $solicitacaoEntity->email,
                'TelefoneCelular'     => $solicitacaoEntity->telefone_celular,
                'TelefoneResidencial' => $solicitacaoEntity->telefone_fixo,
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
