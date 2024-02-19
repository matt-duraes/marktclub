<?php

namespace App\Models\Site\Ativar;

use Modules\Cpf;
use Http\Request;
use Modules\Data;
use Modules\Nome;
use Modules\Botao;
use Modules\Email;
use Modules\Genero;
use Modules\Telefone;
use Helpers\ApiHelper;
use Helpers\CryptHelper;
use Modules\EnderecoCep;
use Modules\EstadoCivil;
use Modules\EnderecoEstado;

final class SalvarModel extends ApiHelper
{
    public function __construct(Request $request, CryptHelper $Crypt)
    {
        parent::__construct();

        $dado = $request->dado();
        (new ApiHelper('usuario_cliente:ativar'))
            ->validar('Ocorreu um erro ao ativar seu usuário, por favor, tente novamente.')
            ->body([
                'hash'                 => $dado['hash'],
                'tipo_usuario'         => $dado['tipo_usuario'],
                'empresa'              => sessao('CLUBE')->empresa,
                'nome'                 => $Crypt->encode((new Nome($dado['nome']))->nome()),
                'cpf'                  => $Crypt->encode((new Cpf($dado['cpf']))->numero()),
                'genero'               => $Crypt->encode((new Genero($dado['genero']))->valor()),
                'senha'                => $Crypt->encode($dado['senha']),
                'termo'                => (new Botao($dado['termo']))->valor(),
                'data_nascimento'      => $Crypt->encode((new Data($dado['data_nascimento']))->date()),
                'estado_civil'         => $Crypt->encode((new EstadoCivil($dado['estado_civil']))->valor()),
                'email_pessoal'        => $Crypt->encode((new Email($dado['email_pessoal']))->email()),
                'email_trabalho'       => $Crypt->encode((new Email($dado['email_trabalho']))->email()),
                'telefone_pessoal'     => $Crypt->encode((new Telefone($dado['telefone_pessoal']))->numero()),
                'telefone_trabalho'    => $Crypt->encode((new Telefone($dado['telefone_trabalho']))->numero()),
                'endereco_cep'         => $Crypt->encode((new EnderecoCep($dado['endereco_cep']))->numero()),
                'endereco_logradouro'  => $Crypt->encode($dado['endereco_logradouro']),
                'endereco_numero'      => $Crypt->encode($dado['endereco_numero']),
                'endereco_complemento' => $Crypt->encode($dado['endereco_complemento']),
                'endereco_bairro'      => $Crypt->encode($dado['endereco_bairro']),
                'endereco_estado'      => $Crypt->encode((new EnderecoEstado($dado['endereco_estado']))->uf()),
                'endereco_cidade'      => $Crypt->encode($dado['endereco_cidade']),
            ])
            ->put('/usuario-cliente/ativar');
    }
}
