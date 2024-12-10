<?php

namespace App\Classes\Painel\Config;

use App\Classes\Painel\Config\Trait\PermissaoTrait;

final class Padrao
{
    use PermissaoTrait;

    public const RECURSOS = [
        'perfil'      => 'Perfil',
        'agenda'      => 'Agenda',
        'google'      => 'Google',
        'assinatura'  => 'Assinatura',
        'atualizacao' => 'Atualizações'
    ];
    public const CAMPOS_OBRIGATORIOS = [
        'usuario_cliente' => [
            'nome', 'cpf', 'email', 'status'
        ]
    ];
    public const UPLOAD_GRUPO = [
        'imagem'      => 'e53ae4e0-7b33-4988-99ad-50433a29b544',
        'arquivo'     => '2d978fba-4bd2-4af7-80bf-ebb94d9ac991',
        'site_config' => '93bb55cb-56fb-4d42-af81-c8100b229348'
    ];
    public const CAMPOS_PERMITIDOS = [
        'usuario_cliente' => [
            'titulo'   => 'Usuário Cliente',
            'recursos' => [
                'geral'    => [
                    'nome'                 => 'Nome',
                    'cpf'                  => 'CPF',
                    'matricula'            => 'Matrícula',
                    'siape'                => 'SIAPE',
                    'genero'               => 'Gênero',
                    'estado_civil'         => 'Estado Civil',
                    'data_nascimento'      => 'Data de Nascimento',
                    'email_trabalho'       => 'E-mail de Trabalho',
                    'email_pessoal'        => 'E-mail de Pessoal',
                    'telefone_pessoal'     => 'Telefone Pessoal',
                    'telefone_trabalho'    => 'Telefone Trabalho',
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'endereco_logradouro'  => 'Logradouro',
                    'pagamento'            => 'Pagamento',
                    'tipo_pagamento'       => 'Metódo de Pagamento',
                    'analytics'            => 'Analytics',
                    'salavip'              => 'Sala VIP',
                    'dependente'           => 'Dependente',
                    'imagem'               => 'Imagem',
                    'primeiro_acesso'      => 'Primeiro Acesso',
                    'mudar_senha'          => 'Mudar Senha',
                    'senha'                => 'Senha',
                    'status'               => 'Status',
                    'data_criacao_de'      => 'Data de Criação de',
                    'data_criacao_ate'     => 'Data de Criação Até',
                    'data_criacao'         => 'Data de Criação',
                    'data_upload'          => 'Data de Upload',
                    'grupo'                => 'Grupo',
                    'empresa'              => 'Empresa',
                    'subempresa'           => 'Sub Empresa',
                    'trabalho_cargo'       => 'Trabalho no Cargo',
                    'trabalho_empresa'     => 'Trabalho na Empresa',
                    'trabalho_data_inicio' => 'Data de Início do Trabalho',
                    'federacao'            => 'Federação',
                    'lead'                 => 'Lead',
                    'origem'               => 'Origem',
                    'tipo'                 => 'Tipo'
                ],
                'download' => [
                    'nome'                 => 'Nome',
                    'cpf'                  => 'CPF',
                    'rg'                   => 'RG',
                    'siape'                => 'SIAPE',
                    'matricula'            => 'Matrícula',
                    'data_nascimento'      => 'Data de Nascimento',
                    'genero'               => 'Gênero',
                    'estado_civil'         => 'Estado Civil',
                    'telefone_pessoal'     => 'Telefone Pessoal',
                    'telefone_trabalho'    => 'Telefone de Trabalho',
                    'email_pessoal'        => 'E-mail Pessoal',
                    'email_trabalho'       => 'E-mail de Trabalho',
                    'email_funcional'      => 'E-mail Funcional',
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'endereco_logradouro'  => 'Logradouro',
                    'empresa'              => 'Empresa',
                    'subempresa'           => 'Sub Empresa',
                    'federacao'            => 'Federação',
                    'trabalho_empresa'     => 'Trabalho na Empresa',
                    'trabalho_cargo'       => 'Trabalho no Cargo',
                    'tipo_pagamento'       => 'Metódo de Pagamento',
                    'grupo'                => 'Grupo',
                    'lead'                 => 'Lead',
                    'origem'               => 'Origem',
                    'data_acesso'          => 'Data de Acesso',
                    'data_upload'          => 'Data de Upload',
                    'data_criacao'         => 'Data de Criação',
                    'data_atualizacao'     => 'Data de Atualização',
                    'status'               => 'Status'
                ]
            ]
        ],
        'usuario_equipe'  => [
            'titulo'   => 'Usuário Equipe',
            'recursos' => [
                'geral' => [
                    'nome'                 => 'Nome',
                    'cpf'                  => 'CPF',
                    'rg'                   => 'RG',
                    'siape'                => 'SIAPE',
                    'matricula'            => 'Matrícula',
                    'data_nascimento'      => 'Data de Nascimento',
                    'genero'               => 'Gênero',
                    'estado_civil'         => 'Estado Civil',
                    'telefone_pessoal'     => 'Telefone Pessoal',
                    'telefone_trabalho'    => 'Telefone de Trabalho',
                    'email_pessoal'        => 'E-mail Pessoal',
                    'email_trabalho'       => 'E-mail de Trabalho',
                    'email_funcional'      => 'E-mail Funcional',
                    'endereco_cidade'      => 'Cidade',
                    'endereco_bairro'      => 'Bairro',
                    'endereco_complemento' => 'Complemento',
                    'endereco_numero'      => 'Número Residencial/Lote',
                    'endereco_logradouro'  => 'Logradouro',
                    'endereco_cep'         => 'CEP',
                    'endereco_estado'      => 'Estado',
                    'empresa'              => 'Empresa',
                    'subempresa'           => 'Sub Empresa',
                    'federacao'            => 'Federação',
                    'trabalho_empresa'     => 'Trabalho na Empresa',
                    'trabalho_cargo'       => 'Trabalho no Cargo',
                    'tipo_pagamento'       => 'Metódo de Pagamento',
                    'grupo'                => 'Grupo',
                    'lead'                 => 'Lead',
                    'origem'               => 'Origem',
                    'senha'                => 'Senha',
                    'mudar_senha'          => 'Mudar Senha',
                    'primeiro_acesso'      => 'Primeito Acesso',
                    'permissao'            => 'Permissão',
                    'data_acesso'          => 'Data de Acesso',
                    'data_upload'          => 'Data de Upload',
                    'data_criacao'         => 'Data de Criação',
                    'data_atualizacao'     => 'Data de Atualização',
                    'status'               => 'Status'
                ]
            ]
        ]
    ];

    public array $PERMISSAO = [];
    public function __construct()
    {
        $this->setarPropriedadePermissao();
    }

    public function scope(array $equipe): array
    {
        $permissoes = $this->montarPermissoes();
        $scopes = [];
        foreach ($equipe as $item) {
            if (!array_key_exists($item, $permissoes) || empty($permissoes[$item])) {
                continue;
            }
            $scopes = array_merge($scopes, $permissoes[$item]);
        }
        return array_values(arrayRemoverValorDuplicado($scopes));
    }

    /**
     * @return array
     */
    public function montarPermissoes(): array
    {
        $retorno = [];
        foreach ($this->PERMISSAO as $painelApp) {
            foreach ($painelApp['permissao'] as $permissao => $apiScope) {
                $scope = $apiScope['scope'] ?? '';
                if (empty($scope)) {
                    $retorno[$permissao] = [];
                    continue;
                }
                $retorno[$permissao] = is_string($scope) ? [$scope] : $scope;
            }
        }
        return $retorno;
    }
}
