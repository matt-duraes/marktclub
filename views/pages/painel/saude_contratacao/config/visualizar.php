<?php

use App\Classes\Saude\Operadora;
use App\Classes\Saude\Operadoras\Amil\Planos as PlanoAmil;
use App\Classes\Saude\Operadoras\Amil\Regioes;
use App\Classes\Saude\Operadoras\CNUFlorianopolis\Planos as PlanoCNU;
use App\Classes\SolicitacaoCredito\Status;
use App\Classes\Saude\Acomodacao;
use Modules\EstadoCivil;
use Modules\Genero;

$Painel = new PainelConfig\Visualizar('saude_contratacao');

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->bloco('Usuário', callback: function () use ($Painel) {
        $Painel
            ->linha('usuario->nome', 'Nome')
            ->linha('usuario->email', 'E-mail')
            ->botao(
                'usuario_link',
                'Ver usuário',
                link: LINK . '/app/visualizar/usuario-cliente/usuario->id',
                permissao: \App\Classes\UsuarioCliente\Helper::PERMISSAO_VISUALIZAR
            );
    });

    $Painel->bloco('Simulação', callback: function () use ($Painel) {
        $Painel
            ->data('simulacao->titular', 'Data de nascimento')
            ->linha('simulacao->quantidade_dependente', 'Qtd. Dependentes')
            ->linha('simulacao->operadora', 'Operadora')
            ->linha('simulacao->acomodacao', 'Acomodação')
            ->linha('simulacao->plano', 'Plano')
            ->linha('simulacao->regiao', 'Região')
            ->dinheiro('simulacao->valor_titular', 'Valor p/ titular')
            ->dinheiro('simulacao->valor_total', 'Valor total')
            ->data('simulacao->data_criacao', 'Data de criação');
    });

    $Painel->bloco('Contratação', callback: function () use ($Painel) {
        $Painel
            ->cpf('documento_cpf', 'Documento CPF')
            ->linha('documento_rg', 'RG')
            ->linha('orgao_expedidor', 'Orgão Expedidor')
            ->linha('nome', 'Nome')
            ->data('data_nascimento', 'Data de nascimento')
            ->linha('estado_civil', 'Estado civil')
            ->linha('naturalidade', 'Naturalidade')
            ->linha('genero', 'Gênero')
            ->linha('peso', 'Peso')
            ->linha('altura', 'Altura')
            ->linha('nome_mae', 'Nome da mãe');
    });

    $Painel->bloco('Dados responsável', callback: function () use ($Painel) {
        $Painel
            ->linha('responsavel_nome', 'Nome')
            ->cpf('responsavel_cpf', 'CPF')
            ->linha('responsavel_rg', 'RG')
            ->linha('responsavel_orgao_expedidor', 'Orgão Expedidor');
    });

    $Painel->bloco('Formas de contato', callback: function () use ($Painel) {
        $Painel
            ->email('email_pessoal', 'E-mail')
            ->telefone('telefone_celular', 'Telefone celular')
            ->telefone('telefone_residencial', 'Telefone residencial')
            ->telefone('telefone_comercial', 'Telefone comercial')
            ->linha('telefone_comercial_ramal', 'Telefone comercial ramal');
    });

    $Painel->bloco('Endreço', callback: function () use ($Painel) {
        $Painel
            ->linha('endereco_logradouro', 'Endereço')
            ->cep('endereco_cep', 'CEP')
            ->linha('endereco_estado', 'Estado')
            ->linha('endereco_cidade', 'Cidade')
            ->linha('endereco_bairro', 'Bairro')
            ->linha('endereco_numero', 'Número')
            ->linha('endereco_complemento', 'Complemento');
    });

    $Painel
        ->status(
            campo: 'status',
            texto: 'Cancelado',
            inArray: ['Novo', 'Enviado p/ Parceiro'],
            status: Status::CANCELADO,
            mensagem: 'Tem certeza que deseja alterar o status de cancelado?',
            cor: 'vermelho'
        );
});

$planos = array_merge(
    (new PlanoAmil())->select(),
    (new PlanoCNU())->select()
);

$Painel->include('dependente');
$Painel->js('painel_saude_contratacao_dependente');

$Painel
    ->replace('simulacao->operadora', (new Operadora())->select())
    ->replace('simulacao->plano', $planos)
    ->replace('simulacao->regiao', (new Regioes())->select())
    ->replace('simulacao->acomodacao', (new Acomodacao())->select())
    ->replace('estado_civil', (new EstadoCivil())->select())
    ->replace('genero', (new Genero())->select());

return $Painel;
