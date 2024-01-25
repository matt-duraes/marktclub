<?php

namespace Painel\TabelaUsuario\Models;

use Helpers\ListaHelper;
use App\Classes\UsuarioCliente\Situacao;
use Symfony\Component\HttpFoundation\File\UploadedFile;

final class AnalisarModel
{
    public function __construct(
        private ?UploadedFile $arquivo = null
    ) {
        if ($arquivo == null) {
            return;
        }

        if (
            !in_array(
                strCaixaBaixa($arquivo->getClientMimeType()),
                [
                    'text/comma-separated-values', 'text/csv', 'application/vnd.ms-excel',
                    'text/x-comma-separated-values'
                ]
            ) ||
            strCaixaBaixa($arquivo->getClientOriginalExtension()) != 'csv'
        ) {
            mensagemErro('Erro!', 'Não foi possível validar o tipo de arquivo, por favor, envie um arquivo CSV.');
        }

        // $AntiVirus = (new AntiVirusHelper($arquivo->getPathname()));
        // if (!$AntiVirus->validar()) {
        //     mensagemErro('Erro!', 'Não foi possível validar o arquivo enviado.');
        // }
    }

    public function analisarParaSalvar()
    {
        $arquivo = $this->arquivo->getPathname();
        if (($handle = fopen($arquivo, 'r')) === false) {
            mensagemErro('Erro no arquivo!', 'Não foi possível ler o arquivo, por favor, tente novamente.');
        }

        $obrigatorio = sessao('PAINEL.obrigatorio')['usuario_cliente'] ?? ['cpf', 'status'];

        $listaEstado = (new ListaHelper())->uf()->r();
        $listaErro = [];
        $listaOk = [];

        $i = 0;
        while (($registro = fgetcsv($handle, 1000, ';')) !== false) {
            if ($i == 0) {
                $i++;
                continue;
            }

            $linha = $i;
            $i++;

            if (count($registro) > 15) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A linha tem mais colunas que o permitido.'
                ];
                continue;
            } elseif (count($registro) < 15) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A linha tem menos colunas que o permitido.'
                ];
                continue;
            }

            $nome = trim($registro[0]);
            $cpf = trim($registro[1]);
            $siape = trim($registro[2]);
            $telefoneCelular = trim($registro[3]);
            $telefoneFixo = trim($registro[4]);
            $emailPessoal = trim($registro[5]);
            $emailTrabalho = trim($registro[6]);
            $enderecoEstado = trim($registro[7]);
            $enderecoCidade = trim($registro[8]);
            $dataNascimento = trim($registro[9]);
            $genero = trim($registro[10]);
            $situacao = trim($registro[11]);
            $federacao = trim($registro[12]);
            $matricula = trim($registro[13]);
            $grupo = trim($registro[14]);

            // Campo único obrigatório
            if (in_array('cpf', $obrigatorio) && empty($cpf)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'Campo CPF é obrigatório.'
                ];
            }
            if (in_array('matricula', $obrigatorio) && empty($matricula)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'Campo matrícula é obrigatório.'
                ];
            }
            if (in_array('siape', $obrigatorio) && empty($siape)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'Campo siape é obrigatório.'
                ];
            }
            if (empty($nome)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'Campo nome é obrigatório.'
                ];
            }

            // Validando campos
            $cpfValidar = !empty($cpf) ? str_pad($cpf, 11, 0, STR_PAD_LEFT) : '';
            if (!empty($cpf) && !validarCpf($cpfValidar)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo CPF está inválido (' . $cpf . ').'
                ];
            }
            if (!empty($siape) && empty(soNumero($siape))) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo SIAPE está inválido (' . $siape . ').'
                ];
            }
            if (!empty($telefoneCelular) && !ValidarTelefone($telefoneCelular)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo TELEFONE CELULAR está inválido (' . $telefoneCelular . ').'
                ];
            }
            if (!empty($telefoneFixo) && !ValidarTelefone($telefoneFixo)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo TELEFONE FIXO está inválido (' . $telefoneFixo . ').'
                ];
            }
            if (!empty($emailPessoal) && !validarEmail($emailPessoal)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo EMAIL PESSOAL está inválido (' . $emailPessoal . ').'
                ];
            }
            if (!empty($emailTrabalho) && !validarEmail($emailTrabalho)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo EMAIL DE TRABALHO está inválido (' . $emailTrabalho . ').'
                ];
            }
            if (!empty($enderecoEstado) && !in_array($enderecoEstado, $listaEstado)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo ENDEREÇO DO ESTADO está inválido (' . $enderecoEstado . ').'
                ];
            }
            if (!empty($dataNascimento) && !validarDate($dataNascimento)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo DATA NASCIMENTO está inválido (' . $dataNascimento . ').'
                ];
            }
            if (!empty($genero) && !in_array($genero, [1, 2, 'masculino', 'feminino', 'homem', 'mulher', 'm', 'f'])) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo GÊNERO está inválido (' . $genero . ').'
                ];
            }
            if (!empty($federacao) && !in_array($federacao, $listaEstado) && $federacao != 'FU') {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo FEDERAÇÃO está inválido (' . $federacao . ').'
                ];
            }
            if (!empty($matricula) && empty(soNumero($matricula))) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo MATRÍCULA está inválido (' . $matricula . ').'
                ];
            }

            if (!empty($situacao) && !(new Situacao($situacao))->valido()) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'O campo SITUAÇÃO está inválido (' . $situacao . ').'
                ];
            }

            $cpf = soNumero($cpf);
            $siape = soNumero($siape);
            $telefoneCelular = soNumero($telefoneCelular);
            $telefoneFixo = soNumero($telefoneFixo);
            $matricula = soNumero($matricula);

            $dado = [];

            if (!empty($nome)) {
                $dado['nome'] = strCaixaAltaAlta($nome);
            }
            if (!empty($cpf)) {
                $dado['cpf'] = str_pad($cpf, 11, 0, STR_PAD_LEFT);
            }
            if (!empty($siape)) {
                $dado['siape'] = $siape;
            }
            if (!empty($telefoneCelular)) {
                $dado['telefone_celular'] = $telefoneCelular;
            }
            if (!empty($telefoneFixo)) {
                $dado['telefone_fixo'] = $telefoneFixo;
            }
            if (!empty($emailPessoal)) {
                $dado['email_pessoal'] = $emailPessoal;
            }
            if (!empty($emailTrabalho)) {
                $dado['email_trabalho'] = $emailTrabalho;
            }
            if (!empty($enderecoEstado)) {
                $dado['endereco_estado'] = $enderecoEstado;
            }
            if (!empty($enderecoCidade)) {
                $dado['endereco_cidade'] = $enderecoCidade;
            }
            if (!empty($dataNascimento)) {
                $dado['data_nascimento'] = $dataNascimento;
            }
            if (!empty($genero)) {
                $dado['genero'] = $genero;
            }
            if (!empty($situacao)) {
                $dado['situacao'] = $situacao;
            }
            if (!empty($federacao)) {
                $dado['federacao'] = $federacao;
            }
            if (!empty($matricula)) {
                $dado['matricula'] = $matricula;
            }
            if (!empty($grupo)) {
                $dado['grupo'] = $grupo;
            }

            $titulo = '';
            if (in_array('cpf', $obrigatorio)) {
                $titulo = 'CPF: ' . $cpf;
            }
            if (in_array('matricula', $obrigatorio)) {
                $titulo = 'Matrícula: ' . $matricula;
            }
            if (in_array('siape', $obrigatorio)) {
                $titulo = 'SIAPE: ' . $siape;
            }
            if ($dado) {
                $listaOk[] = [
                    'linha'  => $linha,
                    'titulo' => $titulo,
                    'hash'   => base64_encode(jsonEncode($dado))
                ];
            }
        }

        if ($listaOk && !$listaErro) {
            return [
                'status' => 'sucesso',
                'dado'   => $listaOk,
            ];
        }

        return [
            'status' => 'erro',
            'erro'   => $listaErro
        ];
    }

    public function removerHash($hash): array
    {
        return base64Decode($hash, 'hash_upload_tabela_salvar');
    }

    public function analisarParaBloquear()
    {
        $arquivo = $this->arquivo->getPathname();
        if (($handle = fopen($arquivo, 'r')) === false) {
            mensagemErro('Erro no arquivo!', 'Não foi possível ler o arquivo, por favor, tente novamente.');
        }

        $obrigatorio = sessao('PAINEL.obrigatorio')['usuario_cliente'] ?? ['cpf', 'status'];

        $listaErro = [];
        $listaOk = [];

        $i = 0;
        while (($registro = fgetcsv($handle, 1000, ';')) !== false) {
            if ($i == 0) {
                $i++;
                continue;
            }

            $linha = $i;
            $i++;

            if (count($registro) > 1) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A linha tem mais colunas que o permitido.'
                ];
                continue;
            } elseif (count($registro) < 1) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A linha tem menos colunas que o permitido.'
                ];
                continue;
            }

            $chave = is_string($registro[0]) ? trim($registro[0]) : '';
            if (empty($chave)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A chave é obrigatória.'
                ];
                continue;
            }

            $chave =
                in_array('cpf', $obrigatorio) ? str_pad(soNumero($chave), 11, 0, STR_PAD_LEFT) : soNumero($chave);

            if (in_array('cpf', $obrigatorio) && !validarCpf($chave)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A chave não é um CPF válido (' . $chave . ').'
                ];
            } elseif (in_array('matricula', $obrigatorio) && empty($chave)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A chave não é uma matrícula válida (' . $chave . ').'
                ];
            } elseif (in_array('siape', $obrigatorio) && empty($chave)) {
                $listaErro[] = [
                    'linha'    => $linha,
                    'mensagem' => 'A chave não é um SIAPE válido (' . $chave . ').'
                ];
            }

            $dado = ['chave' => $chave];

            $titulo = '';
            if (in_array('cpf', $obrigatorio)) {
                $titulo = 'CPF: ' . $chave;
            }
            if (in_array('matricula', $obrigatorio)) {
                $titulo = 'Matrícula: ' . $chave;
            }
            if (in_array('siape', $obrigatorio)) {
                $titulo = 'SIAPE: ' . $chave;
            }

            $listaOk[] = [
                'linha'  => $linha,
                'titulo' => $titulo,
                'hash'   => base64Encode($dado, true)
            ];
        }

        if (!$listaOk && !$listaErro) {
            mensagemErro('Erro!', 'Não foi encontrado nenhum dado na tabela enviada.');
        }

        if ($listaOk && !$listaErro) {
            return [
                'status' => 'sucesso',
                'dado'   => $listaOk,
            ];
        }

        return [
            'status' => 'erro',
            'erro'   => $listaErro
        ];
    }
}
