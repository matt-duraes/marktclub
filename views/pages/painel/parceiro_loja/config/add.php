<?php

use Helpers\ApiHelper;
use Modules\EnderecoEstado;
use App\Classes\ParceiroLoja\TipoLoja;
use App\Classes\ParceiroLoja\Categoria;
use App\Classes\ParceiroLoja\OrigemLead;
use App\Classes\ParceiroLoja\TipoJuridico;
use App\Classes\ParceiroLoja\PrazoDeclaracao;
use App\Classes\ParceiroLoja\TipoProcedimento;
use App\Classes\ParceiroLoja\TipoEstabelecimento;

$empresa = (new ApiHelper(token: true))->get('/comercial-empresa/select')->array()['dado'] ?? [];
$tag = (new ApiHelper(token: true))->get('/parceiro-subcategoria')->array()['dado'] ?? [];
$diretorioAnexos = sessao('PAINEL.upload_grupo')->anexos ?? '';

$Painel = new PainelConfig\Add(app: 'parceiro_loja', acao: $acao);
$gerente = sessao('USUARIO')['gerente'] ?? '' == 'sim';

$Painel->coluna(callback: function () use ($Painel, $diretorioAnexos) {
    $Painel->fieldset('Logo', function () use ($Painel) {
        $Painel->imagem(name: 'imagem_logo', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });

    $Painel->fieldset('Capa Desktop', function () use ($Painel) {
        $Painel->imagem(name: 'imagem_capa_desktop', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });

    $Painel->fieldset('Capa Mobile', function () use ($Painel) {
        $Painel->imagem(name: 'imagem_capa_mobile', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
});

$Painel->coluna(callback: function () use ($Painel, $gerente) {
    $Painel->fieldset('Dados da empresa', callback: function () use ($Painel) {
        $Painel
            ->input(
                name: 'nome_fantasia',
                label: 'Nome fantasia',
                placeholder: 'Digite um nome fantasia',
                contador: 100,
            )
            ->input(
                name: 'razao_social',
                label: 'Razão social',
                placeholder: 'Digite uma razão social',
                contador: 100,
            )
            ->select(
                name: 'tipo_juridico',
                lista: (new TipoJuridico())->select('Escolha uma opção'),
                label: 'Pessoal física ou jurídica?',
                placeholder: 'Pessoal física ou jurídica?'
            )
            ->cpf(
                name: 'documento_cpf',
                label: 'CPF',
                placeholder: 'Digite um CPF',
                class: 'display_none',
                id: 'bloco_documento_cpf'
            )
            ->cnpj(
                name: 'documento_cnpj',
                label: 'CNPJ',
                placeholder: 'Digite um CNPJ',
                class: 'display_none',
                id: 'bloco_documento_cnpj'
            );
    });

    $Painel->fieldset('Dados do painel', function () use ($Painel, $gerente) {
        $Painel
            ->input(
                name: 'titulo_interno',
                label: 'Título',
                placeholder: 'Digite um título para o painel',
                contador: 100,
            )
            ->select(
                name: 'tipo_loja',
                lista: (new TipoLoja())->select('Escolha uma opção'),
                label: 'Tipo de loja',
                placeholder: 'Escolha um tipo de loja'
            );

        if ($gerente) {
            $equipe = (new ApiHelper(token: true))
                ->get('/usuario-equipe/select')
                ->json(['tipo' => 'convenio', 'titulo' => 'Escolha um operador'])
                ->array()['dado'] ?? [];
            $Painel->select(
                name: 'equipe',
                lista: $equipe,
                label: 'Operador',
                placeholder: 'Escolha um operador'
            );
        } else {
            $Painel->html('<input name="equipe" id="input_equipe" value="' . sessao('USUARIO.id') . '">', acao: 'add');
        }
    });

    $Painel->fieldset('Responsável', function () use ($Painel) {
        $Painel
            ->input(
                name: 'responsavel_nome',
                label: 'Nome do responsável',
                placeholder: 'Digite um nome',
                contador: 100,
            )
            ->input(
                name: 'responsavel_cargo',
                label: 'Cargo',
                placeholder: 'Digite um cargo',
            )
            ->cpf(
                name: 'responsavel_cpf',
                label: 'CPF',
                placeholder: 'Digite um CPF',
            )
            ->telefone(
                name: 'responsavel_telefone',
                label: 'Telefone',
                placeholder: 'Digite um telefone',
            )
            ->email(
                name: 'responsavel_email',
                label: 'E-mail',
                placeholder: 'Digite um e-mail'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Clube', function () use ($Painel) {
        $Painel
            ->input(
                name: 'titulo',
                label: 'Título',
                placeholder: 'Digite um título para o clube',
                contador: 100,
            )
            ->select(
                name: 'tipo_estabelecimento',
                lista: (new TipoEstabelecimento())->select('Escolha uma opção'),
                label: 'Tipo de estabelecimento',
                placeholder: 'Tipo de estabelecimento',
            )
            ->select(
                name: 'origem_lead',
                lista: (new OrigemLead())->select('Escolha uma opção'),
                label: 'Origem do lead',
                placeholder: 'Origem do lead',
                acao: 'add'
            )
            ->select(
                name: 'pontuacao',
                lista: [
                    ''  => 'Escolha uma opção',
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                    '6' => 6,
                    '7' => 7
                ],
                label: 'Pontuação',
                placeholder: 'Escolha uma pontuação',
            )
            ->telefone(name: 'contato_whatsapp', label: 'WhatsApp', placeholder: 'Número do WhatsApp')
            ->uri(name: 'url', label: 'URI do clube', placeholder: 'URI do clube')
            ->switch(name: 'delivery', label: 'Parceiro faz delivery?')
            ->switch(name: 'convenio_direto', label: 'É um convênio direto?');
    });

    $Painel->fieldset('Contrato', function () use ($Painel) {
        $Painel
            ->data(name: 'data_contrato_inicio', label: 'Data do contrato', placeholder: 'Data do contrato')
            ->data(name: 'data_contrato_vencimento', label: 'Data do vencimento', placeholder: 'Data do vencimento')
            ->switch(name: 'precisa_aditivo', label: 'Precisa de aditivo?')
            ->email(name: 'email_contato', label: 'E-mail de contato', placeholder: 'Digite um e-mail de contato');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Procedimentos', function () use ($Painel) {
        $Painel
            ->input(name: 'desconto', label: 'Desconto curto', placeholder: 'Digite um desconto curto')
            ->select(
                name: 'tipo_procedimento',
                lista: (new TipoProcedimento())->select('Escolha uma opção'),
                label: 'Tipo de procedimento',
                placeholder: 'Escolha um procedimento'
            )
            ->select(
                name: 'prazo_declaracao',
                lista: (new PrazoDeclaracao())->select('Escolha uma opção'),
                label: 'Prazo da Declaração (se houver)',
                placeholder: 'Escolha um prazo',
                class: 'display_none',
                id: 'bloco_prazo_declaracao'
            )
            ->numero(
                name: 'limite_voucher',
                label: 'Voucher terá limite mensal?',
                placeholder: 'Limite mensal do voucher',
                class: 'display_none',
                id: 'bloco_limite_voucher'
            )
            ->numero(
                name: 'prazo_voucher',
                label: 'Prazo do voucher em dias',
                placeholder: 'Prazo do voucher em dias',
                class: 'display_none',
                id: 'bloco_prazo_voucher'
            )
            ->data(
                name: 'prazo_voucher_fixo',
                label: 'Prazo do voucher fixo',
                placeholder: 'Prazo do voucher fixo',
                class: 'display_none',
                id: 'bloco_prazo_voucher_fixo'
            )
            ->url(name: 'link_site', label: 'Link do site', placeholder: 'Link do site');
    });

    $Painel->fieldset('Outros dados', function () use ($Painel) {
        $Painel
            ->switch(name: 'confirmar_status', label: 'Precisa confirmar procedimento?')
            ->select(
                name: 'confirmar_titulo_tipo',
                lista: [
                    'padrao' => 'Título padrão',
                    'outro'  => 'Pesonalizadao'
                ],
                label: 'Título',
                placeholder: 'Escolha um título',
                id: 'bloco_confirmar_titulo_tipo',
                class: 'display_none'
            )
            ->input(
                name: 'confirmar_titulo',
                label: 'Título',
                placeholder: 'Título da confirmação',
                class: 'display_none',
                id: 'bloco_confirmar_titulo',
                contador: 100
            )
            ->select(
                name: 'confirmar_texto_tipo',
                lista: [
                    'padrao' => 'Texto padrão',
                    'outro'  => 'Pesonalizadao'
                ],
                label: 'Texto',
                placeholder: 'Escolha um texto',
                id: 'bloco_confirmar_texto_tipo',
                class: 'display_none'
            )
            ->input(
                name: 'confirmar_texto',
                label: 'Mensagem',
                placeholder: 'Digite uma mensagem',
                class: 'display_none',
                id: 'bloco_confirmar_texto',
                contador: 250
            )
            ->titulo('Extensão:')
            ->tag(name: 'link_alias', label: 'Link para extensão ', placeholder: 'Link para extensão', tipo: 'url')
            ->tag(
                name: 'link_bloqueado',
                label: 'Link bloqueado para extensão ',
                placeholder: 'Link bloqueado para extensão',
                tipo: 'url'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Cashback', function () use ($Painel) {
        $Painel
            ->input(name: 'comissao_minima', label: 'Comissão mínima', placeholder: 'Digite a comissão mínima')
            ->input(name: 'comissao_maxima', label: 'Comissão máxima', placeholder: 'Digite a comissão máxima')
            ->textarea('texto_restricao', label: 'Restrições', placeholder: 'Digite as restrições se houver')
            ->textarea('texto_outro', label: 'Outro', placeholder: 'Digite um texto com dados opicionais');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Arquivos do clube', function () use ($Painel) {
        $Painel->arquivoLista(name: 'arquivo_clube', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
    $Painel->fieldset('Arquivos do painel', function () use ($Painel) {
        $Painel->arquivoLista(name: 'arquivo_painel', diretorio: '0493d060-44ba-470b-a0a2-7211ba138d8c');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Textos', function () use ($Painel) {
        $Painel
            ->textarea(
                name: 'texto_descricao',
                label: 'Descrição',
                placeholder: 'Digite uma descrição'
            )
            ->textarea(
                name: 'texto_desconto',
                label: 'Desconto',
                placeholder: 'Digite um desconto'
            )
            ->textarea(
                name: 'texto_procedimento',
                label: 'Procedimento',
                placeholder: 'Digite um procedimento'
            )
            ->textarea(
                name: 'texto_voucher',
                label: 'Voucher',
                placeholder: 'Digite um texto para o voucher'
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Cupom', function () use ($Painel) {
        $Painel
            ->indiceValor(
                name: 'cupom_desconto',
                placeholder: ['Título', 'Cupom'],
                ordem: true
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Categoria', function () use ($Painel) {
        $Categoria = new Categoria();
        $Painel
            ->select(
                name: 'categoria_principal',
                lista: $Categoria->select('Escolha uma opção'),
                label: 'Categoria',
                placeholder: 'Categoria'
            )
            ->margem(10)
            ->blocoCheckbox(
                titulo: 'Lista de categorias',
                callback: function () use ($Painel, $Categoria) {
                    foreach ($Categoria->select() as $id => $nome) {
                        $Painel->checkbox(name: 'categoria_lista[]', label: $nome, value: $id);
                    }
                }
            );
    });
});

$Painel->coluna(callback: function () use ($Painel, $tag) {
    $Painel->fieldset('Subcategoria', function () use ($Painel, $tag) {
        $Painel
            ->tag(name: 'subcategoria_tag', label: 'Tag', placeholder: 'Digite suas tags', espaco: true)
            ->margem(10)
            ->blocoCheckbox(
                titulo: 'Subcategorias',
                callback: function () use ($Painel, $tag) {
                    foreach ($tag as $titulo => $values) {
                        $Painel->titulo($titulo);
                        foreach ($values as $id => $nome) {
                            $Painel->checkbox(name: 'subcategoria_lista[]', label: $nome, value: $id);
                        }
                    }
                },
                mais: true
            );
    });
});

$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Empresas',
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'empresa[]', label: $nome, value: $id);
            }
        },
        todos: 'Marcar todas as empresas',
        mais: true
    );
});

$Painel->coluna(callback: function () use ($Painel, $empresa) {
    $Painel->fieldsetCheckbox(
        titulo: 'Destaque',
        callback: function () use ($Painel, $empresa) {
            foreach ($empresa as $id => $nome) {
                $Painel->checkbox(name: 'destaque[]', label: $nome, value: $id);
            }
        },
        todos: 'Marcar todas as empresas',
        mais: true
    );
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Estados',
        callback: function () use ($Painel) {
            foreach ((new EnderecoEstado())->select() as $id => $nome) {
                $Painel->checkbox(name: 'endereco_estado[]', label: $nome, value: $id);
            }
        },
        todos: 'Marcar todos os estados',
        mais: true
    );
});

$Painel->js('painel_parceiro_loja_add');

return $Painel;
