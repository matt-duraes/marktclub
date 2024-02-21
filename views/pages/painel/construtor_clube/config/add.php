<?php

use Helpers\ApiHelper;
use App\Classes\Geral\Status;
use App\Classes\ConstrutorClube\TipoAtivacao;
use App\Helpers\PrimeiroAcessoHelper;

$Painel = new PainelConfig\Add(app: 'comercial-empresa', acao: $acao);

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Imagem Tema Light', function () use ($Painel) {
        $Painel
            ->imagem('logo_principal', '118e10b2-58cf-4708-9c1f-3e4392d2e675');
    });
    $Painel->fieldset('Imagem Tema Dark', function () use ($Painel) {
        $Painel
            ->imagem('logo_secundaria', '118e10b2-58cf-4708-9c1f-3e4392d2e675');
    });
    $Painel->fieldset('Imagem Favicon', function () use ($Painel) {
        $Painel
            ->imagem('favicon', '118e10b2-58cf-4708-9c1f-3e4392d2e675');
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Dados do clube', function () use ($Painel) {
        $Painel
            ->input(name: 'titulo', label: 'Título para o clube')
            ->select(
                name: 'empresa',
                label: 'Empresa',
                lista: (new ApiHelper(token: true))
                    ->json(['titulo' => 'Escolha um cliente'])
                    ->get('/comercial-empresa/select')
                    ->array()['dado'] ?? []
            )
            ->cor(
                name: 'cor_principal',
                label: 'Cor do tema light',
            )
            ->cor(
                name: 'cor_secundaria',
                label: 'Cor do tema dark ',
            )
            ->select(
                name: 'tipo_ativacao',
                label: 'Tipo de ativação',
                placeholder: 'Qual o tipo de ativação?',
                lista: (new TipoAtivacao())->select('Escolha um tipo')
            );
    });
    $Painel->fieldset('SEO e status', function () use ($Painel) {
        $Painel
            ->input(name: 'header_descricao', label: 'Descrição', placeholder: 'Digite uma descrição', contador: 155)
            ->tag(name: 'header_tag', label: 'Tags', tipo: 'texto', espaco: true, placeholder: 'Digite a lista de tag')
            ->switch(
                name: 'chat_status',
                label: 'Vai ter chat?'
            )
            ->switch(
                name: 'api_status',
                label: 'O login é via API?'
            )
            ->switch(
                name: 'administrado_status',
                label: 'Administrado pelo Markt Club?'
            )
            ->select(
                name: 'status',
                label: 'Status',
                lista: (new Status())->select('Escolha um status')
            );
    });
});

