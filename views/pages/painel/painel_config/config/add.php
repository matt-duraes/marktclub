<?php

use Helpers\ApiHelper;
use App\Classes\UsuarioCliente\Helper;
use App\Helpers\Painel\ConfiguracoesPadrao;

$Painel = new PainelConfig\Add('painel_config', $acao);

$empresas = (new ApiHelper(token: true))
    ->json(['titulo' => 'Escolha uma empresa'])
    ->get('/comercial-empresa/select')
    ->array()['dado'] ?? [];

$Painel->coluna(callback: function () use ($Painel, $empresas) {
    $Painel->fieldset('Informações do Painel', function () use ($Painel, $empresas) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título interno',
                contador: 100
            )
            ->select(
                name: 'empresa',
                lista: $empresas,
                label: 'Escolha uma empresa',
                permissao: Helper::PERMISSAO_EMPRESA
            );
    });

    $Painel->fieldset('Diretórios de Upload Padrões', function () use ($Painel) {
        $Painel
            ->input(
                name: 'upload_imagem',
                label: 'Imagens',
                placeholder: 'Insira o UUID do diretório padrão de imagens'
            )
            ->input(
                name: 'upload_arquivo',
                label: 'Arquivos',
                placeholder: 'Insira o UUID do diretório padrão de arquivos'
            )
            ->input(
                name: 'site_config',
                label: 'Arquivos do Site (Institucional)',
                placeholder: 'Insira o UUID do diretório padrão de arquivos do site'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Recursos do Painel', function () use ($Painel) {
        $Painel->fieldsetCheckbox(
            callback: function () use ($Painel) {
                foreach (ConfiguracoesPadrao::RECURSOS as $recurso => $nomeRecurso) {
                    $Painel->checkbox(name: 'configuracao[]', label: $nomeRecurso, value: $recurso);
                }
            }
        );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Campos Obrigátorios de Usuário', function () use ($Painel) {
        $Painel->fieldsetCheckbox(
            callback: function () use ($Painel) {
                $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'Nome', value: 'nome');
                $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'CPF', value: 'cpf');
                $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'E-mail', value: 'email');
                $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'Matricula', value: 'matricula');
                $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'SIAPE', value: 'siape');
                $Painel->checkbox(name: 'campo_obrigatorio[]', label: 'Status', value: 'status');
            },
            todos: 'Marcar todos os campos',
            mais: 1
        );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Campos Permitidos', function () use ($Painel) {
        $Painel->fieldsetCheckbox(
            callback: function () use ($Painel) {
                foreach (ConfiguracoesPadrao::CAMPOS_PERMITIDOS as $app => $dado) {
                    $titulo = $dado['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h3>' . $titulo . '</h3>');
                    }

                    if (array_key_exists('geral', $dado['recursos'])) {
                        $Painel->margem('10');
                        $Painel->html('<h4>Geral</h4>');
                        foreach ($dado['recursos']['geral'] as $campo => $nomeCampo) {
                            $Painel->checkbox(name: 'campo_permitido[]', label: $nomeCampo, value: $app . '-geral-' . $campo);
                        }
                    }

                    if (array_key_exists('download', $dado['recursos'])) {
                        $Painel->margem('10');
                        $Painel->html('<h4>Download</h4>');
                        foreach ($dado['recursos']['download'] as $campo => $nomeCampo) {
                            $Painel->checkbox(name: 'campo_permitido[]', label: $nomeCampo, value: $app . '-download-' . $campo);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Permissões', function () use ($Painel) {
        $Painel->fieldsetCheckbox(
            callback: function () use ($Painel) {
                foreach (ConfiguracoesPadrao::PERMISSOES as $ind => $dado) {
                    $titulo = $dado['titulo'] ?? '';
                    if (!empty($titulo)) {
                        $Painel->html('<h3>' . $titulo . '</h3>');
                    }
                    if (array_key_exists('permissao', $dado)) {
                        foreach ($dado['permissao'] as $permissaoFinal => $nomePermissao) {
                            $Painel->checkbox(name: 'permissao[]', label: $nomePermissao, value: $permissaoFinal);
                        }
                    }
                }
            },
            todos: 'Marcar todas as permissões',
            mais: 1
        );
    });
});

return $Painel;