$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldset('Contato', function () use ($Painel) {
        $Painel
            ->telefone(name: 'contato_telefone', label: 'Telefone', placeholder: 'Digite o telefone de contato')
            ->telefone(name: 'contato_whatsapp', label: 'WhatsApp', placeholder: 'Digite o WhatsApp de contato')
            ->email(name: 'contato_email', label: 'E-mail', placeholder: 'Digite o e-mail de contato')
            ->input(name: 'contato_horario', label: 'Horário de atendimento', placeholder: 'Digite o horário de atendimento')
            ->input(name: 'contato_endereco', label: 'Endereço', placeholder: 'Digite o endereço de atendimento');
    });
    $Painel->fieldset('Liks', function () use ($Painel) {
        $Painel
            ->url(name: 'link_clube', label: 'Link do clube', placeholder: 'Link do clube')
            ->url(name: 'link_login', label: 'Link de login', placeholder: 'Link de login')
            ->url(name: 'link_cadastro', label: 'Link de cadastro', placeholder: 'Link de cadastro')
            ->url(name: 'link_salavip', label: 'Link da salavip', placeholder: 'Link da salavip')
            ->url(name: 'link_odontologico', label: 'Link do plano odontológico', placeholder: 'Link do plano odontológico')
            ->url(name: 'link_app_ios', label: 'Link do APP IOS', placeholder: 'Link do APP IOS')
            ->url(name: 'link_app_android', label: 'Link do APP Android', placeholder: 'Link do APP Android');
    });
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Menu Login',
        mais: false,
        callback: function () use ($Painel) {
            $Painel->checkbox(name: 'menu_faq', label: 'FAQ');
            $Painel->checkbox(name: 'menu_como_funciona', label: 'Como funciona');
            $Painel->checkbox(name: 'menu_primeiro_acesso', label: 'Primeiro Acesso');
            $Painel->checkbox(name: 'menu_meu_parceiro', label: 'Meu Parceiro');
        }
    );
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Plano de saúde',
        mais: false,
        callback: function () use ($Painel) {
            $Painel->checkbox(name: 'menu_saude_vitoria', label: 'Unimed Vitória');
            $Painel->checkbox(name: 'menu_saude_amil', label: 'Amil');
            $Painel->checkbox(name: 'menu_saude_seguro', label: 'Unimed Seguros');
            $Painel->checkbox(name: 'menu_saude_cnu', label: 'Central Nacional Unimed');
            $Painel->checkbox(name: 'menu_saude_florianopolis', label: 'Unimed Florianopolis');
        }
    );
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Descontos',
        mais: false,
        callback: function () use ($Painel) {
            $Painel->checkbox(name: 'menu_loja', label: 'Loja');
            $Painel->checkbox(name: 'menu_mapa', label: 'Mapa');
            $Painel->checkbox(name: 'menu_samsung', label: 'Samsung');
            $Painel->checkbox(name: 'menu_turismo', label: 'Turismo');
            $Painel->checkbox(name: 'menu_farmacia', label: 'Farmacia');
            $Painel->checkbox(name: 'menu_automovel', label: 'Automovel');
            $Painel->checkbox(name: 'menu_cashback', label: 'Cashback');
            $Painel->checkbox(name: 'menu_cupom', label: 'Cupom');
            $Painel->checkbox(name: 'menu_premium', label: 'Loja Premium');
            $Painel->checkbox(name: 'menu_credito_sicoob', label: 'Crédido Sicoob');
            $Painel->checkbox(name: 'menu_indicar_loja', label: 'Indicar loja');
            $Painel->checkbox(name: 'menu_ponto_mais_acao', label: 'Ponto+ Ação');
        }
    );
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Diversão',
        mais: false,
        callback: function () use ($Painel) {
            $Painel->checkbox(name: 'menu_cinema', label: 'Cinema');
            $Painel->checkbox(name: 'menu_corrida', label: 'Corrida');
            $Painel->checkbox(name: 'menu_show_nacional', label: 'Show Nacional');
            $Painel->checkbox(name: 'menu_show_internacional', label: 'Show Internacional');
        }
    );
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Outros botões',
        mais: false,
        callback: function () use ($Painel) {
            $Painel->checkbox(name: 'menu_acesso_rapido', label: 'Acesso rápido');
            $Painel->checkbox(name: 'menu_sair', label: 'Botão de sair');
            $Painel->checkbox(name: 'menu_historico', label: 'Historico');
            $Painel->checkbox(name: 'menu_indicar_usuario', label: 'Indicar amigo');
            $Painel->checkbox(name: 'menu_odontologico', label: 'Odontologico');
            $Painel->checkbox(name: 'menu_dependente', label: 'Dependente');
            $Painel->checkbox(name: 'menu_carteira', label: 'Carteirinha');
            $Painel->checkbox(name: 'menu_salavip', label: 'Salavip');
            $Painel->checkbox(name: 'menu_tema', label: 'Tema');
        }
    );
});
$Painel->coluna(callback: function () use ($Painel) {
    $Painel->fieldsetCheckbox(
        titulo: 'Primeiro acesso',
        mais: false,
        todos: 'Marcar todos',
        callback: function () use ($Painel) {
            foreach (PrimeiroAcessoHelper::CAMPOS as $campo => $label) {
                $Painel->checkbox(name: 'campos_primeiro_acesso[]', value: $campo, label: $label);
            }
        }
    );
    $Painel->input(name: 'grupo_label', label: 'Label do grupo', placeholder: 'Digite o label do grupo', ajuda: "O padrão é: 'Grupo'");
    $Painel->input(name: 'grupo_placeholder', label: 'Placeholder do grupo', placeholder: 'Digite o placeholder do grupo', ajuda: "O padrão é: 'Grupo'");
});

$Painel->js('painel_construtor_clube_add');

return $Painel;
